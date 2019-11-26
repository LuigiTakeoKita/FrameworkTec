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
    require_once "InterfaceBuilder.php";
    require_once "ControllerBuilder.php";
    require_once "HeaderBuilder.php";
    require_once "FooterBuilder.php";
    require_once "SelectViewBuilder.php";
    require_once "TableViewBuilder.php";
    class Builder
    {
        public function init()
        {
            if(file_exists('builder.json')){
                $fjosn = file_get_contents('builder.json', 'r');
                $json = json_decode($fjosn, true);
                $info = new Informations($json['projectName'], $json['description']);
                $dir = getcwd(). DIRECTORY_SEPARATOR.$info->getProjectName(). DIRECTORY_SEPARATOR;
                if (! (file_exists($dir) and is_dir($dir))) mkdir($info->getProjectName(), 0700);
                $info->createREADME($dir);
                $defaultsFolders = [
                    "controller",
                    "bo",
                    "dao",
                    "dto",
                    "view",
                    "conection",
                    "css"
                 ];
                if(array_key_exists('tables',$json)){
                    array_push($defaultsFolders, "interface");
                } 
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
                $arr = [];
                if(array_key_exists('tables',$json)){
                    $interfaceB = new InterfaceBuilder;
                    $interfaceB->createInteface($dir);
                    $sql = new SQLReader;
                    $arr = $sql->read($json['tables']);
                    $dtob = new DTOBuilder();
                    $daob = new DAOBuilder();
                    $bob = new BOBuilder();
                    $svb = new SelectViewBuilder;
                    $tvb = new TableViewBuilder;
                    foreach ($arr as $key => $value) {
                        $dtob->createDTO($dir, $value);
                        $daob->createDAO($dir, $value);
                        $bob->createBO($dir, $value);
                        $svb->createSelectView($dir, $value);
                        $tvb->createTableView($dir, $value);
                    }
                    $hb = new HeaderBuilder;
                    $hb->createHeader($dir, $arr, $info);
                    $fb = new footerBuilder;
                    $fb->createFooter($dir);
                 }
                if(array_key_exists('routes', $json)){
                    $routes = new Routes($json['routes']['defaults'], $json['routes']['errorPage']);
                    $routes->createHtAccess($dir);
                    $routes->createErrorPage($dir);
                    $routes->createRoutes($dir);
                    $routes->createRoutesJson($dir);
                 }
                $contBuilder = new ControllerBuilder;
                $contBuilder->createController($dir, $conflag, $routes, $arr);
                $this->createIndex($dir, $routes);
                return $info->getProjectName();
             }else{
                print("Error 404: file not found.");
                return "";
             }
         }
        private function createIndex($dir, $routes)
        {
            $index = "<?php\n    require_once \"../autoload.php\";".
            "\trequire_once(\"header.php\");";
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