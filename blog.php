<?php
	echo $_POST ["textarea"];
	$textarea = ($_POST["textarea"]);
	echo $_POST ["tags"];
	$tags = ($_POST["tags"]);

	function validate($variable)
	{
		if(!empty($variable))
		{
			return true;
		}

		return false;
	}

	
	if (isset($_POST['Közzétesz'])) {
	
	$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');
	
	$statement = $connect->prepare("INSERT INTO post(post)
    VALUES(?)");

	$statement->execute(array("$textarea"));
	
	}

	
?>

<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form action="blog.php" method="post">
<textarea id="textarea" name="textarea" rows="10" placeholder="Ide írj" cols="50">
<?php echo $textarea;?>
</textarea>
<br />
<input type="text" id="tags" name="tags" value = "<?php echo $tags ?>">
<br />
<input type="submit" name="Előnézet"value="Előnézet">
<br />
<?php
	if (validate($textarea) && validate($tags)){
		echo '<input type="submit" name="Közzétesz" value="Közzétesz">';		
	}
?>
</form>
</body>
</html>