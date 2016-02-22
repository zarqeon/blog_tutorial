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
*/

function validate_button ($textarea, $tags)	
{
        //Ha a $textarára, és a $tags-ra teljesül a validate függvény, végrehajtja a kódot, tehát echózik egy új gombot a meghívás helyén.
	if(validate($textarea) && validate($tags))	
	{
		echo '<input type="submit" name="Közzétesz" value="Közzétesz">';	
	}	
}


/*
processTags függvény	
*/

function processTags ($connect, $tags, $post_id){ 
        //szétválasztja vesszőnként a tag mezőbe beírt tagokat
	$exploded_tags = explode(",", $tags);	
        //lefuttat egy ciklust minden különválasztott tagra
	foreach($exploded_tags as $single_tag){	
                //leveszi a szóközöket a tagok mindkét végéről
		$single_tag = trim($single_tag);	
                //A statement végrehajt egy lekérdezést, ami kiválasztja a felvitt tag id-jét, ha már szerepel az adatbázisban
		$query = $connect->prepare("SELECT ID FROM tag WHERE tag = '$single_tag'");	
		$query->execute(array($single_tag));	
                //Statement eredménye
		$q_result = $query->fetchColumn();	
		$all_tagid = $q_result;	
		
		//Ha a tagok közül valamelyik nem volt benne az adatbázisban, ($q_result üres)
		if(empty($q_result)){	
                        //Beszúrja az újonnan felvitt tagot a tag tábla tag oszlopába
			$statement = $connect->prepare("INSERT INTO tag(tag)VALUES(?)");	
			$statement->execute(array($single_tag));	
                        //Legutóbb felvitt elem ID-je
			$last_id = $connect->lastInsertId();	
			$all_tagid = $last_id;	

		}
                //A statement beszúrja az $all_tagid, és $post_id változókat a posttotag táblába a ciklus lefutásaikor
		$statement = $connect->prepare("INSERT INTO posttotag(tag_id, post_id) VALUES(?,?)");	
		$statement->execute(array($all_tagid, $post_id));	
	}

}

/*
hidden_input függvény
Ha 
*/
function hidden_input ($id_post)
{
	if (!empty ($id_post))
	{
		echo "<input type='hidden' name='id' value='$id_post'>";
	}
}

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

	

//Deklarálja a $connect változót. Ez egy PDO segítségével kapcsolatot hoz létre az adatbázissal.
$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');	

/*
HA a $textarea nem üres, $repost = textarea
*/
if(!empty($textarea)) 
{

	$repost=$textarea;	
//HA az $id_post nem üres, végrehajtja az edit_post statementet.
}	else if(!empty($id_post)) {	
        //A statement végrehajt egy lekérdezést, ami kiválasztja egy postot, aminek az id-je megegyezik az $id_post változóval
	$edit_post=$connect->prepare("SELECT post FROM post WHERE id=?");
	$data=array($id_post);
	$edit_post->execute($data);
	$result=$edit_post->fetchColumn();
	$repost=$result;

}

/*
Ha le lett nyomva a közzétesz gomb ÉS validált két változót a validate függvény
*/
if (isset($_POST['Közzétesz']) && (validate($textarea) && validate($tags))) 
{	
    
	//ha van $id_post, frissíti az adatbázisban a blogpostot, amihez az adott id tartozik
	if(!empty($id_post)){	
		$stmnt = $connect->prepare("UPDATE post SET post =? WHERE id=?");
		$stmnt->execute(array($textarea, $id_post));
	}
	//ha a feltétel nem teljesült
	else{
                //beszúrja a textarea tartalmát a post táblába
		$statement = $connect->prepare("INSERT INTO post(post)VALUES(?)");
		$statement->execute(array($textarea));
		$post_id = $connect->lastInsertId();
	}
        
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
