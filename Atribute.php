<?php
    class Atribute
    {
        private $name;
        private $type;
        private $foreignKey;
        private $reference;
        public function __construct ($name = "number", $type = "int", $foreignKey = "", $reference="")
        {
            $this->name = $name;
            $this->type = $type;
            $this->foreignKey = $foreignKey;
            $this->reference = $reference;
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

        /**
         * Get the value of reference
         */ 
        public function getReference()
        {
                return $this->reference;
        }
        /**
         * Set the value of reference
         *
         * @return  self
         */ 
        public function setReference($reference)
        {
                $this->reference = $reference;

                return $this;
        }
     }
 ?>