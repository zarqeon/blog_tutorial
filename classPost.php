<?php

<<<<<<< HEAD
require_once 'Model.php';   
=======
require_once 'classConstructor.php';   
>>>>>>> c26e6494796d744ade0c6aa1001b7534550ea2e4

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
<<<<<<< HEAD
class classPost extends Model{
=======
class classPost extends classConstructor{
>>>>>>> c26e6494796d744ade0c6aa1001b7534550ea2e4

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
<<<<<<< HEAD

=======
>>>>>>> c26e6494796d744ade0c6aa1001b7534550ea2e4
}
?>
