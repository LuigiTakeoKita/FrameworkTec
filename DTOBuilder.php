<?php
    class DTOBuilder
    {
        private function generateAttributes($table)
        {
            $stri = "";
            foreach ($table->getAtributes() as $attr) {
                $stri .= "       private $". $attr->getName() . ";\n";
            }
            return $stri;
         }
        private function generateGetSet($table)
        {	
            $stri = "";
            foreach ($table->getAtributes() as $attr) {
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
        private function generateToString($table)
        {	
            $stri = 
            "       public function __toString(){\n".
            "           return\n";
            for ($i = 0; $i < count($table->getAtributes()); $i++) {
                $stri .= "              '| ". $table->getAtributes()[$i]->getName(). ' = \'. $this-> '. $table->getAtributes()[$i]->getName(). ".' '";
                if ($i != count($table->getAtributes()) - 1) $stri .= ". \n    ";
                else $stri .= ". '|';\n }";
            }
            return $stri;
         }
        public function createDTO($dir, $table)
        {
            $dto = 
            "<?php\n".
            "   class ".ucfirst($table->getName()).
            "   {".
            ":atributes".
            ":getset".
            ":toString".
            "   }\n".
            "?>";
            $dto = str_replace(":atributes", $this->generateAttributes($table), $dto);
            $dto = str_replace(":getset", $this->generateGetSet($table), $dto);
            $dto = str_replace(":toString", $this->generateToString($table), $dto);
            $fp = fopen($dir.'dto'.DIRECTORY_SEPARATOR.ucfirst($table->getName()).'.php', 'w');
            fwrite($fp, $dto);
            fclose($fp);
         }
     }
 ?>