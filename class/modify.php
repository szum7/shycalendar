<?php

class modify {
    
    public function __construct() {
        
    }
    
    public function modifyAll($connection_id, $originalMysql, $mysql, $date){
        
        $originalMysql = stringMysqlToArray($originalMysql, $date);
        $mysql = stringMysqlToArray($mysql, $date);
        
        $deletableLine = kulombseg($originalMysql, $mysql);
        $newLine = kulombseg($mysql, $originalMysql);        
        
        $this->deleteLines($connection_id, $deletableLine);        
        $this->insertNewLines($connection_id, $newLine);
    }
    
    private function deleteLines($connection_id, $deletableLine){
        if(count($deletableLine) > 0){
            $query = "
                DELETE FROM events 
                WHERE (date, intro, content) IN (";
            for($i = 0; $i < count($deletableLine); $i++){
                if($i != 0){
                    $query .= ",";
                }
                $query .= "('" . $deletableLine[$i]["date"] . "','";
                $query .= $deletableLine[$i]["intro"] . "','";
                $query .= $deletableLine[$i]["content"] . "')";
            }
            $query .= ");";
            $result = mysqli_query($connection_id, $query) or die("BAD QUERY - " . $query);
        }
    }
    
    private function insertNewLines($connection_id, $newLine){
        if(count($newLine) > 0){
            $query = "INSERT INTO events (date, intro, content) VALUES ";
            for($i = 0; $i < count($newLine); $i++){
                if($i != 0){
                    $query .= ",";
                }
                $query .= "('" . $newLine[$i]["date"] . "','";
                $query .= $newLine[$i]["intro"] . "','";
                $query .= $newLine[$i]["content"] . "')";
            }
            $query .= ";";
            $result = mysqli_query($connection_id, $query) or die("BAD QUERY - " . $query);
        }
    }
    
    
    
    /* alma, körte, barack
     * alma, körte
     * 
     * (1 - 2): barack
     *      - delete barack
     * 
     * alma, körte
     * alma, körte, barack
     * 
     * (2 - 1): barack
     *      insert barack
     * 
     */
    
}

function stringMysqlToArray($str, $date){
        
    $arr = explode("*", $str);
    $ret = array();
    for($i = 0; $i < count($arr) - 1; $i++){
        $exp = explode(";", $arr[$i]);
        array_push($ret, array(
            "date" => $date . " " . $exp[0],
            "intro" => $exp[1],
            "content" => $exp[2]
        ));
    }

    return $ret;
}

function kulombseg($arr1, $arr2){
    // arr1 - arr2 = arr3 := new lines
    if(count($arr2) == 0){
        return $arr1;
    }
    $arr3 = array();
    for($i = 0; $i < count($arr1); $i++){
        $j = 0;
        while($j < count($arr2) && 
                !($arr1[$i]["date"] == $arr2[$j]["date"] && 
                $arr1[$i]["intro"] == $arr2[$j]["intro"] && 
                $arr1[$i]["content"] == $arr2[$j]["content"])){
            $j++;
        }
        if($j == count($arr2)){
            array_push($arr3, $arr1[$i]);
        }
    }
    return $arr3;
}

?>