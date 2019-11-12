<?php
    class DTOBuilder
    {
        private function generateAttributes($table)
        {
            $stri = "";
            foreach ($table->getAtributes() as $attr) {
                $stri .= "\t\tprivate $". $attr->getName() . ";\n";
            }
            return $stri;
         }
        private function generateGetSet($table)
        {	
            $stri = "";
            foreach ($table->getAtributes() as $attr) {
                $stri .= 
                "\t\tpublic function get". ucfirst($attr->getName()) . "(){\n".
                "\t\t\treturn \$this-> ". $attr->getName(). ";\n".
                "\t\t }\n";
                $stri .= 
                "\t\tpublic function set". ucfirst($attr->getName()) . "($". $attr->getName() ."){\n".
                "\t\t\t\$this-> ". $attr->getName(). " = \$". $attr->getName(). ";\n".
                "\t\t }\n";
            }
            return $stri;
         }
        private function generateToString($table)
        {	
            $stri = 
            "\t\tpublic function __toString(){\n".
            "\t\t\treturn\n";
            for ($i = 0; $i < count($table->getAtributes()); $i++) {
                $stri .= "\t\t\t\t'| ". $table->getAtributes()[$i]->getName(). ' = \'. $this-> '. $table->getAtributes()[$i]->getName(). ".' '";
                if ($i != count($table->getAtributes()) - 1) $stri .= ". \n";
                else $stri .= ". '|';\n\t\t }\n";
            }
            return $stri;
         }
        public function createDTO($dir, $table)
        {
            $dto = 
            "<?php\n".
            "\tclass ".ucfirst($table->getName()).
            "\t{\n".
            ":atributes".
            ":getset".
            ":toString".
            "\t }\n".
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