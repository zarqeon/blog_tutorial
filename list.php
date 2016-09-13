<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require_once('Post.php');

Post::PostList();
$returned_objects_array = Post::getAllPosts();

foreach ($returned_objects_array as $separated_objects)
	{
		$list_id = $separated_objects->id;
		
		echo '<a href="blog.php?id='.$list_id.'">', $list_id, '</a>';
		
		$p_text = $separated_objects->text['post'];
		
		echo "$p_text, <br />";
	}
?>
