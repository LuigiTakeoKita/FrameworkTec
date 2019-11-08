<?php
    class DAOBuilder
    {
        private function generateBinds($table)
        {
            $binds = "";
            foreach ($table->getAtributes() as $key => $value) {
                $binds .= "       \$stmt->bindParam(':".$value->getName()."', \$".$value->getName().", PDO::PARAM_".strtoupper($valu->getType()).");\n";
            }
            return $binds;
         }
        private function generateConvertions($table)
        {
            $convertions = "";
            foreach ($table->getAtributes() as $key => $value) {
                if ($value->getForeignKey()=="") {
                    $convertions .= "       \$".$value->getName()." = \$".strtolower($tabel->getName())."->get".ucfirst($tabel->getName())."();\n";
                } else {
                    $convertions .= "       \$".$value->getName()." = \$".strtolower($tabel->getName())."->get".ucfirst($tabel->getName())."()->get".ucfirst($value->getReference())."();\n";
                }
            }
            return $convertions;
         }
        private function generateInsert($table)
        {
            $insert =
            "public function inserir(\$".strtolower($table->getName())."){\n".
            "   if (\$inst instanceof ".$table->getName().") {\n".
            "   \$stmt = \$Conection->getInstance()->prepare('INSERT INTO ".$table->getName()." (:fields) VALUES(:values)');\n".
            ":binds".
            ":convertions".
            "       \$stmt->execute();\n".
            "   }\n".
            "}\n";
            $insert = str_replace(":binds", $this->generateBinds($table), $insert);
            $insert = str_replace(":convertions", $this->generateConvertions($table), $insert);
            return $insert;
         }
        private function generateUpdate($table)
        {
            return "";
         }
        private function generateDelete($table)
        {
            return "";
         }
        private function generateSelectAll($table)
        {
            return "";
         }
        private function generateSelects($table)
        {
            return "";
         }
        public function createDAO($dir, $table)
        {
            $dao = 
            "<?php\n".
            "   class ".ucfirst($table->getName())."DAO".
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
            $fp = fopen($dir.'dao'.DIRECTORY_SEPARATOR.ucfirst($table->getName()).'DAO.php', 'w');
            fwrite($fp, $dao);
            fclose($fp);
         }
     }
 ?>