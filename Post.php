<?php

require_once 'Model.php';   
require_once 'Tag.php';

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
		
		$this->id = $connect->LastInsertId();
	}
	
	public function Update ()
	{
		$statement = $connect->prepare("UPDATE post SET post =? WHERE id=?");
		$statement->execute(array());
	}	


	public function Iterate ()
	{
			foreach ($this->tags as $tag_object)
			{			
				$tag_object->Save();
			}
			
	}
	
	public function PosttoTag ()
	{
		
		foreach ($this->tags as $post_tags)
		{	
			$connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz');
			$statement = $connect->prepare("INSERT INTO posttotag(tag_id, post_id)VALUES(?,?)");
			$statement->execute(array($post_tags->id, $this->id));		
		}
		
	
	}

	public function Save () 
	{		
		$this->Iterate();	
		

			
		if(!empty($this->id))
		{
			$this->Update();
		}
		else
		{
			$this->Create();
		}	
		
		$this->PosttoTag();
	}
	
	public static function PostList ()
	{	
		/*$connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz');
		$query = $connect->query('SELECT * FROM post');
		$result = $query->fetchAll();
		
		foreach($result as $id ) {
			
		//ez a statemenet számolná meg, hogy hány tag kapcsolódik az adott posthoz
		$count_tag = $connect->prepare('SELECT COUNT(post_id) FROM posttotag WHERE post_id=?');
		$count_tag->execute(array($id['id']));
		$number = $count_tag->fetchColumn();//valahogy el kéne íréni hogy "$result['id']" -re hajtsa végre

		
		$echo_tags = $connect->prepare('SELECT tag.tag_name, posttotag.tag_id FROM tag INNER JOIN posttotag ON tag.id=posttotag.tag_id WHERE post_id=?');
		$echo_tags->execute(array($id['id']));
		$tag_list = $echo_tags->fetchAll(); 
		
		
		
		echo '<a href="blog.php?id='.$id['id'].'">', $id['id'], '</a>';
		echo $id['post'];  
		echo '(', $number, ')';    
		
	   
		
		foreach($tag_list as $name)
		{   
			echo '<a href="tag_post.php?tag='.$name['tag_id'].'">', $name['tag_name'], '</a>', ', ';
		}
		
		echo '<br />';   
		}*/
	}
	
	public static function getObject ()
	{
		$connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz');
		
		$statement_text = $connect->prepare("SELECT post FROM post WHERE id=?");
		$statement_text->execute(array("313"));
		$re_text = $statement_text->fetch(); 
		
		$statement_tagids = $connect->prepare("SELECT tag_id FROM posttotag WHERE post_id=?");
		$statement_tagids->execute(array("313"));
		$re_tagid = $statement_tagids->fetchAll();
		
		
		foreach ($re_tagid as $tagid)
		{	
			$tagid_value = ($tagid['tag_id']);
			
			$statement_tags = $connect->prepare("SELECT tag FROM tag WHERE id=?");
			$statement_tags->execute(array($tagid_value));
			$re_tags = $statement_tags->fetch();
			
			$pure_tag=($re_tags['tag']);	
			
			var_dump ($pure_tag);
		}
		
	$rc_post = new Post (['text' => $re_text, 'tags' => $pure_tag]);
		
	}

	
}
?>
