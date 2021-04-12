<?php

$pass = "password";
$user= "tamanoir";
$host = "localhost";
$name= "arthur";

$pdo = new PDO("mysql:host=".$host.";dbname=".$name,$user,$pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$databases = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_ASSOC);
$dbBanned = array('information_schema','mysql','sys','performance_schema');


foreach($databases as $database){
    if (!in_array($database['Database'],$dbBanned)){
        echo "<h1>".$database['Database']."</h1>";
        $tables = $pdo->query('SHOW TABLES from '.$database['Database'])->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tables as $table){
            $t = $table["Tables_in_".$database['Database']];
            echo '<br><h3>'.$t.'</h3>';
            $columns = $pdo->query("SELECT * from ".$database['Database'].".".$t)->fetchAll(PDO::FETCH_ASSOC);
            $fields = $pdo->query('DESCRIBE '.$database['Database'].'.'.$t)->fetchAll(PDO::FETCH_ASSOC);
            $co = [];
            foreach ($fields as $field){
                array_push($co,$field['Field']);
            }
            
            foreach ($columns as $column){
                foreach ($co as $c){
                    echo $column[$c]."<br>";
                }                
            }
        }
    }
    
    
}
?>
<html>
<div id="output">

</div>

</html>