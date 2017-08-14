<?php
	
	$URL = 'http://apps.idealhomeinturkey.com/landing-page/landing/ideal-016';
	$Status = 1; // 1 Aktif | 0 Pasif ---- PROJE DURUMU
	$FormName = 'Arabic Landing Page';
	$ProjectName = 'Blue Lake';
	$ProjectCode = 'ideal-016';
	$Title = 'Ideal Home in Turkey | Istanbul Real Estate Company | Your Ideal Home is Waiting For You!';
	$Slogan1 = 'الرفاهية الراحة الرقي و الفخامة , تجربة الحياة الفارهة و الساحرة بقرب الشاطئ تنتظرك';
	$Slogan2 = 'الموقع المتميز اطلالة على البحيرة و البحر , حياة الرفاهية و الكثير بأنتظارك';
	$Phone = '00 90 212 988 13 50';
	
	
	// Harita Bilgileri
	$Map = array(
	
		'api_key' => 'AIzaSyAeB_gxckxN1Np70JPXA9sP5xjxYYJhr94',
		'coordinate1' => '40.992976',
		'coordinate2' => '28.767199',
	
	);

	// IP Adresi
	$IPAdress = addslashes(htmlspecialchars($_SERVER['REMOTE_ADDR']));
	
	// Form Oluşturulma Zamanı
	$FormCreatedTime = strtotime('Now');
	
	// Referer
	$Referer = addslashes(htmlspecialchars($_SERVER['HTTP_REFERER']));
	
	// Sayfa
	$Page = addslashes(htmlspecialchars($_SERVER['REQUEST_URI']));
		
	// UTM Source
	$UTM_Source = addslashes(htmlspecialchars($_GET['utm_source']));
	
	// UTM Medium
	$UTM_Medium = addslashes(htmlspecialchars($_GET['utm_medium']));
	
	// UTM Campaign
	$UTM_Campaign = addslashes(htmlspecialchars($_GET['utm_campaign']));

	require_once 'Mobile_Detect.php';
	require_once 'Browser.php';
	$detect = new Mobile_Detect;
	
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
	
		'page' => $Page,
		'referral' => $Referer,
		'ip_adress' => $IPAdress,
		'browser' => $BrowserInfo,
		'created_at' => strtotime('Now'),
		'utm_source' => $UTM_Source,
		'utm_medium' => $UTM_Medium,
		'utm_campaign' => $UTM_Campaign,
		'device' => $Device,
		'form_created_at' => strtotime('Now'),
	
	);
	
	$VisitorInfo_Serialized = serialize($VisitorInfo);

?>