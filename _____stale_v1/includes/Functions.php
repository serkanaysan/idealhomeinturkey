<?php
date_default_timezone_set('Europe/Istanbul');
$MAIN_URL = 'https://www.idealhomeinturkey.com';

require_once 'Mobile_Detect.php';
require_once 'Browser.php';
$detect = new Mobile_Detect;

if(!isset($_SESSION['ihit_visitorInfo_serialize'])){

	// IP Adresi
	$IPAdress = addslashes(htmlspecialchars($_SERVER['REMOTE_ADDR']));
	
	// Referer
	$Referer = addslashes(htmlspecialchars($_SERVER['HTTP_REFERER']));
		
	// UTM Source
	$UTM_Source = addslashes(htmlspecialchars($_GET['utm_source']));
	
	// UTM Medium
	$UTM_Medium = addslashes(htmlspecialchars($_GET['utm_medium']));
	
	// UTM Campaign
	$UTM_Campaign = addslashes(htmlspecialchars($_GET['utm_campaign']));
	
	if($detect->isMobile()){
		
		$Device = 'Mobile';
		
	}elseif($detect->isTablet()){
		
		$Device = 'Tablet';
		
	}else{
		
		$Device = 'Desktop';
		
	}
	
	$Browser = new Browser;
	
	$BrowserInfo = $Browser->getBrowser() . " " . $Browser->getVersion();
	
	$VisitorInfo = array(
	
		'referral' => $Referer,
		'ip_adress' => $IPAdress,
		'browser' => $BrowserInfo,
		'created_at' => strtotime('Now'),
		'utm_source' => $UTM_Source,
		'utm_medium' => $UTM_Medium,
		'utm_campaign' => $UTM_Campaign,
		'device' => $Device,
	
	);
	
	$VisitorInfo_Serialized = serialize($VisitorInfo);
	
	$_SESSION['ihit_visitorInfo_serialize'] = base64_encode($VisitorInfo_Serialized);

}

function getSettings($Column){
	
	global $DB;
	
	$getSettings = $DB->query("SELECT {$Column} FROM ihit_settings");
	$getSettings = $getSettings->fetch(PDO::FETCH_ASSOC);
	
	return $getSettings[$Column];
	
}

function getCityInfo($city_id,$Column){
	
	global $DB;
	
	$getCityInfo = $DB->query("SELECT {$Column} FROM ihit_projects_citys WHERE id = '{$city_id}'");
	$getCityInfo = $getCityInfo->fetch(PDO::FETCH_ASSOC);
	
	return $getCityInfo[$Column];
	
}

function permalink($string){
	
	$find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
	$replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
	$string = strtolower(str_replace($find, $replace, $string));
	$string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
	$string = trim(preg_replace('/\s+/', ' ', $string));
	$string = str_replace(' ', '-', $string);
	return $string;
	
}


?>