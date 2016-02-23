<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');
$query = $connect->query('SELECT * FROM post');
$result = $query->fetchAll();
        
foreach($result as $id ) {
    
    //ez a statemenet számolná meg, hogy hány tag kapcsolódik az adott posthoz
    $count_tag = $connect->query('SELECT COUNT(post_id) FROM posttotag WHERE post_id=98');
    $number = $count_tag->fetchAll();//valahogy el kéne íréni hogy "$result['id']" -re hajtsa végre

    $echo_tags = $connect->query('SELECT tag.tag, posttotag.tag_id FROM tag INNER JOIN posttotag ON tag.ID=posttotag.tag_id WHERE post_id=98');
    $tag_list = $echo_tags->fetchAll();

    var_dump($number);
    
    echo '<a href="blog.php?id='.$id['id'].'">', $id['id'], '</a>';
    echo $id['post'];  
    echo '(',$number, ')';
    echo $tag_list, '<br />';
}


?>