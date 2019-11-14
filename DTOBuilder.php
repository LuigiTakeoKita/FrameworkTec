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
            foreach ($table->getAtributes() as $atr) {
                $var = "";
                if ($atr->getForeignKey()=="") {
                    $var = $atr->getName();
                 } else {
                    $var = $atr->getForeignKey();
                  }
                $stri .= 
                "\t\tpublic function get". ucfirst($var) . "(){\n".
                "\t\t\treturn \$this->". $var. ";\n".
                "\t\t }\n".
                "\t\tpublic function set". ucfirst($var) . "($". $var ."){\n".
                "\t\t\t\$this->". $var. " = \$". $var. ";\n".
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
                if($table->getAtributes()[$i]->getForeignKey() == ""){
                    $stri .= "\t\t\t\t'| ". $table->getAtributes()[$i]->getName(). ' = \'. $this->'. $table->getAtributes()[$i]->getName(). ".' '";
                 } else {
                    $stri .= "\t\t\t\t'| ". $table->getAtributes()[$i]->getForeignKey(). ' = \'. $this->'. $table->getAtributes()[$i]->getForeignKey(). ".' '";
                  }
                if ($i != count($table->getAtributes()) - 1) $stri .= ". \n";
                else $stri .= ". '|';\n\t\t }\n";
            }
            return $stri;
         }
        public function createDTO($dir, $table)
        {
            $dto = 
            "<?php\n".
            "\tclass ".ucfirst($table->getName())." {\n".
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