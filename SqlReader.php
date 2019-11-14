<?php
    require_once "Atribute.php";
    require_once "SQLTable.php";
    class SQLReader
    {
        public function read($json=""){
            if($json=="" or empty($json)){
                return [];
             }
            $tables=[];
            foreach ($json as $key => $value) {
                $table = new SQLTable;
                $table->setName(strtolower($key));
                foreach ($value as $key2 => $value2) {
                    $arr = array_keys($value2);
                    $atr = new Atribute;
                    $atr->setName(strtolower($arr[0]));
                    $atr->setType(strtoupper($value2[$arr[0]]));
                    if(sizeof($arr)>1){
                        $atr->setReference(strtolower($arr[1]));
                        $atr->setForeignKey(strtolower($value2[$arr[1]]));
                    }
                    $table->addAtribute($atr);
                 }
                array_push($tables, $table);
             }
            return $tables;
         }
     }
 ?>
 <!-- 
     ao pegar os valores da tabela colocar tudo em minusculo
    {
        "tabela":[
            {
                "id":"int"
            },
            {
                "tabela2_id":"INT",
                "id":"tabela2"
            },
            {
                "nome":"str"
            }
        ],
        "tabela2":[
            {
                "id":"int"
            },
            {
                "nome":"str"
            }
        ]
    }
  -->