<?php
$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');

$query = $connect->query('SELECT * FROM post');

$query->execute();

$result = $query->fetchAll();
print_r($result);

?>