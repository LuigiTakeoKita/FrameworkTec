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
                "', \$".$value->getName().", PDO::PARAM_".$value->getType().");\n";
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
            "\t\tpublic function insert(\$".$table->getName()."){\n".
            "\t\t\tif (\$".$table->getName()." instanceof ".ucfirst($table->getName()).") {\n".
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
            "\t\t\tif (\$".$table->getName()." instanceof ".ucfirst($table->getName()).") {\n".
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
            "\t\t\tif (\$".$table->getName()." instanceof ".ucfirst($table->getName()).") {\n".
            "\t\t\t\t\$stmt = Conection::getInstance()->prepare('DELETE FROM ".$table->getName()." WHERE :where');\n".
            ":binds".
            ":convertions".
            "\t\t\t\t\$stmt->execute();\n".
            "\t\t\t }\n".
            "\t\t }\n";
            $atributes = $table->getAtributes();
            $id = $atributes[0];
            $delete = str_replace(":where", $this->generateWhere($id), $delete);
            $delete = str_replace(":binds", $this->generateBinds([$id]), $delete);
            $table->setAtributes([$id]);
            $delete = str_replace(":convertions", $this->generateConvertions($table), $delete);
            $table->setAtributes($atributes);
            return $delete;
         }
        private function generateSets($table, $ntabs)
        {
            $tabs="";
            for ($i=0; $i < $ntabs; $i++) { 
                $tabs .= "\t";
            }
            $sets = "";
            $name = $table->getName();
            foreach ($table->getAtributes() as $key => $value) {
                if ($value->getForeignKey()=="") {
                    $sets .= $tabs."\$".$name."->set".ucfirst($value->getName())."(\$row['".$value->getName()."']);\n";
                 } else {
                    $sets .= 
                    $tabs."\$".$value->getForeignKey()." = new ".ucfirst($value->getForeignKey()).";\n".
                    $tabs."\$".$value->getForeignKey()."->set".$value->getReference()."(\$row['".$value->getName()."']);\n".
                    $tabs."\$".$value->getForeignKey()." = \$".$value->getForeignKey()."dao->selectPK(\$".$value->getForeignKey().");\n".
                    $tabs."\$".$name."->set".ucfirst($value->getForeignKey())."(\$".$value->getForeignKey().");\n";
                  }
                
            }   
            return $sets;
         }
        private function generateSelectAll($table)
        {
            $selectAll =
            "\t\tpublic function selectAll(){\n".
            "\t\t\t\$query = Conection::getInstance()->query(\"SELECT * FROM ".$table->getName().";\");\n".
            "\t\t\t\$arr=array();\n".
            ":dao".
			"\t\t\twhile (\$row = \$query->fetch(PDO::FETCH_ASSOC)) {\n".
			"\t\t\t\t\$".$table->getName()." = new ".ucfirst($table->getName())."();\n".
			":sets".
			"\t\t\t\tarray_push(\$arr, \$".$table->getName().");\n".
			"\t\t\t }\n".
            "\t\t\treturn \$arr;\n".
            "\t\t }\n";
            $dao = "";
            foreach ($table->getAtributes() as $key => $value) {
                if($value->getForeignKey() != ""){
                    $dao .= "\t\t\t\$".$value->getForeignKey()."dao = new ".ucfirst($value->getForeignKey())."DAO;\n";
                }
            }
            $selectAll = str_replace(":dao", $dao, $selectAll);
            $selectAll = str_replace(":sets", $this->generateSets($table, 4), $selectAll);
            return $selectAll;
         }
        private function generateSelectPK($table)
        {
            $selectpk = 
            "\t\tpublic function selectPK(\$".$table->getName()."){\n".
            "\t\t\tif (\$".$table->getName()." instanceof ".ucfirst($table->getName()).") {\n".
            "\t\t\t\t\$stmt = Conection::getInstance()->prepare(\"SELECT * FROM ".$table->getName()." WHERE :where;\");\n".
            ":binds".
            ":convertions".
            "\t\t\t\t\$stmt->execute();\n".
            "\t\t\t\t\$row = \$stmt->fetch(PDO::FETCH_ASSOC);\n".
            "\t\t\t\tif (\$row != false) {\n".
            "\t\t\t\t\t\$".$table->getName()." = new ".ucfirst($table->getName())."();\n".
            ":sets".
            "\t\t\t\t\treturn \$".$table->getName().";\n".
            "\t\t\t\t }\n".
            "\t\t\t }\n".
            "\t\t\treturn null;\n".
            "\t\t }\n";
            $atributes = $table->getAtributes();
            $id = $atributes[0];
            $selectpk = str_replace(":sets", $this->generateSets($table, 5), $selectpk);
            $selectpk = str_replace(":where", $this->generateWhere($id), $selectpk);
            $selectpk = str_replace(":binds", $this->generateBinds([$id]), $selectpk);
            $table->setAtributes([$id]);
            $selectpk = str_replace(":convertions", $this->generateConvertions($table), $selectpk);
            $table->setAtributes($atributes);
            return $selectpk;
         }
        public function createDAO($dir, $table)
        {
            $dao = 
            "<?php\n".
            "\trequire_once \"../autoload.php\";\n".
            "\tclass ".ucfirst($table->getName())."DAO implements TableInterface {\n".
            ":insert".
            ":update".
            ":delete".
            ":selectAll".
            ":selectPK".
            "\t }\n".
            " ?>";
            $dao = str_replace(":insert", $this->generateInsert($table), $dao);
            $dao = str_replace(":update", $this->generateUpdate($table), $dao);
            $dao = str_replace(":delete", $this->generateDelete($table), $dao);
            $dao = str_replace(":selectAll", $this->generateSelectAll($table), $dao);
            $dao = str_replace(":selectPK", $this->generateSelectPK($table), $dao);
            $fp = fopen($dir.'dao'.DIRECTORY_SEPARATOR.ucfirst($table->getName()).'DAO.php', 'w');
            fwrite($fp, $dao);
            fclose($fp);
         }
     }
 ?>