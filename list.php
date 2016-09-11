<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require_once('Post.php');

Post::PostList();
$returned_objects_array = Post::getAllPosts();

var_dump ($returned_objects_array); //jelenleg array-ban tér vissza

?>
