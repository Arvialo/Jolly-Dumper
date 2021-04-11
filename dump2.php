<?php
$hidden1 = "";

if (isset($_POST['connection'])){
    $env['user'] = $_POST['user'];
    $env['pass'] = $_POST['pass'];
    $env['host'] = $_POST['host'];
    $env['name'] = $_POST['name'];
    try{
        $pdo = new PDO("mysql:host=".$env['host'].";dbname=".$env['name'],$env['user'],$env['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $res = "Success :)";
        $hidden1 = "hidden";
        $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    }
    catch(Exception $e){
        $res = "Fail :(";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dump database</title>
</head>
<body>
    <form action="" method="post" <?= $hidden1 ?>>
        <input type="text" name="user" value="<?= $env['user'] ?>" placeholder="username">
        <input type="text" name="pass" value="<?= $env['pass'] ?>" placeholder="password">
        <input type="text" name="name" value="<?= $env['name'] ?>" placeholder="database name">
        <input type="text" name="host" value="<?= $env['host'] ?>" placeholder="host">
        <input type="submit" name="connection" value="Connect">
        <?= res; ?>
    </form>
        <?php

foreach($tables as $table){
    $columns_name = $pdo->query('DESCRIBE '.$table)->fetchAll(PDO::FETCH_COLUMN);
    $columns_content = $pdo->query('SELECT * from '.$table)->fetchAll();
    ?>
    <table>
            <caption><?= "\n".$table."\n" ?></caption>
            <tr>
                <?php 
    
                    foreach($columns_name as $column_name){
                        ?>
                        <th scope="col"><?= $column_name ?></th>
                        <?php

                    }
                
                ?>
            
            </tr>
            
                    <?php
                        foreach($columns_content as $column_content){
                             ?>
                             <tr>
                             <?php
                            foreach($columns_name as $column_name){
                            ?>
                            <td><?= $column_content[$column_name] ?></td>
    
                            <?php
                        }
                        ?>
                        </tr>
                        <?php
                
                    }
                    
                    ?>

            </table>
       <?php     
    }
        
        ?>
</body>
<style>
td,
th {
    border: 1px solid rgb(190, 190, 190);
    padding: 10px;
}

td {
    text-align: center;
}

tr:nth-child(even) {
    background-color: #eee;
}

th[scope="col"] {
    background-color: #696969;
    color: #fff;
}

th[scope="row"] {
    background-color: #d7d9f2;
}

caption {
    padding: 10px;
    caption-side: bottom;
}

table {
    border-collapse: collapse;
    border: 2px solid rgb(200, 200, 200);
    letter-spacing: 1px;
    font-family: sans-serif;
    font-size: .8rem;
}

</style>
</html>