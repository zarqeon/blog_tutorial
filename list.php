<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz');
$query = $connect->query('SELECT * FROM post');
$result = $query->fetchAll();


        
foreach($result as $id ) {

    //ez a statemenet számolná meg, hogy hány tag kapcsolódik az adott posthoz
    $count_tag = $connect->prepare('SELECT COUNT(post_id) FROM posttotag WHERE post_id=?');
    $count_tag->execute(array($id['id']));
    $number = $count_tag->fetchColumn();//valahogy el kéne íréni hogy "$result['id']" -re hajtsa végre

    
    $echo_tags = $connect->prepare('SELECT tag.tag_name, posttotag.tag_id FROM tag INNER JOIN posttotag ON tag.id=posttotag.tag_id WHERE post_id=?');
    $echo_tags->execute(array($id['id']));
    $tag_list = $echo_tags->fetchAll(); 
    
    
    
    echo '<a href="blog.php?id='.$id['id'].'">', $id['id'], '</a>';
    echo $id['post_text'];  
    echo '(', $number, ')';    
    
   
    
    foreach($tag_list as $name)
    {   
        echo '<a href="tag_post.php?tag='.$name['tag_id'].'">', $name['tag_name'], '</a>', ', ';
    }
    
    echo '<br />';   
}


?>
