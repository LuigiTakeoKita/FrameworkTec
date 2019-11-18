<?php
    class InterfaceBuilder
    {
        public function createInteface($dir)
        {
            $interface =
            "<?php\n".
            "\tinterface TableInterface{\n".
            "\t\tpublic function insert(\$table);\n".
            "\t\tpublic function update(\$table);\n".
            "\t\tpublic function delete(\$table);\n".
            "\t\tpublic function selectAll();\n".
            "\t\tpublic function SelectPK(\$table);\n".
            "\t }\n".
            " ?>";
            $fp = fopen($dir.'interface'.DIRECTORY_SEPARATOR.'TableInterface.php', 'w');
            fwrite($fp, $interface);
            fclose($fp);
        }    
     }
 ?>