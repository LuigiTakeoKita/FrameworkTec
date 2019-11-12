<?php
    require_once "Informations.php";
    require_once "Folders.php";
    require_once "Conection.php";
    require_once "Routes.php";
    require_once "Atribute.php";
    require_once "SQLTable.php";
    require_once "DTOBuilder.php";
    require_once "DAOBuilder.php";
    require_once "BOBuilder.php";
    require_once "SQLReader.php";
    class Builder
    {
        public function init()
        {
            if(file_exists('builder.json')){
                $fjosn = file_get_contents('builder.json', 'r');
                $json = json_decode($fjosn, true);
                $info = new Informations($json['projectName'], $json['description']);
                mkdir($info->getProjectName(), 0700);
                $dir = getcwd(). DIRECTORY_SEPARATOR.$info->getProjectName(). DIRECTORY_SEPARATOR;
                $info->createREADME($dir);
                $defaultsFolders = [
                    "controller",
                    "bo",
                    "dao",
                    "dto",
                    "view",
                    "conection"
                 ];
                if(array_key_exists('folders', $json)){
                    $folders = new Folders(array_merge($defaultsFolders, $json['folders']));    
                 }else{
                    $folders = new Folders($defaultsFolders);    
                 }
                $folders->createFolders($dir);
                $folders->createAutoload($dir);
                $conflag = false;
                if(array_key_exists('pdo', $json)){
                    $con = new Conection($json['pdo']['host'], $json['pdo']['driver'], $json['pdo']['dbName'], $json['pdo']['username'], $json['pdo']['password']);
                    $con->createPDO($dir);
                    $conflag=true;
                 }
                $routes = null;
                if(array_key_exists('classes',$json)){
                    $dtob = new DTOBuilder;
                    foreach ($json['classes'] as $key => $value) {
                        $table = new SQLTable;
                        $table->setName(strtolower($key));
                        foreach ($value as $key2 => $value2) {
                            $atr = new Atribute;
                            $atr->setName(strtolower($value2));
                            $table->addAtribute($atr);
                        }
                        $dtob->createDTO($dir, $table);
                    }
                 }
                if(array_key_exists('tables',$json)){
                    $sql = new SQLReader;
                    $arr = $sql->read($json['tables']);
                    $dtob = new DTOBuilder();
                    $daob = new DAOBuilder();
                    $bob = new BOBuilder();
                    foreach ($arr as $key => $value) {
                        $dtob->createDTO($dir, $value);
                        $daob->createDAO($dir, $value);
                        // $bob->createBO($dir, $value);
                    }
                 }
                if(array_key_exists('routes', $json)){
                    $routes = new Routes($json['routes']['defaults'], $json['routes']['errorPage']);
                    $routes->createHtAccess($dir);
                    $routes->createErrorPage($dir);
                    $routes->createRoutes($dir);
                    $routes->createRoutesJson($dir);
                 }
                $this->createController($dir, $conflag, $routes);
                $this->createIndex($dir, $routes);
                return $dir;
             }else{
                print("Error 404: file not found.");
                return "";
             }
         }
        private function createController($dir, $con, $routes)
        {
            $controller = 
            "<?php\n".
            "    require_once \"../autoload.php\";\n".
            "    class Controller\n".
            "    {\n";
            if ($con) {
                $controller .= 
                "        public function getPdo()\n".
                "        {\n".
                "             return Conection::getInstance();\n".
                "        }\n";
             }
            if ($routes != null) {
                foreach ($routes->getRoutes() as $key => $value) {
                    if($value[0] == "Controller"){
                        if (preg_match('/:int$|:string$|:float$/', $key)) {
                            $controller .= 
                            "        public function ".$value[1]."(\$var)\n".
                            "        {\n".
                            "            echo \"".$value[1].": \".\$var;\n".
                            "        }\n";
                         }else{
                            $controller .= 
                            "        public function ".$value[1]."()\n".
                            "        {\n".
                            "            echo \"".$value[1]."\";\n".
                            "        }\n";
                         }
                     }
                 }
             }
            $controller .= "    }\n?>";
            $fp = fopen($dir.'controller'.DIRECTORY_SEPARATOR.'Controller.php', 'w');
            fwrite($fp, $controller);
            fclose($fp);
         }
        private function createIndex($dir, $routes)
        {
            $index = "<?php\n    require_once \"../autoload.php\";";
            if ($routes != null) {
                $index.="\n    // \$routes = new Routes;\n    // if(\$_GET){\n    //     // var_dump(\$_GET);\n    //     \$routes->run(\$_GET['url']);\n    // }else {\n    //     echo \"empty\";\n    // }";
            }
            $index .="\n    \$control = new Controller;\n    \$pdo = null;".
            "\n    \$pdo = \$control->getPdo();".
            "\n    if(\$pdo != null){".
            "\n        echo \"Funcionou\";\n    }else {\n        echo \"Falhou\";\n    }\n?>";
            $fp = fopen($dir.'view'.DIRECTORY_SEPARATOR.'index.php', 'w');
            fwrite($fp, $index);
            fclose($fp);
         } 
    }
 ?>