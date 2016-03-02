<?php

class classPost {

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
*/
public function hidden_input ($id_post)
{
    if (!empty ($id_post))
    {
	echo "<input type='hidden' name='id' value='$id_post'>";
    }
}
    

public function __construct($attributes){
    
    $textarea = $attributes['text'];
    $tags = $attributes['tags'];
    
    $separated_tag = explode(",", $tags);//szétválaszjta a tagokat a vesszőnél

    foreach($separated_tag as $singular_tag)
    {
    $new_tag = new classTag ();
    $new_tag->name =$singular_tag;
    $tag_array[]=$new_tag;
    }
    
    $new_post->text =$textarea;
    $new_post->tags =$tag_array;
   
    var_dump ($tags);
}
}
?>