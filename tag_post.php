<?php




if(isset($_REQUEST['tag']))
{
    $url_tag = $_REQUEST['tag'];
}

$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');
//kiválasztja melyik post id-hez kapcsoltuk az adott tag id-jét
$select_post = $connect->prepare('SELECT post_id FROM posttotag WHERE tag_id=?');
$select_post->execute(array($url_tag));
$selected_id = $select_post->fetchAll();

foreach($selected_id as $single_id)
{   
    
    $only_id=$single_id['post_id'];
    
    //a statement kiválasztja a post szöveget a post id-je alapján
    $select_text = $connect->prepare('SELECT post_text FROM post WHERE id=?');
    $select_text->execute(array($only_id));
    $selected_text = $select_text->fetchColumn();
    
    //nemtudom hogy ezt lehet e egyszerűbb statementtel csinálni, vagy csak úgy mint a list.php-ban
    $connecting_tags = $connect->prepare('SELECT tag.tag_name, posttotag.tag_id FROM tag INNER JOIN posttotag ON tag.id=posttotag.tag_id WHERE post_id=?');
    $connecting_tags->execute(array($only_id));
    $connecting = $connecting_tags->fetchAll(); 
    
    echo $only_id, ' ';
    echo $selected_text, ' ';
    echo $connecting['tag_name'];
    
    foreach($connecting as $name_only)
    {
        echo $name_only['tag_name'], ', ';
    }
    
    echo '<br />';
}

?>