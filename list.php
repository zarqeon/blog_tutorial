<?php
$connect = new PDO ('mysql:host=localhost;dbname=blog','root','');

$query = $connect->query('SELECT * FROM post');

while($r = $query->fetch()) {
	
	echo $r['post'];
	echo $r['id'], '<br />';

}

?>