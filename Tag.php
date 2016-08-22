<?php

require_once 'Model.php';   

class Tag extends Model {
    //változók
	/**
	 * tag id
	 *
	 * @var int
	 */
	public $id;

	/**
	 * name
	 * a tag neve
	 *
	 * @var string
	 */
	public $name;	

	/**
	 * tableName
	 * a tábla neve amiben a tag lakik.
	 *
	 * @var string
	 */
	private $tableName = "tag";
	
    //függvények
    
    /**
     * ebből a függvényből majd konstruktort kell csinálni!
     *
    public function processTags ($connect, $tags, $post_id){ 
        //szétválasztja vesszőnként a tag mezőbe beírt tagokat
	$exploded_tags = explode(",", $tags);	
        //lefuttat egy ciklust minden különválasztott tagra
	foreach($exploded_tags as $single_tag){	
                //leveszi a szóközöket a tagok mindkét végéről
		$single_tag = trim($single_tag);	
                //A statement végrehajt egy lekérdezést, ami kiválasztja a felvitt tag id-jét, ha már szerepel az adatbázisban
		$query = $connect->prepare("SELECT id FROM tag WHERE tag_name = '$single_tag'");	
		$query->execute(array($single_tag));	
                //Statement eredménye
		$q_result = $query->fetchColumn();	
		$all_tagid = $q_result;	
		
		//Ha a tagok közül valamelyik nem volt benne az adatbázisban, ($q_result üres)
		if(empty($q_result)){	
                        //Beszúrja az újonnan felvitt tagot a tag tábla tag oszlopába
			$statement = $connect->prepare("INSERT INTO tag(tag_name)VALUES(?)");	
			$statement->execute(array($single_tag));	
                        //Legutóbb felvitt elem ID-je
			$last_id = $connect->lastInsertId();	
			$all_tagid = $last_id;	
		}
                //A statement beszúrja az $all_tagid, és $post_id változókat a posttotag táblába a ciklus lefutásaikor
		$statement = $connect->prepare("INSERT INTO posttotag(tag_id, post_id) VALUES(?,?)");	
		$statement->execute(array($all_tagid, $post_id));	
	}

       
    }
     */
     
   public function __construct ($tag_array)
   {
	   parent::__construct($tag_array);
	   $this->checkTags ();
   }  
   
   
   public function Create ()
   {
	    $connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz');
			
		$statement = $connect->prepare("INSERT INTO" . $this->tableName . "(tag)VALUES(?)");
		$statement->execute(array($this->name));	
		
		$this->id = $connect->LastInsertId();
   }     
     
   public function Save () 
   {
	   if(empty($this->id))
	   {
		   $this->Create();
       }	   
   } 
	     

	public function checkTags()
	{
		$connect = new PDO ('mysql:host=localhost;dbname=blog','root','4fhc9imz');
		$statement = $connect->prepare("SELECT ID FROM" . $this->tableName . "WHERE tag=?");
		$statement->execute(array($this->name));
		
		$result = $statement->fetch();
		
		if (isset($result['ID']))
		{
			$this->id = ($result['ID']);
		}	
	}  

	     
}
?>
