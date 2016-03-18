<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

/*
ide jönne a két calss
*/

/**
 * Class: Post
 * egy blog poszt szőröstül bőröstül
 *
 */

include 'classPost.php';

include 'classTag.php';

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

        
//classPost példányosítás 
                      	

        
$attributes = [
'text' => $textarea,
'tags' => $tags];

$new_post = new classPost ($attributes);

//var_dump ($new_post);

//Deklarálja a $connect változót. Ez egy PDO segítségével kapcsolatot hoz létre az adatbázissal.
$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');	

/*
HA a $textarea nem üres, $repost = textarea
*/

$repost = "";

if(!empty($textarea)) 
{
	$repost=$textarea;	
//HA az $id_post nem üres, végrehajtja az edit_post statementet.
}	else if(!empty($id_post)) {	
        //A statement végrehajt egy lekérdezést, ami kiválasztja egy postot, aminek az id-je megegyezik az $id_post változóval
	$edit_post=$connect->prepare("SELECT post_text FROM post WHERE id=?");
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
		$stmnt = $connect->prepare("UPDATE post_text SET post =? WHERE id=?");
		$stmnt->execute(array($textarea, $id_post));
	}
	//ha a feltétel nem teljesült
	else{
                //beszúrja a textarea tartalmát a post táblába
		$statement = $connect->prepare("INSERT INTO post(post_text)VALUES(?)");
		$statement->execute(array($textarea));
		$post_id = $connect->lastInsertId();
	}
        
        processTags($connect, $tags, $post_id);
}	

/*
hidden_input függvény 
*/
function hidden_input ($id_post)
{
    if (!empty ($id_post))
    {
	echo "<input type='hidden' name='id' value='$id_post'>";
    }
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
