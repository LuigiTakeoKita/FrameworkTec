<?php
    class BOBuilder
    {
        public function createBO($dir, $table)
        {
            $bo = 
            "<?php\n".
            "   class ".$table->getName()."BO".
            "   {".
            "       private \$dao;\n".
            "       public function __construct(\$dao){".
            "           \$this->dao = \$dao;".
            "       }\n".
            "       public function insert(".$table->getName()." = null)\n".
            "       {\n".
            "           \$dao->insert(".$table->getName().");".
            "       }".
            "       public function update(".$table->getName()." = null)\n".
            "       {\n".
            "           \$dao->update(".$table->getName().");".
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
            "?>";
            $fp = fopen($dir.'bo'.DIRECTORY_SEPARATOR.ucfirst($table->getName()).'BO.php', 'w');
            fwrite($fp, $bo);
            fclose($fp);
         }
     }
 ?>