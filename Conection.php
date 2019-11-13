<?php
    class Conection
    {
        private $host;
        private $driver;
        private $dbName;
        private $username;
        private $password;     
        public function __construct ($host = "localhost", $driver = "mysql", $dbName = "db", $username = "root", $password = "")
        {
            $this->host = $host;
            $this->driver = $driver;
            $this->dbName = $dbName;
            $this->username = $username;
            $this->password = $password;
         }
        /**
         * Get the value of host
         */ 
        public function getHost()
        {
            return $this->host;
         }
        /**
         * Set the value of host
         *
         * @return  self
         */ 
        public function setHost($host)
        {
            $this->host = $host;
            return $this;
         }

        /**
         * Get the value of driver
         */ 
        public function getDriver()
        {
            return $this->driver;
         }
        /**
         * Set the value of driver
         *
         * @return  self
         */ 
        public function setDriver($driver)
        {
             $this->driver = $driver;
             return $this;
         }

        /**
         * Get the value of dbName
         */ 
        public function getDbName()
        {
            return $this->dbName;
         }
        /**
         * Set the value of dbName
         *
         * @return  self
         */ 
        public function setDbName($dbName)
        {
            $this->dbName = $dbName;
            return $this;
         }
        /**
         * Get the value of username
         */ 
        public function getUsername()
        {
            return $this->username;
         }
        /**
         * Set the value of username
         *
         * @return  self
         */ 
        public function setUsername($username)
        {
            $this->username = $username;
            return $this;
         }
        /**
         * Get the value of password
         */ 
        public function getPassword()
        {
            return $this->password;
         }
        /**
         * Set the value of password
         *
         * @return  self
         */ 
        public function setPassword($password)
        {
            $this->password = $password;
            return $this;
         }
        public function createPDO($dir)
        {
            $pdo = 
            "<?php\n".
            "\tclass Conection {\n".
            "\t\tpublic static \$instance;\n".
            "\t\tprivate function __construct(){}\n".
            "\t\tpublic static function getInstance(){\n".
            "\t\t\tif (!isset(self::\$instance)) {\n".
            "\t\t\t\tself::\$instance = new PDO('".$this->getDriver().":host=".$this->getHost().";dbname=".$this->getDbName()."', \"".$this->getUsername()."\",\"".$this->getPassword()."\");\n".
            "\t\t\t\tself::\$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n".
            "\t\t\t }\n".
            "\t\t\treturn self::\$instance;\n".
            "\t\t }\n".
            "\t }\n".
            " ?>";
            $fp = fopen($dir.'conection'.DIRECTORY_SEPARATOR.'Conection.php', 'w');
            fwrite($fp, $pdo);
            fclose($fp);
         }
    }
?>