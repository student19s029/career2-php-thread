<?php

$dsn = 'mysql:dbname=school;host=localhost';
$user = 'root';
$password = 'password';

try{
    $dbh = new PDO($dsn, $user, $password);

    $sql = 'select * from students';
    foreach ($dbh->query($sql) as $row) {
        echo $row['name'].'さんは'.$row['club'].'所属です。<br />';
    }
}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}

$dbh = null;
