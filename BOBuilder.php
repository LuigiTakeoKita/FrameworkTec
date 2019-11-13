<?php
    class BOBuilder
    {
        public function createBO($dir, $table)
        {
            $bo = 
            "<?php\n".
            "\tclass ".$table->getName()."BO {\n".
            "\t\tprivate \$dao;\n".
            "\t\tpublic function __construct(\$dao) {\n".
            "\t\t\t\$this->dao = \$dao;\n".
            "\t\t }\n".
            "\t\tpublic function insert(\$".$table->getName()." = null) {\n".
            "\t\t\t\$dao->insert(\$".$table->getName().");\n".
            "\t\t }\n".
            "\t\tpublic function update(\$".$table->getName()." = null) {\n".
            "\t\t\t\$dao->update(\$".$table->getName().");\n".
            "\t\t }\n".
            "\t\tpublic function delete(\$".$table->getName()." = null) {\n".
            "\t\t\t\$dao->delete(\$".$table->getName().");\n".
            "\t\t }\n".
            "\t\tpublic function selectAll() {\n".
            "\t\t\treturn \$dao->selectAll();\n".
            "\t\t }\n".
            "\t }\n".
            " ?>";
            $fp = fopen($dir.'bo'.DIRECTORY_SEPARATOR.ucfirst($table->getName()).'BO.php', 'w');
            fwrite($fp, $bo);
            fclose($fp);
         }
     }
 ?>