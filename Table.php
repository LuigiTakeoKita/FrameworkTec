<?php
    class Table
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
        public function generateAttributes()
        {
            $stri = "";
            foreach ($this->getAtributes() as $attr) {
                $stri .= "       private $". $attr->getName() . ";\n";
            }
            return $stri;
         }
        public function generateGetSet()
        {	
            $stri = "";
            foreach ($this->getAtributes() as $attr) {
                $stri .= 
                "       public function get". ucfirst($attr->getName()) . "(){\n".
                "           return \$this-> ". $attr. ";\n".
                "       }\n";
                $stri .= 
                "       public function set". ucfirst($attr->getName()) . "($". $attr ."){\n".
                "           \$this-> ". $attr. " = \$". $attr. ";\n".
                "       }\n";
            }
            return $stri;
         }
        public function generateToString()
        {	
            $stri = 
            "       public function __toString(){\n".
            "           return\n";
            for ($i = 0; $i < count($this->getAtributes()); $i++) {
                $stri .= "              '| ". $this->getAtributes()[$i]->getName(). ' = \'. $this-> '. $this->getAtributes()[$i]->getName(). ".' '";
                if ($i != count($this->getAtributes()) - 1) $stri .= ". \n    ";
                else $stri .= ". '|';\n }";
            }
            return $stri;
         }
        public function createDTO($dir)
        {
            $dto = 
            "<?php\n".
            "   class ".$this->getName().
            "   {".
            ":atributes".
            ":getset".
            ":toString".
            "   }\n".
            "?>";
            $atrs = [];
            $dto = str_replace(":atributes", $this->generateAttributes());
            $dto = str_replace(":getset", $this->generateGetSet());
            $dto = str_replace(":toString", $this->generateToString());
            $fp = fopen($dir.'dto'.DIRECTORY_SEPARATOR.$this->getName().'.php', 'w');
            fwrite($fp, $dto);
            fclose($fp);
         }
        public function createDAO($dir)
        {
            $dao = 
            "<?php\n".
            "   class ".$this->getName()."DAO".
            "   {".
            ":insert".
            ":update".
            ":delete".
            ":selectAll".
            ":selects".
            "   }\n".
            "?>";;
            $fp = fopen($dir.'dao'.DIRECTORY_SEPARATOR.$this->getName().'DAO.php', 'w');
            fwrite($fp, $dao);
            fclose($fp);
         }
        public function createBO($dir)
        {
            $bo = 
            "<?php\n".
            "   class ".$this->getName()."BO".
            "   {".
            "       private \$dao;\n".
            "       public function __construct(\$dao){".
            "           \$this->dao = \$dao;".
            "       }\n".
            "       public function insert(".strtolower($this->getName())." = null)\n".
            "       {\n".
            "           \$dao->insert(".strtolower($this->getName()).");".
            "       }".
            "       public function update(".strtolower($this->getName())." = null)\n".
            "       {\n".
            "           \$dao->update(".strtolower($this->getName()).");".
            "       }".
            "       public function delete(\$id) = 0)\n".
            "       {\n".
            "           \$dao->delete(\$id);".
            "       }".
            "       public function selectAll()\n".
            "       {\n".
            "           return \$dao->selectAll();".
            "       }".
            "   }\n".
            "?>";;
            $fp = fopen($dir.'bo'.DIRECTORY_SEPARATOR.$this->getName().'BO.php', 'w');
            fwrite($fp, $bo);
            fclose($fp);
         }
     } 
 ?>