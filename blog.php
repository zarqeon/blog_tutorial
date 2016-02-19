<?php

/**
 * alkalmazás logika
 *
 * ez egy FORM amivel új blog postot lehet létrehozni (berakni az adatbázisba)
 * egy új postot nem engedünk azonnal közzétennni (lerakni a db-be), csak akkor ha előtte a user meggyőzödött róla, hogy megfelelő-e a post (előnézet)
 *
 * ez úgy történik, hogy:
 * - a user beírja a blog post szövegét és a posthoz tartozó tag-eket
 * - megnyomja az előnézet gombot
 * - mi történik?
 * - megmaradnak az input fieldek tartalmai
 * - lenyomjuk a közzéteszt
 * - lefut a processTags függvény, és a isset (közzétesz)hez kapcsolódó elágazások
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//globális változók dektlarálása

$textarea = $tags = $post_id = $id_post = false;

//begyűjtjük a POST-ból és a REQUEST-ből a változókat
	//blog post szövege
	if(isset($_POST['textarea']))
	{
		$textarea = $_POST['textarea'];
	}

	//blog posthoz tartozó tag-ek (ez egy string, a tag-ek vesszővel vannak elválasztva)
	if(isset($_POST['tags']))
	{
		$tags = $_POST['tags'];
	}

	// blogpost id-je, ami alapján behozta a blogpostot a list.php
	if(isset($_REQUEST['id']))
	{
		$id_post = $_REQUEST['id'];
	}

	
/*
 * validate
 * validál egy változót
 * akkor valid egy változó, hogy ha van benne valami (nem üres)
 *
 * @param mixed $variable a validálandó változó
 * @return boolean valid-e a változó vagy sem
 */
function validate($variable)
{
	//HA a változó nem üres
	if(!empty($variable))	
	{
		return true;
	}

	return false;	
}

/*
validate_button
két változót validál
HA MINDKÉT változóra teljesül a validate függvény, végrehajtja a kódot, tehát echózik egy új gombot a meghívás helyén.
*/

function validate_button ($textarea, $tags)	
{

	if(validate($textarea) && validate($tags))	
	{
		echo '<input type="submit" name="Közzétesz" value="Közzétesz">';	
	}	
}
//Deklarálja a $connect változót. Ez egy PDO segítségével kapcsolatot hoz létre az adatbázissal.
$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');	

/*
HA a $textarea nem üres, $repost = textarea
*/
if(!empty($textarea)) 
{

	$repost=$textarea;	
//HA az $id_post nem üres, végrehajtja az edit_post statementet, és fetchel egy oszlopot
}	else if(!empty($id_post)) {	
	$edit_post=$connect->prepare("SELECT post FROM post WHERE id=?");
	$data=array($id_post);
	$edit_post->execute($data);
	$result=$edit_post->fetchColumn();
	$repost=$result;

}

/*
hidden_input függvény
ha a változó NEM üres, echóz egy input fieldet
*/
function hidden_input ($id_post)
{
	if (!empty ($id_post))
	{
		echo "<input type='hidden' name='id' value='$id_post'>";
	}
}



/*
Ha le lett nyomva a közzétesz gomb ÉS validált két változót a validate függvény
*/
if (isset($_POST['Közzétesz']) && (validate($textarea) && validate($tags))) 
{	
	//ha a változó nem üres
	if(!empty($id_post)){	
		$stmnt = $connect->prepare("UPDATE post SET post =? WHERE id=?");
		$stmnt->execute(array($textarea, $id_post));
	}
	//ha a feltétel nem teljesült
	else{

		$statement = $connect->prepare("INSERT INTO post(post)VALUES(?)");
		$statement->execute(array($textarea));
		$post_id = $connect->lastInsertId();
	}
}	


/*
processTags függvény
felrobbantja vesszőnként a tagokat
egy foreach loopot futtat le rajtuk
	trimeli a tagokat
	kiválasztja a tagokhoz kapcsolódó id-t
	beszúrja két változó értékét a posstotag két oszlpába
	
*/

function processTags ($connect, $tags, $post_id){ 

	$exploded_tags = explode(",", $tags);	

	foreach($exploded_tags as $single_tag){	
		$single_tag = trim($single_tag);	

		$query = $connect->prepare("SELECT ID FROM tag WHERE tag = '$single_tag'");	
		$query->execute(array($single_tag));	
		$q_result = $query->fetchColumn();	
		$all_tagid = $q_result;	
		
		//HA a változó nem üres
		//végrehajtja a statementet
		if(empty($q_result)){	
			$statement = $connect->prepare("INSERT INTO tag(tag)VALUES(?)");	
			$statement->execute(array($single_tag));	
			$last_id = $connect->lastInsertId();	
			$all_tagid = $last_id;	

		}
		$statement = $connect->prepare("INSERT INTO posttotag(tag_id, post_id) VALUES(?,?)");	
		$statement->execute(array($all_tagid, $post_id));	
	}

}

/*
HA a közzétesz le lett nyomva, meghívja a függvényt
*/
if(isset($_POST['Közzétesz'])){	

	processTags($connect, $tags, $post_id);	

}



?>

<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form action="blog.php" method="post">
<textarea id="textarea" name="textarea" rows="10" placeholder="Ide írj" cols="50">
<?php echo $repost //kiechózza a $repost változó értékét, így marad meg a textarea tartalma ?>
</textarea>
<br />
<input type="text" id="tags" name="tags" value = "<?php echo $tags ?>">
<br />
<input type="submit" name="Előnézet"value="Előnézet">
<br />
<?php
validate_button($textarea, $tags); //itt hívódik meg a validate_button függvény
hidden_input($id_post); //itt hívódik meg a hidden_input függvény
?>

</form>
</body>
</html>
