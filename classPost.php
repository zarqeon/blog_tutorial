<?php

require_once 'Model.php';   

/**
 * rövidtávú cél:
 * elérni, hogy a classTag objektumnak ne kelljen kívülről beállítani az attribútumait.
 * TEHÁT: ne így kelljen példányosítani:
 * $tag = new classTag();
 * $tag->name = 'lófasz';
 *
 * hanem e helyett inkább így:
 * $attr = array(
 * 	'name' => 'lófasz'
 * );
 * $tag = new classTag($attr);
 */

/**
 * Class: Post
 * Egy blog postot reprezentáló osztály.
 * Egy példánya a classPost osztálynak = egy blog post-al, vagyis:
 * - egy post minden adatát tartalmazza
 * - viseli a felelősséget mindenért ami egy post-al történni tud, pl:
 * 	- adatbázisból kinyerni egy post-ot
 * 	- beletenni az adatbázisba egy post-ot.
 *
 */
class classPost extends Model{

	/**
	 * text
	 * a blog post szövege
	 *
	 * @var strung
	 */
	public $text;

	/**
	 * tags
	 * a post-hoz tartozó tag-ek.
	 *
	 * @var array
	 */
	public $tags;

	/**
	 * id
	 *
	 * @var int
	 */
	public $id;
	
	/**
	 * tableName
	 * a tábla neve amiben a poszt lakik
	 *
	 * @var string
	 */
	private $tableName;   

public function setTags ($value)
{   
	$separated_tag = explode(",", $value);

	foreach($separated_tag as $singular_tag)
	{   
            $singular_tag = trim($singular_tag);
            $tag_array = array('name'=>$singular_tag);
            $new_tag = new classTag ($tag_array);       
            $this->tags[] = $new_tag;   
	}  
}


/*
validate_button
két változót validál
*/

/*public function validate_button ($textarea, $tags)	
{
        //Ha a $textarára, és a $tags-ra teljesül a validate függvény, végrehajtja a kódot, tehát echózik egy új gombot a meghívás helyén.
	if(validate($textarea) && validate($tags))	
	{
		echo '<input type="submit" name="Közzétesz" value="Közzétesz">';	
	}	
}
*/


/*új függvény, ami úgy használja a model::validate-et, mint a validate_button a validate-et*/


public function validate ($textarea, $tags)
{
	
	if(parent::validate($textarea) && parent::validate($tags))
	{
		echo '<input type="submit" name="Közzétesz" value="Közzétesz">';
	}
}



}



?>
