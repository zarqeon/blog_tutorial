<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');

$query = $connect->query('SELECT * FROM post');

$result = $query->fetchAll();


foreach($result as $id ) {
    echo $id['post'];
    echo '<a href="blog.php?id='.$id['id'].'">', $id['id'], '<br />', '</a>';
}


?>