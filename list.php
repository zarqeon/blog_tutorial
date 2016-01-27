<?php
$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');

$query = $connect->query('SELECT * FROM post');

$result = $query->fetchAll();

foreach($result as $r) {
    echo $r['post'];
    echo '<a href="blog.php?r='.$r['id'].'>', $r['id'], '<br />', '</a>';
}


?>