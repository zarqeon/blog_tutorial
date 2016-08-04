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
class Post extends Model{

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
		if(!empty($value))
		
		{
		$separated_tag = explode(",", $value);

		foreach($separated_tag as $singular_tag)
		{   
			
			if(!empty($singular_tag))
			{
			$singular_tag = trim($singular_tag);
			$tag_array = array('name'=>$singular_tag);
			$new_tag = new Tag ($tag_array);       
			$this->tags[] = $new_tag;   
		}
	}
	}  
	}

	/*új függvény, ami úgy használja a model::validate-et, mint a validate_button a validate-et*/
	public function validate ()
	{
		if(!empty($this->tags) && is_array($this->tags))
		{
			return $this->validateAttribute($this->text);
		}

		return false;
	}
	
	public function Create ()
	{	
		
		$connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz');
			
		$statement = $connect->prepare("INSERT INTO post(post)VALUES(?)");
		$statement->execute(array($this->text));		
	}
	
}
?>
