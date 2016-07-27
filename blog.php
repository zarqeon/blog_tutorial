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

include 'Post.php';
include 'Tag.php';


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

$post = new Post ([
	'text' => $textarea,
	'tags' => $tags
]);


//Deklarálja a $connect változót. Ez egy PDO segítségével kapcsolatot hoz létre az adatbázissal.
//$connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz');	

/*
Ha le lett nyomva a közzétesz gomb ÉS validált két változót a validate függvény
*/
//if (isset($_POST['Közzétesz']) && ($post->validate())) 
//{	
    
	////ha van $id_post, frissíti az adatbázisban a blogpostot, amihez az adott id tartozik
	//if(!empty($id_post)){	
		//$stmnt = $connect->prepare("UPDATE post_text SET post =? WHERE id=?");
		//$stmnt->execute(array($textarea, $id_post));
	//}
	////ha a feltétel nem teljesült
	//else{
                ////beszúrja a textarea tartalmát a post táblába
		//$statement = $connect->prepare("INSERT INTO post(post_text)VALUES(?)");
		//$statement->execute(array($textarea));
		//$post_id = $connect->lastInsertId();
	//}
        
        //processTags($connect, $tags, $post_id);
//}	

//[>
//hidden_input függvény 
//*/
//function hidden_input ($id_post)
//{
    //if (!empty ($id_post))
    //{
	//echo "<input type='hidden' name='id' value='$id_post'>";
    //}
//}

?>

<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form action="blog.php" method="post">
<textarea id="textarea" name="textarea" rows="10" placeholder="Ide írj" cols="50">
<?php if($post->validate()) { echo $post->text; } //kiechózza a $repost változó értékét, így marad meg a textarea tartalma ?>
</textarea>
<br />
<input type="text" id="tags" name="tags" value = "<?php echo $tags ?>">
<br />
<input type="submit" name="Előnézet"value="Előnézet">
<br />
<?php
//hidden_input($id_post); //itt hívódik meg a hidden_input függvény
?>

</form>
</body>
</html>
