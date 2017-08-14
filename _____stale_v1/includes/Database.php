<?php

// Veritabanı bağlantısı
try{

	$DB = new PDO("mysql:host=localhost;dbname=ihit_pbUIH359;charset=utf8", "ihit_pbUIH359", "5%hOf+}FTpRc");
	$DB->query("SET NAMES UTF8");

}catch(PDOException $msg){

	echo "Bir Hata Oluştu [A01]";
	die();

}

?>