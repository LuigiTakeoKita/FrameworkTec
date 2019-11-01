<?php
    class BOBuilder
    {
        public function createBO($dir)
        {
            $bo = 
            "<?php\n".
            "   class ".$this->getName()."BO".
            "   {".
            "       private \$dao;\n".
            "       public function __construct(\$dao){".
            "           \$this->dao = \$dao;".
            "       }\n".
            "       public function insert(".strtolower($this->getName())." = null)\n".
            "       {\n".
            "           \$dao->insert(".strtolower($this->getName()).");".
            "       }".
            "       public function update(".strtolower($this->getName())." = null)\n".
            "       {\n".
            "           \$dao->update(".strtolower($this->getName()).");".
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
            "?>";;
            $fp = fopen($dir.'bo'.DIRECTORY_SEPARATOR.$this->getName().'BO.php', 'w');
            fwrite($fp, $bo);
            fclose($fp);
         }
     }
 ?>