<?php
	echo $_POST ["textarea"];
	$textarea = ($_POST["textarea"]);
	echo $_POST ["tags"];
	$tags = ($POST["tags"]);
	
	if (validate($textarea) && validate($tags)){
		echo '<input type="submit" name="Közzétesz" value="Közzétesz">';		
	}

	function validate($variable)
	{
		if(empty($variable))
		{
			return true;
		}

		return $false;
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
<input type="text" id="tags" name="tags">
<br />
<input type="submit" name="Előnézet"value="Előnézet">
</form>
</body>
</html>
