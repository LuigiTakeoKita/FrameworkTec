<?php
    class Atribute
    {
        private $name;
        private $type;
        private $foreignKey;
        public function __construct ($name = "number", $type = "int", $foreignKey = "")
        {
            $this->name = $name;
            $this->type = $type;
            $this->foreignKey = $foreignKey;
         }
        /**
         * Get the value of name
         */ 
        public function getName()
        {
            return $this->name;
         }
        /**
         * Set the value of name
         *
         * @return  self
         */ 
        public function setName($name)
        {
            $this->name = $name;
            return $this;
         }

        /**
         * Get the value of type
         */ 
        public function getType()
        {
            return $this->type;
         }
        /**
         * Set the value of type
         *
         * @return  self
         */ 
        public function setType($type)
        {
            $this->type = $type;
            return $this;
         }
        /**
         * Get the value of foreignKey
         */ 
        public function getForeignKey()
        {
            return $this->foreignKey;
         }
        /**
         * Set the value of foreignKey
         *
         * @return  self
         */ 
        public function setForeignKey($foreignKey)
        {
            $this->foreignKey = $foreignKey;
            return $this;
         }
     }
 ?>