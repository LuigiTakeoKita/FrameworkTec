<?php
    class Informations
    {
        private $projectName;
        private $description;
        public function __construct ($projectName = "Name", $description = "Des")
        {
                $this->projectName = $projectName;
                $this->description = $description;
         }
        /**
         * Get the value of projectName
         */ 
        public function getProjectName()
        {
                return $this->projectName;
         }
        /**
         * Set the value of projectName
         *
         * @return  self
         */ 
        public function setProjectName($projectName)
        {
                $this->projectName = $projectName;

                return $this;
         }
        /**
         * Get the value of description
         */ 
        public function getDescription()
        {
                return $this->description;
         }
        /**
         * Set the value of description
         *
         * @return  self
         */ 
        public function setDescription($description)
        {
                $this->description = $description;

                return $this;
         }
        public function createREADME($dir)
        {
            $txt = "Project Name: ".$this->getProjectName()."\nDescription: ".$this->getDescription();
            $fp = fopen($dir.'README.txt', 'w');
            fwrite($fp, $txt);
            fclose($fp); 
         }
    }
?>