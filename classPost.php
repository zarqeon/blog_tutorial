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

    foreach($attributes as $key => $value){
        
	//beállítja az attributes-ban megadott attribútumok értékét
	//$this->setter ($key, $value);    
	//what the actual fuck does this do? is it magic? hell it is!

       $function_name = 'set'.ucfirst($key);

        
        if(method_exists($this, $function_name)){//nem tudom hogy itt mit nézzen meg hogy létezik e pontosan.
            call_user_func(array($this, $function_name), $value);
            
            //var_dump ($value);
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

	$this->setter('text', $value);
}

public function setTags ($value)
{   
    
    //var_dump ($value);
    
    $separated_tag = explode(",", $value);
    
    
    foreach($separated_tag as $singular_tag)
    {   
        //var_dump ($singular_tag);
        
        $new_tag = new classTag (); 
        $new_tag->name =$singular_tag;        
        
        //var_dump ($new_tag);
        
        $this->tags[] = $new_tag;
    }

}
}
?>
