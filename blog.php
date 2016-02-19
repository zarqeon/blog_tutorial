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
 * -
 *
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//globális változók dektlarálása

$textarea = $tags = $post_id = false;

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

echo $_POST ["textarea"];	// Kiechozza mi lett POST módszerrel elküldve a textareából
echo $_POST ["tags"];	// Kiechózza mi lett POST módszerrel elküldve a tags input mezőből

//eddig nyúltam bele !!!!!
$id_post = ($_REQUEST['id']);	// Deklarálja az $id_post változót (az URL-ben lett átküldve a list.php fájlból, és REQUEST módszerrel lett beszerezve)


/**
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

function validate_button ($textarea, $tags)	//deklarálja a validate_button nevű függvényt, és a $textarea, és a $tags változókat adja meg paraméternek
{
	if(validate($textarea) && validate($tags))	//A feltétel akkor teljesül, ha a validate függvény lefutása a $textarea ÉS a $tags változók felhasználásval true értéked ad vissza
	{
		echo '<input type="submit" name="Közzétesz" value="Közzétesz">';	//ha teljesült a feltétel, akkor egy közzétesz buttont echóz a függvény
	}	
}

$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');	//Deklarálja a $connect változót. Ez egy PDO segítségével kapcsolatot hoz létre az adatbázissal.



if(!empty($textarea)) //Az elágazás feltétele az, hogy a $textarea változó ne legyen üres
{

	$repost=$textarea;	//Ha teljesült a feltétel, a $repost változót egyenlővé teszi a $textarea változóval

}	else if(!empty($id_post)) {		//Ennek az elágazásnak a feltétele, hogy az $id_post változó ne legyen üres
	$edit_post=$connect->prepare("SELECT post FROM post WHERE id=?");	//Ha teljesült a feltétel, ez a sor létrehoz egy statementet, ami kiválaszt egy mezőt a post táblából egy id alapján
	$data=array($id_post);	//deklarálja a $data változót, ami egyenértékű az $id_posttal
	$edit_post->execute($data);		//végrehajtja az edit_post statementet, a $data változót használva paraméterként
	$result=$edit_post->fetchColumn();		//fetcheli azt az oszlopot amit az edit_post statement kapott eredményként, és egyenlővé teszi az eredményt az $result változóval
	$repost=$result;	//deklarálja a $repost változót, ami a $result változóval egyenértékű

}



function hidden_input ($id_post)	//deklarálja a hidden_input nevű függvényt, az $id_post változót felhasználva paraméterként
{
	if (!empty ($id_post))	//az elágazás feltétele az, hogy az $id_post változó ne legyen üres
	{
		echo "<input type='hidden' name='id' value='$id_post'>";	//ha teljesült a feltétel, ezt a sort echózza ki, egy láthatatlan input mezőt
	}
}




if (isset($_POST['Közzétesz']) && (validate($textarea) && validate($tags))) //ennek az elágazásnak az a feltétele, hogy le legyen nyomva a közzétesz gomb, ÉS fusson le a validate függvény a $textarea-ra, és a $tags-ra
{	
	if(!empty($id_post)){	//az elágazás feltétele, hogy ne legyen üres az $id_post változó
		$stmnt = $connect->prepare("UPDATE post SET post =? WHERE id=?");	//létrehoz egy statementet, ami frissíti a post táblának post oszlopát, abban a sorban, amelyben a megadott id szerepel.
		$stmnt->execute(array($textarea, $id_post));	//végrehajtja a statementet, a $textarea, és az $id_post változókat felhasználva
	}

	else{	//ha nem teljesült a fenti feltétel, az alábbi kódot hajtja végre

		$statement = $connect->prepare("INSERT INTO post(post)VALUES(?)");	//létrezhoz egy statementet, ami beszúr egy értéket a post tábla post oszlopába
		$statement->execute(array($textarea));	//végrehajtja a statementet, a $textarea változót felhasználva paraméterként (ennek a változónak az értékét szúrja majd be)
		$post_id = $connect->lastInsertId();	//egyenlővé teszi a $post_id változót a legutóbb beszúrt id értékével.
	}
}	




function processTags ($connect, $tags, $post_id){ //deklarálja a porcessTags függvényt, és a $connect, $tags, és $post_id változókat használja paraméterként

	$exploded_tags = explode(",", $tags);	//explode-olja a $tags stringet, a vesszőknél fogva

	foreach($exploded_tags as $single_tag){	//létrehoz egy foreach loopot, amiben az exploded_tags elemeiből $single_tag változót csinál
		$single_tag = trim($single_tag);	//leveszi a whitespace-t a $single_tag stringek elejéről és végéről

		$query = $connect->prepare("SELECT ID FROM tag WHERE tag = '$single_tag'");	//létrehoz egy select statementet, ami kiválaszt egy id-t a tag tábla tag oszlopából, ahol a tag a $single_tag változóval azonos
		$query->execute(array($single_tag));	//végrehajta a statementet, a $single_tag változót felhasználva paraméterként
		$q_result = $query->fetchColumn();	//deklarálja a $q_result változót, ami egyenértékű a $query statement eredményének egy oszlopával.
		$all_tagid = $q_result;	//deklarálja az $all_tagid változót, ami a $q_result változóval egyenértékű

		if(empty($q_result)){	//az elágazás feltétele akkor teljesül, ha a $q_result változó üres
			$statement = $connect->prepare("INSERT INTO tag(tag)VALUES(?)");	//létrehoz egy statementet, ami beszúr egy értéket a tag tábla tag oszlopába
			$statement->execute(array($single_tag));	//végrehajtja a statementet a $single_tag változót felhasználva paraméterként
			$last_id = $connect->lastInsertId();	//deklarálja a $last_id változót, ami az utoljára beszúrt id értékével egyenlő
			$all_tagid = $last_id;	//deklarálja az $all_tagid változót, ami a $last_id-vel egyenértékű

		}
		$statement = $connect->prepare("INSERT INTO posttotag(tag_id, post_id) VALUES(?,?)");	//létrehoz egy statementet, ami beszúr a posttotag tábla két oszlopába két értéket
		$statement->execute(array($all_tagid, $post_id));	//végrehatja a statementet, felhasnzálva az $all_tagid, és a $post_id változókat paraméterként
	}

}

if(isset($_POST['Közzétesz'])){	//az elágazást ahhoz a feltételhez köti, hogy a közzétesz gomb le legyen nyomva

	processTags($connect, $tags, $post_id);	//meghívja a processTags függvényt, a $connect, $tags, és a $post_id változókat használva paraméterként

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
validate_button($textarea, $tags); //meghívja a validate_button függvényt, a $textarea, és a $tags változókat felhasználva paraméterként
hidden_input($id_post); //meghívja a hidden_input függvényt, az $id_post változót felhasználva paraméterként
?>

</form>
</body>
</html>
