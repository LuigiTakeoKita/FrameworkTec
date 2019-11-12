<?php
    class SQLTable
    {
        private $name;
        private $atributes;
        public function __construct($name = "", $atributes = []) {
            $this->name = $name;
            $this->atributes = $atributes;
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
         * Get the value of atributes
         */ 
        public function getAtributes()
        {
            return $this->atributes;
         }
        /**
         * Set the value of atributes
         *
         * @return  self
         */ 
        public function setAtributes($atributes)
        {
            $this->atributes = $atributes;
            return $this;
         }
        public function addAtribute($atribute)
        {
            if ($atribute instanceof Atribute) {
                array_push($this->atributes, $atribute);
             }
         }
     }
 ?>