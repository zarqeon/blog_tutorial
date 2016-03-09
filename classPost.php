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

    $this->text =$attributes['text'];
    $this->tags =$tag_array;
    
    foreach($attributes as $key => $value){
	//beállítja az attributes-ban megadott attribútumok értékét
	//$this->setter ($key, $value);    
	//what the actual fuck does this do? is it magic? hell it is!
        
       $function_name = 'set'.ucfirst($key);
       
       var_dump ($function_name);
        
        if(method_exists($this, $function_name)){//nem tudom hogy itt mit nézzen meg hogy létezik e pontosan.
            call_user_func(array($this, $function_name), $value);
        }
        else{
            $this->setter($key, $value);
        }
        
    }
    
}

private function setter ($key, $value)
{
    $this->$key = $value;
}

public function setId($value)
{
	$this->setter('id', $value);
}

public function setText($value)
{
        var_dump ($value);
	$this->setter('text', $value);
}

public function setTag ($value)
{   
    
    $separated_tag = explode(",", $attributes['tags']);
    
    foreach($separated_tag as $singular_tag)
    {   
        $new_tag = new classTag (); 
        $new_tag->name =$singular_tag;
        $tag_array[]=$new_tag;
    }

    
}
}
?>
