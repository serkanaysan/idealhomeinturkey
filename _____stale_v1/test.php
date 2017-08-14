<?php

// Veritabaný baðlantýsý
try{

	$DB = new PDO("mysql:host=localhost;dbname=ihit_pbUIH359;charset=utf8", "ihit_pbUIH359", "5%hOf+}FTpRc");
	$DB->query("SET NAMES UTF8");

}catch(PDOException $msg){

	echo "Bir Hata Oluþtu [A01]";
	die();

}

$List = $DB->query("SELECT * FROM ihit_social_areas");
foreach($List->fetchAll() as $Detail){
	echo $Detail['name']."<br />";
}

?>