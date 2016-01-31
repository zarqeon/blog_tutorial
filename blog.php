<?php
	echo $_POST ["textarea"];
	$textarea = ($_POST["textarea"]);
	echo $_POST ["tags"];
	$tags = ($_POST["tags"]);
	$id_post = ($_GET["r"]);
	
	echo $id_post;

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
		if (validate($textarea) && validate($tags))
		{
			echo '<input type="submit" name="Közzétesz" value="Közzétesz">';		
		}	
	}
	
	$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');

	
	
	if(!empty($textarea))
	{
		
		$repost=$textarea;
		
	}	else if(!empty($id_post) && empty($textarea)) {
		$edit_post=$connect->prepare("SELECT post FROM post WHERE id=?");
		$data=array($id_post);
		$edit_post->execute($data);
		$result=$edit_post->fetchColumn();
		$repost=$result;
		
	}

	
	
	
	if (isset($_POST['Közzétesz']) && (validate($textarea) && validate($tags))) 
	{	
		$statement = $connect->prepare("INSERT INTO post(post)VALUES(?)");
		$statement->execute(array($textarea));
		echo $connect->lastInsertId();
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
?>
</form>
</body>
</html>