<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');
$query = $connect->query('SELECT * FROM post');
$result = $query->fetchAll();
        
foreach($result as $id ) {
    
    //ez a statemenet számolná meg, hogy hány tag kapcsolódik az adott posthoz
    $count_tag = $connect->query('SELECT COUNT(post_id) FROM posttotag WHERE post_id=?');
    $number = $count_tag->fetchAll();//valahogy el kéne íréni hogy "$result['id']" -re hajtsa végre

    //ez a statement választaná ki, a post által használt tagok id-jét, ha a post_id egyenlő lenne a $numberral
    $list_tag = $connect->query('SELECT tag_id FROM posttotag WHERE post_id=?');
    $listed = $list_tag->fetchAll();
    
    //ez a statement adná meg az id- alapján a tagokat magukat amik a posthoz kapcsolódnak, ha az id egyenlő lenne a $listed értékével
    $echo_tags = $connect->query('SELECT tag FROM tag WHERE id=?');
    $relisted = $echo_tags->fetchAll();

    
    
    
    echo '<a href="blog.php?id='.$id['id'].'">', $id['id'], '</a>';
    echo $id['post'];  
    echo '(',$number, ')';
    echo $relisted, '<br />';
}


?>