<?php

	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);
	
	echo $_POST ["textarea"];
	$textarea = ($_POST["textarea"]);
	echo $_POST ["tags"];
	$tags = ($_POST["tags"]);
	$id_post = ($_REQUEST['id']);
	
	
	function validate($variable)
	{
		if(!empty($variable))
		{
			return true;
		}

		return false;
	}

	function validate_button ($textarea, $tags)
	{
		if(validate($textarea) && validate($tags))
		{
			echo '<input type="submit" name="Közzétesz" value="Közzétesz">';		
		}	
	}
	
	$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');

	
	
	if(!empty($textarea))
	{
		
		$repost=$textarea;
		
	}	else if(!empty($id_post)) {
		$edit_post=$connect->prepare("SELECT post FROM post WHERE id=?");
		$data=array($id_post);
		$edit_post->execute($data);
		$result=$edit_post->fetchColumn();
		$repost=$result;
		
	}


	
	function hidden_input ($id_post)
	{
		if (!empty ($id_post))
			{
				echo "<input type='hidden' name='id' value='$id_post'>";
			}
	}
	


	
	if (isset($_POST['Közzétesz']) && (validate($textarea) && validate($tags))) 
	{	
		if(!empty($id_post)){
		$stmnt = $connect->prepare("UPDATE post SET post =? WHERE id=?");
		$stmnt->execute(array($textarea, $id_post));
		}
		
		else{
			
			$statement = $connect->prepare("INSERT INTO post(post)VALUES(?)");
			$statement->execute(array($textarea));
			echo $connect->lastInsertId();
		}
	}	
	
	
	
	
	
	function processTags ($connect, $tags){
		
		//$statement = $connect->prepare("INSERT INTO tag(tag)VALUES(?)");
		//$statement->execute(array($tags));
		
		$exploded_tags = explode(",", $tags);
		
		foreach($exploded_tags as $single_tag){
		
		$dot .= '"'. trim($single_tag) . '",';
		
		}
		
		$trimmed = rtrim ($dot, ",");
		
		$query = $connect->prepare("SELECT ID FROM tag WHERE tag IN($trimmed)");

		$query->execute(array($trimmed));
		
		$q_result = $query->fetchAll();
		
		var_dump($q_result);
		
	}

	if(isset($_POST['Előnézet'])){
		
		processTags($connect, $tags);

		
	}

	
?>

<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form action="blog.php" method="post">
<textarea id="textarea" name="textarea" rows="10" placeholder="Ide írj" cols="50">
<?php echo $repost?>
</textarea>
<br />
<input type="text" id="tags" name="tags" value = "<?php echo $tags ?>">
<br />
<input type="submit" name="Előnézet"value="Előnézet">
<br />
<?php
	validate_button($textarea, $tags); 
	hidden_input($id_post);
?>

</form>
</body>
</html>