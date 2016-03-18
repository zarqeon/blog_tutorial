<?php

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
class classPost extends classConstructor{

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

/*
hidden_input függvény 
*
public function hidden_input ($id_post)
{
    if (!empty ($id_post))
    {
	echo "<input type='hidden' name='id' value='$id_post'>";
    }
}
 */       

public function setTags ($value)
{   

	//var_dump ($value);

	$separated_tag = explode(",", $value);


	foreach($separated_tag as $singular_tag)
	{   
          
            $tag_array = array('name'=>$singular_tag);
            
            $new_tag = new classTag ($tag_array);       

            $this->tags[] = $new_tag;   
   
	}
        
}
}
?>
