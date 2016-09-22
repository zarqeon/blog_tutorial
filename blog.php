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

require_once 'Post.php';

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

	// ha van bármi a POST-ban akkor abból csinálunk egy objektumot
	if(!empty($textarea) || !empty($tags))
	{
		$post = new Post ([
			'text' => $textarea,
			'tags' => $tags
		]);
	} else 
		
	// blogpost id-je, ami alapján behozta a blogpostot a list.php
	if(isset($_REQUEST['id']))
	{
		$post = Post::getObject(($_GET['id']));
	}

	//létrehozunk egy üres objektumot, hogy ne legyen notice az echozásnál
	if(!isset($post))
	{
		$post = new Post();
	}
        
//classPost példányosítás 

//$connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz'); //nemtudom jó ez e itt, szükség van rá a 65 sorhoz.

if($post->validate()) 
	{
		/*var_dump ($post);*/
	}



/*var_dump ($post->tags);*/



/*if(isset($_POST['Előnézet']) && ($_POST['textarea']) && ($_POST['tags']))
{
echo ($_POST['textarea']), "<br />";
echo ($_POST['tags']);
}
*/



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

if (isset($_POST['Mentés']))
{
	$post->Save();
}


?>

<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form action="blog.php" method="post">
<textarea id="textarea" name="textarea" rows="10" placeholder="Ide írj" cols="50">
<?php 

echo $post->textarea;

?>
</textarea>
<br />
<input type="text" id="tags" name="tags" value = "
<?php 
echo $post->tags;

?>
">
<br />
<input type="submit" name="Előnézet"value="Előnézet">
<br />
<?php
//hidden_input($id_post); //itt hívódik meg a hidden_input függvény

if($post->validate()) 
{
	echo "<input type='submit' name='Mentés' value='Mentés'>";
}
?>

</form>
</body>
</html>
