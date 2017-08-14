<?php

// Veritabanı bağlantısı
try{

	$DB = new PDO("mysql:host=localhost;dbname=ihit_db;charset=utf8", "root", "Alfa123456");
	$DB->query("SET NAMES UTF8");

}catch(PDOException $msg){

	echo "Bir Hata Oluştu [A01]";
	die();

}

?>
