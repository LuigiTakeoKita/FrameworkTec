<?php
    class TableViewBuilder
    {
        private function generateIssets($atributes)
        {
            $issets = "";
            foreach ($atributes as $key => $value) {
                $issets .= "\t\$".$value->getName()." = isset(\$_POST['".$value->getName()."'])?\$_POST['".$value->getName()."']:\"\";\n";
            }
            return $issets;
        }
        private function genareateGlobalsChanges($table)
        {
            $globalChanges = "";
            foreach ($table->getAtributes() as $key => $value) {
                $globalChanges .= "\t\t\$GLOBALS['".$value->getName()."'] = \$".$table->getName()."->get".ucfirst($value->getName())."();\n";
            }
            return $globalChanges;
        }
        private function generatePhp($table)
        {
            $php = 
            ":issets".
 	        "\tif (\$".$table->getAtributes()[0]->getName()."!=\"\") {\n".
 		    "\t\t\$action = \"Update\";\n".
	 	    "\t\t\$control = new Controller;\n".
            "\t\t\$".$table->getName()."=\$control->selectPK".ucfirst($table->getName())."();\n".
            "\t\tcontent(\$".$table->getName().");\n".
            "\t }else{\n".
            "\t\t\$action=\"Add\";\n".
            "\t  }\n".
            "\tfunction content(\$".$table->getName()."){\n".
            ":globalsChanges".
            "\t }\n";
            $php = str_replace(":issets", $this->generateIssets($table->getAtributes()), $php);
            $php = str_replace(":globalsChanges", $this->genareateGlobalsChanges($table), $php);
            return $php;
        }
        private function generateFields($atributes)
        {
            $fields = "\t\t<div class=\"form-row\">\n";
            for ($i=0; $i < sizeof($atributes); $i++) { 
                $fields .= 
                "\t\t\t<div class=\"form-group col-md-4\">\n".
                "\t\t\t\t<label>".ucfirst($atributes[$i]->getName())."</label>\n".
                "\t\t\t\t<input type=\"text\" id=\"".$atributes[$i]->getName()."\" name=\"".$atributes[$i]->getName()."\" class=\"form-control\" placeholder=\"".ucfirst($atributes[$i]->getName())."\" value=\"<?= \$".$atributes[$i]->getName().";?>\">\n".
                "\t\t\t </div>\n";
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
			"\t\t<input type=\"hidden\" name=\"".$table->getAtributes()[0]->getName()."\" value=\"<?= \$".$table->getAtributes()[0]->getName().";?>\">\n".
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
            $tableView = str_replace(":fields", $this->generateFields($table->getAtributes()), $tableView);
            $f = fopen($dir."view". DIRECTORY_SEPARATOR. $table->getName(). "TableView.php", "w");
            fwrite($f, $tableView);
            fclose($f);
        }
     }
 ?>