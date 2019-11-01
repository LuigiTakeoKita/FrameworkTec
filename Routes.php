<?php
    class Routes
    {
        private $routes;
        private $errorPage;
        public function __construct($routes, $errorPage)
        {
            $this->routes = $routes;
            $this->errorPage = $errorPage;
         }
        /**
         * Get the value of routes
         */ 
        public function getroutes()
        {
            return $this->routes;
         }
        /**
         * Set the value of routes
         *
         * @return  self
         */ 
        public function setRoutes($routes)
        {
            $this->routes = $routes;
            return $this;
         }
        /**
         * Get the value of errorPage
         */ 
        public function getErrorPage()
        {
            return $this->errorPage;
         }
        /**
         * Set the value of errorPage
         *
         * @return  self
         */ 
        public function setErrorPage($errorPage)
        {
            $this->errorPage = $errorPage;
            return $this;
         }
        public function createHtAccess($dir)
        {
            $htaccess = 
            "RewriteEngine On\n".
            "RewriteCond %{REQUEST_FILENAME} !-f\n".
            "RewriteCond %{REQUEST_FILENAME} !-d\n".
            "RewriteRule ^(.*)$ index.php?url=$1 [NC,L,QSA]";
            $fp = fopen($dir.'.htaccess', 'w');
            fwrite($fp, $htaccess);
            fclose($fp);
         }
        public function createErrorPage($dir)
        {
            $name = explode("/", $this->getErrorPage());
            $name = $name[sizeof($name)-1];
            $error = 
            "<?php\n".
            "   echo \"".$name."\";\n".
            "?>";
            $fp = fopen($dir."view".DIRECTORY_SEPARATOR.$name, 'w');
            fwrite($fp, $error);
            fclose($fp);
         }
        public function createRoutes($dir)
        {
            $routes = 
            "<?php\n".
            "   require_once \"Controller.php\";\n".
            "   class Routes {\n".
            "       private \$routes;\n".
            "       public function __construct(){\n".
            "           \$file = file_get_contents(\"Routes.json\", FILE_USE_INCLUDE_PATH);\n".
            "           \$json = json_decode(\$file, true);\n".
            "           \$this->routes = \$json;\n".
            "           \$control = new Controller;\n".
            "           foreach (array_keys(\$this->routes) as \$value) {\n".
            "               if (\$this->routes[\$value]['controller']==\"Controller\") {\n".
            "                   \$this->routes[\$value]['controller'] = \$control;\n".
            "               }\n".
            "           }\n".
            "       }\n".
            "       public function saveRoutes(){\n".
            "           \$json = json_encode(\$this->routes,JSON_FORCE_OBJECT );\n".
            "           \$fp = fopen('Routes.json', 'w');\n".
            "           fwrite(\$fp, \$json);\n".
            "           fclose(\$fp);\n".
            "       }\n".
            "       public function addRoute(\$url, \$controller, \$method){\n".
            "           \$this->routes[\$url] = [\"controller\" => \$controller, \"method\" => \$method];\n".
            "           \$this->saveRoutes();\n".
            "       }\n".
            "       public function run(\$url){\n".
            "           if (array_key_exists(\$url, \$this->routes)) {\n".
            "               \$res = \$this->routes[\$url];\n".
            "               \$res['controller']->{\$res['method']}();\n".
            "           } else {\n".
            "               \$arr = explode(\"/\", \$url);\n".
            "               \$last = \$arr[sizeof(\$arr)-1];\n".
            "               \$arr[sizeof(\$arr)-1] = preg_replace('/[a-z][a-zA-Z0-9]*/', ':string', \$arr[sizeof(\$arr)-1]);\n".
            "               \$arr[sizeof(\$arr)-1] = preg_replace('/[0-9]+\.[0-9]+/', ':float', \$arr[sizeof(\$arr)-1]);\n".
            "               \$arr[sizeof(\$arr)-1] = preg_replace('/[0-9]+/', ':int', \$arr[sizeof(\$arr)-1]);\n".
            "               \$url = implode(\"/\", \$arr);\n".
            "               if (array_key_exists(\$url, \$this->routes)) {\n".
            "                   \$res = \$this->routes[\$url];\n".
            "                   \$res[\"controller\"]->{\$res[\"method\"]}(\$last);\n".
            "               }else{\n".
            "                   header(\"Location: ".$this->getErrorPage()."\");\n".
            "               }\n".
            "           }\n".
            "       }\n".
            "   }\n".
            "?>";
            $fp = fopen($dir.'controller'.DIRECTORY_SEPARATOR.'Routes.php', 'w');
            fwrite($fp, $routes);
            fclose($fp);
         }  
        public function createRoutesJson($dir)
        {
            $arr = array();
            foreach ($this->getRoutes() as $key => $value) {
                $arr[$key] = ["controller" => $value[0], "method" => $value[1]];
            }
            $json = json_encode($arr);
            $fp = fopen($dir.'controller'.DIRECTORY_SEPARATOR.'Routes.json', 'w');
            fwrite($fp, $json);
            fclose($fp);
         }
    }
?>