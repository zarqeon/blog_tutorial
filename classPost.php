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
*
public function hidden_input ($id_post)
{
    if (!empty ($id_post))
    {
	echo "<input type='hidden' name='id' value='$id_post'>";
    }
}
 */
    

/**
 * __construct
 * konstruktor függvény, ami autómatikusan meghívódik, amikor az osztályt példányosítjuk, és megkapja paraméterként a példányosításkor adott értékeket.
 * TEHÁT:
 * amikor:
 * new classPost('valami', 'lofasz');
 * akkor a __construct első paraméterként azt fogja megkapni, hogy 'valami' második paraméterként azt hogy 'lofasz'.
 * HA a konstruktor deklarációja így néz ki:
 * public function __construct($att1, $something)
 * AKKOR a konstruktor függvényen belül az $att1 értéke az lesz, hogy 'valami'
 * és a $something éréke az lesz hogy 'lofasz'.
 *
 * a konstruktor feladata ebben az osztályban az, hogy beállítsa az objektum attríbútumait, hogy ezt ne kelljen kívülről elvégezni.
 * TEHÁT:
 * ahelyett hogy így építenénk fel egy új post objektumot:
 * $newpost = new classPost();
 * $newpost->text = 'asd';
 * $newpost->tags = array('tag1', 'tag2', 'tag3');
 *
 * HELYETT:
 * $attributes = array(
 * 	'text' => 'ast',
 * 	'tags' => array('tag1', 'tag2', 'tag3')
 * );
 * $newpost = new classPost($attributes);
 *
 * HOGY MŰKÖDIK?
 * ahelyett, hogy minden attribútumot külön adnánk oda paraméterben a konstruktornak
 * pl: public function __construct($text, $tags, $id, $table_name)
 * ezzel egymillió paramétert adva a konnstruktornak, és arra kényszerítve magunkat, hogy nagyon pontosan emlékezzünk a paraméterek sorrendjére,
 * ehelyett egyetlen tömböt adunk át neki, ami úgy néz ki, hogy a tömb indexe lesz a paraméter neve, az értéke pedig a paraméter értéke, vagyis:
 * public function __construct($attributes)
 * AHOL az $attributes:
 * $attributes = array(
 * 	'paraméter_neve' => 'paraméter_értéke'
 * );
 *
 * a konstruktor továbbá úgy működik, hogy ahelyett, hogy minden attribútomot beállít 'manuálisan', pl:
 * public function __construct($attributes){
 * 	$this->text = $attributes['text'];
 * 	...
 * }
 * ami NAGYON rugalmatlan lenne (mi van ha hozzá akarunk adni még 320 attribútumot? akkor írhatjuk át a konstruktor-t),
 * EHELYETT, mivel az $attributes tömb indexe úgy is tartalmazza az attribútum nevét amit be akarunk állítani, mondhatjuk azt, hogy:
 * public function __construct($attributes){
 * 	foreach($attributes as $attribute => $value){
 * 		$this->$attribute = $value;
 * 	}
 * }
 * ez MINDEN attribútumra működik.
 *
 * De még ez sem elég, mert ezzel azt feltételezzük, hogy a konstruktor készre gyártott értékeket kap:
 * pl a tags attribútumhoz már példányosított classTag objektumokat.
 * ugyanakkor azt mondtuk, hogy minden blog post-al kapcsolatos felelősség a classPost osztályé,
 * TEHÁT a konstruktor nem lehet ilyen buta. ha csak belehányjuk a tagokat, mint string-eket, abból neki kell példányosítania
 * a classTag objektumokat.
 *
 * emiatt a gyönyörű álltalános, minden attribútumra működő konstruktorunkat ki kell okosítani úgy, hogy ne saját maga állítsa be az attribútumokat,
 * hanem használjon setter függvényeket.
 *
 * MI AZ A SETTER?
 * a setter egy olyan függvény, ami egy attribútumnak be tud állítani egy értéket, pl:
 * public function setText($value)
 * {
 * 	$this->text = $value;
 * }
 *
 * ahhoz hogy megvalósítsuk azt, hogy a konstruktorunk BÁRMILYEN attribútumot be tudjon állítani, és ne is legyen buta (tehát mondjuk egy stringből tudjon más osztályt példánysosítani
 * és a példányt beállítani az attribútum értékének) azt kell tennünk, hogy minden egyes attribútumra meghívjunk egy settert.
 * TEHÁT:
 * public function __construct($attributes){
 * 	foreach($attributes as $attribute => $value){
 * 		call_user_func(array($this, 'set'.ucfirst($attribute)), $value);
 * 	}
 * }
 * itt mi törénik?
 * HA az $attributes = array('text' => 'bla')
 * AKKOR a konstruktor meghívja a $this->setText('bla') függvényt.
 *
 * Igy viszont minden lökött attribútumra kellene írni egy setter függvényt.
 * ehelyett készítünk egy álltalános setter-t ami gyakorlatilag azt csinálja, mint a pár sorral fentebbi, jóval butább konstruktorunk csinált:
 * public function defaultSetter($attribute, $value){
 * 	$this->$attribute = $value;
 * }
 *
 * a szuper konstruktorunk kész, lássuk mit csinál
 *
 * @param array $attributes attribútum név => attribútum érték
 * @return 
 */
public function __construct($attributes){

	//végigiterál az attribútum tömbön.
	//a ciklusmagban a $key lesz az attribútum neve
	//a $value lesz az attribútum értéke
	foreach($attributes as $key => $value){
        
		//meghatározza hogy mi a setter függvény neve.
		//ha az attribútum neve (vagyis a $key) az volt, hogy 'text',
		//akkor a setter függvény neve az lesz hogy 'setText'
		$function_name = 'set'.ucfirst($key);

        	//megnézi, hogy létezik-e ebben az osztályban ez a setter függvény
		if(method_exists($this, $function_name))
		{
			//ha LÉTEZIK akkor meghívja, és átadja neki az attribútum értékét,
			//VAGYIS, ha az attribútum (a $key) az volt, hogy 'text'
			//és az attribútum értéke (a $value) az volt, hogy 'bla'
			//akkor meghívja a:
			//$this->setText('bla')
			call_user_func(array($this, $function_name), $value);
		} else {
			//ha NEM LÉTEZIK a setter függvény, akkor meghívja a defaultSetter-t,
			//VAGYIS, ha az attribútum (a $key) az volt, hogy 'text'
			//és az attribútum értéke (a $value) az volt, hogy 'bla'
			//akkor meghívja a:
			//$this->defaultSetter('text', 'bla')
			$this->defaultSetter($key, $value);
		}
	}
}

/**
 * defaultSetter
 * alapértelmezett setter függvény. ha nincs spéci setter függvénye egy attribútumnak,
 * akkor a konstruktor ezt a függvényt fogja meghívni
 *
 * @param string $key az attribútum neve
 * @param string $value az attribútum értéke
 * @return 
 */
private function defaultSetter ($key, $value)
{
    $this->$key = $value;
}

public function setTags ($value)
{   

	//var_dump ($value);

	$separated_tag = explode(",", $value);


	foreach($separated_tag as $singular_tag)
	{   
          
            $tag_array = array('name'=>$singular_tag);
            
            $new_tag = new classTag ($tag_array); 

            $new_tag->name =$singular_tag;        

            $this->tags[] = $new_tag;   
   
	}
        
}
}
?>
