<?php
    class SelectViewBuilder
    {
        private function generateIssets($table)
        {
            $issets = "";
            foreach ($table->getAtributes() as $key => $value) {
                if ($value->getForeignKey()=="") {
                    $issets .= "\t\t\$".$value->getName()." = isset(\$_POST['".$table->getName()."_".$value->getName()."'])?\$_POST['".$table->getName()."_".$value->getName()."']:\"\";\n";
                } else {
                    $issets .= "\t\t\$".$value->getName()." = isset(\$_POST['".$value->getForeignKey()."_".$value->getName()."'])?\$_POST['".$value->getforeignKey()."_".$value->getName()."']:\"\";\n";
                }
            }
            return $issets;
        }
        private function generateSets($table)
        {
            $sets = "";
            foreach ($table->getAtributes() as $key => $value) {
                if ($value->getForeignKey()=="") {
                    $sets .= "\t\t\$".$table->getName()."->set".ucfirst($value->getName())."(\$".$value->getName().");\n";
                } else {
                    $sets .= 
                    "\t\t\$".$value->getForeignKey()." = new ".ucfirst($value->getForeignKey()).";\n".
                    "\t\t\$".$value->getForeignKey()."->set".ucfirst($value->getReference())."(\$".$value->getName().");\n".
                    "\t\t\$".$table->getName()."->set".ucfirst($value->getForeignKey())."(\$".$value->getForeignKey().");\n";
                }
            }
            return $sets;
        }
        private function generateTds($atributes)
        {
            $tds = "";
            foreach ($atributes as $key => $value) {
                if ($value->getForeignKey()=="") {
                    $tds .= "\t\t\t\"<td>\".\$value->get".ucfirst($value->getName())."().\"</td>\".\n";    
                } else {
                    $tds .= "\t\t\t\"<td>\".\$value->get".ucfirst($value->getForeignKey())."()->get".ucfirst($value->getReference())."().\"</td>\".\n";
                }
            }
            return $tds;
        }
        private function generatePhp($table)
        {
            $php =
            "\t\$action = isset(\$_POST['send'])?\$_POST['send']:\"\";\n".
            "\t\$control = new Controller;\n".
            "\tif (\$action != \"\") {\n".
            "\t\t\$".$table->getName()." = new ".ucfirst($table->getName()).";\n".
            ":issets".
            ":sets".
            "\t\tif (\$action == \"Add\") {\n".
            "\t\t\t\$control->insert".ucfirst($table->getName())."(\$".$table->getName().");\n".
            "\t\t }elseif (\$action == \"Update\") {\n".
            "\t\t\t\$control->update".ucfirst($table->getName())."(\$".$table->getName().");\n".
            "\t\t  }\n".
            "\t }\n".
            "\tif (isset(\$_POST['Delete'])) {\n".
            "\t\tdelete();\n".
            "\t }\n".
            "\t\$a".ucfirst($table->getName())." = \$control->selectAll".ucfirst($table->getName())."();\n".
            "\t\$t=\"\";\n".
            "\tif (sizeof(\$a".ucfirst($table->getName()).")>0) {\n".
            "\t\t\$t=tabela(\$a".ucfirst($table->getName()).");\n".
            "\t }\n".
            "\tfunction tabela(\$a".ucfirst($table->getName())."){\n".
            "\t\t\$t = \"\";\n".
            "\t\tforeach (\$a".ucfirst($table->getName())." as \$key => \$value) {\n".
            "\t\t\t\$t .= \"<tr>\". \n".
            ":tds".
            "\t\t\t\"<td><input type='button' onclick='".$table->getName()."_".$table->getAtributes()[0]->getName().".value=\".\$value->get".ucfirst($table->getAtributes()[0]->getName())."().\";return crud();' id='action' name='action' class='btn btn-primary' value='Update'></td>\".\n".
            "\t\t\t\"<td><input type='submit' onclick='".$table->getName()."_".$table->getAtributes()[0]->getName().".value=\".\$value->get".ucfirst($table->getAtributes()[0]->getName())."().\"' name='Delete' class='btn btn-danger' value='Delete'></td>\".\n".
            "\t\t\t\"</tr>\";\n".
            "\t\t }\n".
            "\t\treturn \$t;\n".
            "\t }\n".
            "\tfunction delete(){\n".
            "\t\t\$".$table->getName()." = new ".ucfirst($table->getName()).";\n".
            "\t\t\$".$table->getName()."->set".ucfirst($table->getAtributes()[0]->getName())."(\$_POST['".$table->getName()."_".$table->getAtributes()[0]->getName()."']);\n".
            "\t\t\$control = new Controller;\n".
            "\t\t\$control->delete".ucfirst($table->getName())."(\$".$table->getName().");\n".
            "\t }\n";
            $php = str_replace(":issets", $this->generateIssets($table), $php);
            $php = str_replace(":sets", $this->generateSets($table), $php);
            $php = str_replace(":tds", $this->generateTds($table->getAtributes()), $php);
            return $php;
        }
        private function generateThs($atributes)
        {
            $ths = "";
            foreach ($atributes as $key => $value) {
                $ths .= "\t\t\t\t\t<th>".ucfirst($value->getName())."</th>\n";
            }
            $ths .=
            "\t\t\t\t\t<th>Update</th>\n".
            "\t\t\t\t\t<th>Delete</th>\n";
            return $ths;
        }
        public function createSelectView($dir, $table)
        {
            $selectView =
            "<?php\n".
            "\trequire_once \"../autoload.php\";\n".
            "\trequire_once \"header.php\";\n".
            ":php".
            " ?>\n".
            "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js\"></script>\n".
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css\">\n".
            "<script type=\"text/javascript\" charset=\"utf8\" src=\"https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js\"></script>\n".
            "<script>\n".
            "\tfunction crud(){\n".
	    	"\t\tdocument.Form.action=\"".$table->getName()."TableView.php\";\n".
	    	"\t\tdocument.Form.submit();\n".
	    	"\t\treturn true;\n".
            "\t }\n".
            " </script>\n".
            "<div class=\"container\">\n".
            "\t<form method=\"post\" name=\"Form\">\n".
            "\t\t<legend>".ucfirst($table->getName())."</legend>\n".
            "\t\t<div class=\"form-row\">\n".
            "\t\t\t<input type=\"hidden\" id=\"".$table->getName()."_".$table->getAtributes()[0]->getName()."\" name=\"".$table->getName()."_".$table->getAtributes()[0]->getName()."\">\n".
            "\t\t\t<input type='button' onclick='return crud();' name='action' class='btn btn-primary' value='Add'>\n".
            "\t\t </div>\n".
            "\t\t<br>\n".
            "\t\t<table id=\"table\">\n".
            "\t\t\t<thead>\n".
            "\t\t\t\t<tr>\n".
            ":ths".
            "\t\t\t\t </tr>\n".
            "\t\t\t </thead>\n".
            "\t\t\t<tbody>\n".
            "\t\t\t\t<?= \$t?>\n".
            "\t\t\t </tbody>\n".
            "\t\t\t </table>\n".
            "\t </form>\n".
            " </div>\n".
            "<script>\n".
            "\t\$(document).ready( function () {\n".
            "\t\t\$('#table').DataTable();\n".
            "\t });\n".
            " </script>\n".
            "<?php require_once(\"footer.php\"); ?>";
            $selectView = str_replace(":php", $this->generatePhp($table), $selectView);
            $selectView = str_replace(":ths", $this->generateThs($table->getAtributes()), $selectView);
            $f = fopen($dir."view". DIRECTORY_SEPARATOR. $table->getName(). "SelectView.php", "w");
            fwrite($f, $selectView);
            fclose($f);
        }
     }
 ?>
