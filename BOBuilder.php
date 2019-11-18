<?php
    class BOBuilder
    {
        public function createBO($dir, $table)
        {
            $bo = 
            "<?php\n".
            "\trequire_once \"../autoload.php\";\n".
            "\tclass ".ucfirst($table->getName())."BO implements TableInterface {\n".
            "\t\tprivate \$dao;\n".
            "\t\tpublic function __construct() {\n".
            "\t\t\t\$this->dao = ".ucfirst($table->getName())."DAO;\n".
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