<?php

class Database_operations {

    public function __construct()
    {
      // require('db.php');
    }

    public function insert_into_table($year, $day){
        require('db.php');
        $sql = "INSERT INTO test_table (year,day) VALUES ('".$year."', '".$day."')";
        $conn->query($sql);
    }

    public function select_from_table($year, $day){
        require('db.php');
        return $sql2 = "SELECT ".$year.", ".$day." FROM test_table";
    }
    
}

?>