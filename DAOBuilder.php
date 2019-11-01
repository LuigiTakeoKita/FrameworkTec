<?php
    class DAOBuilder
    {
        public function generateInsert()
        {
            return "";
         }
        public function generateUpdate()
        {
            return "";
         }
        public function generateDelete()
        {
            return "";
         }
        public function generateSelectAll()
        {
            return "";
         }
        public function generateSelects()
        {
            return "";
         }
        public function createDAO($dir, $table)
        {
            $dao = 
            "<?php\n".
            "   class ".$table->getName()."DAO".
            "   {".
            ":insert".
            ":update".
            ":delete".
            ":selectAll".
            ":selects".
            "   }\n".
            "?>";
            $dao = str_replace(":insert", $this->generateInsert($table), $dao);
            $dao = str_replace(":insert", $this->generateUpdate($table), $dao);
            $dao = str_replace(":insert", $this->generateDelete($table), $dao);
            $dao = str_replace(":insert", $this->generateSelectAll($table), $dao);
            $dao = str_replace(":insert", $this->generateSelects($table), $dao);
            $fp = fopen($dir.'dao'.DIRECTORY_SEPARATOR.$table->getName().'DAO.php', 'w');
            fwrite($fp, $dao);
            fclose($fp);
         }
     }
 ?>