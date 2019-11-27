<?php
    class ControllerBuilder
    {
        private function generateConstruct($tables)
        {
            $construct = "";
            if($tables != []){
                $construct =
                ":vars".
                "\t\tpublic function __construct() {\n".
                ":daos".
                "\t\t }\n";
                $vars = "";
                $daos = "";
                foreach ($tables as $key => $value) {
                    $vars .= "\t\tprivate \$".$value->getName()."DAO;\n";
                    $daos .= "\t\t\t\$this->".$value->getName()."DAO = new ".ucfirst($value->getName())."DAO;\n";
                 }
                $construct = str_replace(":vars", $vars, $construct);
                $construct = str_replace(":daos", $daos, $construct);
             }
            return $construct;
         }
        private function generateGetPDO($pdo)
        {
            if ($pdo) {
                return  
                "\t\tpublic function getPdo()\n".
                "\t\t{\n".
                "\t\t\treturn Conection::getInstance();\n".
                "\t\t }\n";
             }
            return "";
         }
        private function generateRoutes($routes)
        {
            $phpRoutes = "";
            if ($routes != null) {
                foreach ($routes->getRoutes() as $key => $value) {
                    if($value[0] == "Controller"){
                        if (preg_match('/:int$|:string$|:float$/', $key)) {
                            $phpRoutes .= 
                            "\t\tpublic function ".$value[1]."(\$var)\n".
                            "\t\t{\n".
                            "\t\t\techo \"".$value[1].": \".\$var;\n".
                            "\t\t }\n";
                         }else{
                            $phpRoutes .= 
                            "\t\tpublic function ".$value[1]."()\n".
                            "\t\t{\n".
                            "\t\t\techo \"".$value[1]."\";\n".
                            "\t\t }\n";
                         }
                     }
                 }
             }
            return $phpRoutes;
         }
        private function generateModelMethods($tables)
        {
            $methods = "";
            if ($tables != []) {
                foreach ($tables as $key => $value) {
                    $methods .=
                    "\t\tpublic function insert".ucfirst($value->getName())."(\$".$value->getName()." = null) {\n".
                    "\t\t\t\$this->".$value->getName()."DAO->insert(\$".$value->getName().");\n".
                    "\t\t }\n".
                    "\t\tpublic function update".ucfirst($value->getName())."(\$".$value->getName()." = null) {\n".
                    "\t\t\t\$this->".$value->getName()."DAO->update(\$".$value->getName().");\n".
                    "\t\t }\n".
                    "\t\tpublic function delete".ucfirst($value->getName())."(\$".$value->getName()." = null) {\n".
                    "\t\t\t\$this->".$value->getName()."DAO->delete(\$".$value->getName().");\n".
                    "\t\t }\n".
                    "\t\tpublic function selectAll".ucfirst($value->getName())."() {\n".
                    "\t\t\treturn \$this->".$value->getName()."DAO->selectAll();\n".
                    "\t\t }\n".
                    "\t\tpublic function selectPK".ucfirst($value->getName())."(\$".$value->getName()." = null) {\n".
                    "\t\t\treturn \$this->".$value->getName()."DAO->selectPK(\$".$value->getName().");\n".
                    "\t\t }\n";
                 }
             }
            return $methods;
         }
        public function createController($dir, $con, $routes, $tables)
        {
            $controller = 
            "<?php\n".
            "\trequire_once \"../autoload.php\";\n".
            "\tclass Controller\n".
            "\t{\n".
            ":constructor".
            ":getPDO".
            ":routes".
            ":modelMethods".
            "\t }\n".
            " ?>";
            $controller = str_replace(":constructor", $this->generateConstruct($tables), $controller);
            $controller = str_replace(":getPDO", $this->generateGetPDO($con), $controller);
            $controller = str_replace(":routes", $this->generateRoutes($routes), $controller);
            $controller = str_replace(":modelMethods", $this->generateModelMethods($tables), $controller);
            $fp = fopen($dir.'controller'.DIRECTORY_SEPARATOR.'Controller.php', 'w');
            fwrite($fp, $controller);
            fclose($fp);
         }
     }
 ?>