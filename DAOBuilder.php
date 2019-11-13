<?php
    class DAOBuilder
    {
        private function generateFields($atributes)
        {
            $fields="";
            for ($i = 0; $i < count($atributes); $i++) {
                $fields .= $atributes[$i]->getName();
                if ($i != count($atributes) - 1) $fields .= ", ";
             }
            return $fields;
         }
        private function generateValues($atributes)
        {
            $fields="";
            for ($i = 0; $i < count($atributes); $i++) {
                $fields .= ":".$atributes[$i]->getName();
                if ($i != count($atributes) - 1) $fields .= ", ";
             }
            return $fields;
         }
        private function generateBinds($atributes)
        {
            $binds = "";
            foreach ($atributes as $key => $value) {
                $binds .= "\t\t\t\t\$stmt->bindParam(':".$value->getName().
                "', \$".$value->getName().", PDO::PARAM_".strtoupper($value->getType()).");\n";
             }
            return $binds;
         }
        private function generateConvertions($table)
        {
            $convertions = "";
            foreach ($table->getAtributes() as $key => $value) {
                if ($value->getForeignKey()=="") {
                    $convertions .= "\t\t\t\t\$".$value->getName().
                    " = \$".$table->getName()."->get".ucfirst($value->getName())."();\n";
                 } else {
                    $convertions .= "\t\t\t\t\$".$value->getName().
                    " = \$".$table->getName()."->get".ucfirst($value->getForeignKey())."()->get".ucfirst($value->getReference())."();\n";
                  }
             }
            return $convertions;
         }
        
        private function generateInsert($table)
        {
            $insert =
            "\t\tpublic function inserir(\$".$table->getName()."){\n".
            "\t\t\tif (\$inst instanceof ".$table->getName().") {\n".
            "\t\t\t\t\$stmt = Conection::getInstance()->prepare('INSERT INTO ".$table->getName()." (:fields) VALUES(:values)');\n".
            ":binds".
            ":convertions".
            "\t\t\t\t\$stmt->execute();\n".
            "\t\t\t }\n".
            "\t\t }\n";
            $atributes = $table->getAtributes();
            unset($atributes[0]);
            $atributes = array_values($atributes);
            $insert = str_replace(":fields", $this->generateFields($atributes), $insert);
            $insert = str_replace(":values", $this->generateValues($atributes), $insert);
            $insert = str_replace(":binds", $this->generateBinds($atributes), $insert);
            $insert = str_replace(":convertions", $this->generateConvertions($table), $insert);
            return $insert;
         }
        private function generateUpdates($atributes)
        {
            $updates="";
            for ($i = 0; $i < count($atributes); $i++) {
                $updates .= $atributes[$i]->getName()." = :".$atributes[$i]->getName();
                if ($i != count($atributes) - 1) $updates .= ", ";
             }
            return $updates;
         }
        private function generateWhere($id)
        {
            return $id->getName()." = :".$id->getName();
         }
        private function generateUpdate($table)
        {
            $update =
            "\t\tpublic function update(\$".$table->getName()."){\n".
            "\t\t\tif (\$inst instanceof ".$table->getName().") {\n".
            "\t\t\t\t\$stmt = Conection::getInstance()->prepare('UPDATE ".$table->getName()." SET :updates WHERE :where');\n".
            ":binds".
            ":convertions".
            "\t\t\t\t\$stmt->execute();\n".
            "\t\t\t }\n".
            "\t\t }\n";
            $atributes = $table->getAtributes();
            $update = str_replace(":binds", $this->generateBinds($atributes), $update);
            $update = str_replace(":convertions", $this->generateConvertions($table), $update);
            $id = $atributes[0];
            unset($atributes[0]);
            $atributes = array_values($atributes);
            $update = str_replace(":updates", $this->generateUpdates($atributes), $update);
            $update = str_replace(":where", $this->generateWhere($id), $update);
            return $update;
         }
        private function generateDelete($table)
        {
            $delete = 
            "\t\tpublic function delete(\$".$table->getName()."){\n".
            "\t\t\tif (\$inst instanceof ".$table->getName().") {\n".
            "\t\t\t\t\$stmt = Conection::getInstance()->prepare('DELETE FROM ".$table->getName()." WHERE :idold = ::idnewm');\n".
            ":binds".
            ":convertions".
            "\t\t\t\t\$stmt->execute();\n".
            "\t\t\t }\n".
            "\t\t }\n";
            $atributes = $table->getAtributes();
            $id = $atributes[0];
            $delete = str_replace(":idold", $id->getName(), $delete);
            $delete = str_replace(":idnewm", $id->getName(), $delete);
            $delete = str_replace(":binds", $this->generateBinds([$id]), $delete);
            $table->setAtributes([$id]);
            $delete = str_replace(":convertions", $this->generateConvertions($table), $delete);
            return $delete;
         }
        private function generateSets($table)
        {
            $sets = "";
            $name = $table->getName();
            foreach ($table->getAtributes() as $key => $value) {
                $sets .= "\t\t\t\t\$".$name."->set".ucfirst($value->getName())."(\$row['".$value->getName()."']);\n";
            }   
            return $sets;
         }
        private function generateSelectAll($table)
        {
            $selectAll =
            "\t\tpublic function selectAll(){\n".
            "\t\t\t\$query = Conection::getInstance()->query(\"SELECT * FROM ".$table->getName().";\");\n".
			"\t\t\t\$arr=array();\n".
			"\t\t\twhile (\$row = \$query->fetch(PDO::FETCH_ASSOC)) {\n".
			"\t\t\t\t\$".$table->getName()." = new ".ucfirst($table->getName())."();\n".
			":sets".
			"\t\t\t\tarray_push(\$arr, \$".$table->getName().");\n".
			"\t\t\t }\n".
            "\t\t\treturn \$arr;\n".
            "\t\t }\n";
            $selectAll = str_replace(":sets", $this->generateSets($table), $selectAll);
            return $selectAll;
         }
        public function createDAO($dir, $table)
        {
            $dao = 
            "<?php\n".
            "\trequire_once \"..".DIRECTORY_SEPARATOR."conection".DIRECTORY_SEPARATOR."Conection.php\";\n".
            "\tclass ".ucfirst($table->getName())."DAO {\n".
            ":insert".
            ":update".
            ":delete".
            ":selectAll".
            "\t }\n".
            "?>";
            $dao = str_replace(":insert", $this->generateInsert($table), $dao);
            $dao = str_replace(":update", $this->generateUpdate($table), $dao);
            $dao = str_replace(":delete", $this->generateDelete($table), $dao);
            $dao = str_replace(":selectAll", $this->generateSelectAll($table), $dao);
            $fp = fopen($dir.'dao'.DIRECTORY_SEPARATOR.ucfirst($table->getName()).'DAO.php', 'w');
            fwrite($fp, $dao);
            fclose($fp);
         }
     }
 ?>