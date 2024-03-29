<?php
    class TableViewBuilder
    {
        private function generateIssets($table)
        {
            $issets = "";
            foreach ($table->getAtributes() as $key => $value) {
                $issets .= "\t\$".$value->getName()." = isset(\$_POST['".$table->getName()."_".$value->getName()."'])?\$_POST['".$table->getName()."_".$value->getName()."']:\"\";\n";
            }
            return $issets;
        }
        private function genareateGlobalsChanges($table)
        {
            $globalChanges = "";
            foreach ($table->getAtributes() as $key => $value) {
                if($value->getForeignKey()==""){
                    $globalChanges .= "\t\t\$GLOBALS['".$value->getName()."'] = \$".$table->getName()."->get".ucfirst($value->getName())."();\n";
                }else{
                    $globalChanges .= "\t\t\$GLOBALS['".$value->getName()."'] = \$".$table->getName()."->get".ucfirst($value->getForeignKey())."()->get".ucfirst($value->getReference())."();\n";
                }
            }
            return $globalChanges;
        }
        private function generateSelects($table)
        {
            $selecteds = "";
            $pk = 1;
            foreach ($table->getAtributes() as $key => $value) {
                if ($value->getForeignKey()!="") {
                    $selecteds .= 
                    "\t\$pk".$pk." = selectPks".$pk."(\$".$value->getName().");\n".
                    "\tfunction selectPks".$pk."(\$".$value->getName()."){\n".
                    "\t\t\$pk = \n".
                    "\t\t\"<select class=\\\"form-control\\\"name=\\\"".$value->getForeignKey()."_".$value->getName()."\\\" id=\\\"".$value->getForeignKey()."_".$value->getName()."\\\">\\n\".\n".
                    "\t\t\":options\".\n".
                    "\t\t\"</select>\";\n".
                    "\t\t\$arr".ucfirst($value->getForeignKey())." = \$GLOBALS['control']->selectAll".ucfirst($value->getForeignKey())."();\n".
                    "\t\t\$options = \"\";\n".
                    "\t\tforeach (\$arr".ucfirst($value->getForeignKey())." as \$key => \$value) {\n".
                    "\t\t\tif(\$value->get".ucfirst($value->getReference())."() == \$".$value->getName()."){\n".
                    "\t\t\t\t\$options .= \"<option value=\\\"\".\$value->get".ucfirst($value->getReference())."().\"\\\" selected>\".\$value->get".ucfirst($value->getReference())."().\"</option>\\n\";\n".
                    "\t\t\t }else{\n".
                    "\t\t\t\t\$options .= \"<option value=\\\"\".\$value->get".ucfirst($value->getReference())."().\"\\\">\".\$value->get".ucfirst($value->getReference())."().\"</option>\\n\";\n".
                    "\t\t\t  }\n".
                    "\t\t }\n".
                    "\t\t\$pk = str_replace(\":options\", \$options, \$pk);\n".
                    "\t\treturn \$pk;\n".
                    "\t }\n";
                    $pk++;
                }
            }
            return $selecteds;
        }
        private function generatePhp($table)
        {
            $php = 
            ":issets".
            "\t\$control = new Controller;\n".
 	        "\tif (\$".$table->getAtributes()[0]->getName()."!=\"\") {\n".
            "\t\t\$action = \"Update\";\n".
            "\t\t\$".$table->getName()." = new ".ucfirst($table->getName()).";\n".
            "\t\t\$".$table->getName()."->set".ucfirst($table->getAtributes()[0]->getName())."(\$".$table->getAtributes()[0]->getName().");\n".
            "\t\t\$".$table->getName()." = \$control->selectPK".ucfirst($table->getName())."(\$".$table->getName().");\n".
            "\t\tcontent(\$".$table->getName().");\n".
            "\t }else{\n".
            "\t\t\$action = \"Add\";\n".
            "\t  }\n".
            ":selecteds".
            "\tfunction content(\$".$table->getName()."){\n".
            ":globalsChanges".
            "\t }\n";
            $php = str_replace(":issets", $this->generateIssets($table), $php);
            $php = str_replace(":selecteds", $this->generateSelects($table), $php);
            $php = str_replace(":globalsChanges", $this->genareateGlobalsChanges($table), $php);
            return $php;
        }
        private function generateFields($table)
        {
            $fields = "\t\t<div class=\"form-row\">\n";
            $atributes= $table->getAtributes();
            $pk = 1;
            for ($i=0; $i < sizeof($atributes); $i++) { 
                $fields .= 
                "\t\t\t<div class=\"form-group col-md-4\">\n".
                "\t\t\t\t<label>".ucfirst($atributes[$i]->getName())."</label>\n";
                if($atributes[$i]->getForeignKey()==""){
                    $fields .= "\t\t\t\t<input type=\"text\" id=\"".$table->getName()."_".$atributes[$i]->getName()."\" name=\"".$table->getName()."_".$atributes[$i]->getName()."\" class=\"form-control\" placeholder=\"".ucfirst($atributes[$i]->getName())."\" value=\"<?= \$".$atributes[$i]->getName().";?>\">\n";
                }else{
                    $fields .= "\t\t\t\t<?= \$pk".$pk." ?>\n";
                    $pk++;
                }
                $fields .= "\t\t\t </div>\n";
                if((($i+1)%3==0)&&!($i+1==sizeof($atributes))){
                    $fields .=
                    "\t\t </div>\n".
                    "\t\t<div class=\"form-row\">\n";
                }
            }
            $fields .= "\t\t </div>\n";
            return $fields;
        }
        public function createTableView($dir, $table)
        {
            $tableView =
            "<?php\n".
            "\trequire_once \"../autoload.php\";\n".
            "\trequire_once \"header.php\";\n".
            ":php".
            " ?>\n".
            "<div class=\"container\">\n".
		    "\t<form method=\"post\" action=\"".$table->getName()."SelectView.php\">\n".
			"\t\t<input type=\"hidden\" name=\"".$table->getName()."_".$table->getAtributes()[0]->getName()."\" value=\"<?= \$".$table->getAtributes()[0]->getName().";?>\">\n".
            "\t\t<legend>".ucfirst($table->getName())."</legend>\n".
            ":fields".
            "\t\t\t<div class=\"form-row\">\n".
            "\t\t\t\t<div class=\"col-md-2\">\n".
            "\t\t\t\t\t<input type=\"submit\" name=\"send\" class=\"btn btn-primary\" value=\"<?= \$action;?>\">\n".
            "\t\t\t\t </div>\n".
            "\t\t\t </div>\n".
			"\t\t </form>\n".
		    "\t </div>\n".
	        " </div>\n".
            "<?php require_once(\"footer.php\"); ?>";
            $tableView = str_replace(":php", $this->generatePhp($table), $tableView);
            $tableView = str_replace(":fields", $this->generateFields($table), $tableView);
            $f = fopen($dir."view". DIRECTORY_SEPARATOR. $table->getName(). "TableView.php", "w");
            fwrite($f, $tableView);
            fclose($f);
        }
     }
 ?>