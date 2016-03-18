<?php

class classTag extends classConstructor {
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
	private $tableName;
	
    //függvények
    
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
}
?>