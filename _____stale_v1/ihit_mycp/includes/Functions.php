<?php

	//var_dump(session_id());
	
	// Kullanıcının son aktivitesini güncelliyoruz.
	$updateUserLastActivityTime = $DB->prepare("UPDATE ihit_users SET last_login_time = :last_login_time WHERE id = :id");
	$updateUserLastActivityTime->execute(array(':last_login_time'=>strtotime('Now'),':id'=>userInfo('id')));
	
	function sendSmsVerifyPin(){
		
		global $DB;
		
		// Yeni Pin Oluşturalım 6 Karakterli
		$SMSPin = rand(100000,999999);
		
		// Veritabanına login_hash ve sms pini ekleyelim.
		$insertSmsPin = $DB->prepare("INSERT INTO ihit_smspass (login_hash,smspass,status) VALUES (:login_hash,:smspass,:status)");
		$insertSmsPin->execute(
			array(
			
				':login_hash' => userInfo('login_hash'),
				':smspass' => $SMSPin,
				':status' => 0
			
			)
		);
		
		// SMS Gönderme
		function sendRequest($site_name,$send_xml,$header_type=array('Content-Type: text/xml'))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$site_name);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$send_xml);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header_type);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);

			$result = curl_exec($ch);

			return $result;
		}
		
		$xml = '<SMS>'.
				'<oturum>'.
					'<kullanici>8506801088</kullanici>'.
					'<sifre>alfatoshiba</sifre>'.
				'</oturum>'.
				'<mesaj>'.
					'<baslik>IDEAL HOME</baslik>'.
					'<metin>SMS Dogrulama Kodunuz : '.$SMSPin.' - Olusturulma Tarihi : '.date('d/m/Y H:i:s',strtotime('Now')).'</metin>'.
					'<alicilar>'.userInfo('phone').'</alicilar>'.
				'</mesaj>'.
				'<karaliste>kendi</karaliste>'.
				'<izin_link>false</izin_link>'.
				'<izin_telefon>false</izin_telefon>'.
			'</SMS>';

		sendRequest('http://www.dakiksms.com/api/xml_api.php', $xml);
		
	}

	function SmsVerifyControl($return=0){
		
		global $DB;
		
		//$LoginHash = addslashes(htmlspecialchars(strip_tags($_SESSION['mycp_login'])));
		$LoginHash = session_id();
		
		// SMS Bilgileri
		$SMSInfo = $DB->prepare("SELECT * FROM ihit_smspass WHERE login_hash = :login_hash ORDER BY id DESC LIMIT 0,1");
		$SMSInfo->execute(array(':login_hash'=>$LoginHash));
		
		// Eğer smspass tablosunda şuan ki login_hash bulunmazsa sms gönderiyoruz.
		if($SMSInfo->rowCount() < 1){
			
			// Eğer smsverify.php haricinde bu fonksiyon çalıştırılırsa sms gönderme.
			if($return==1){
				// SMS Gönder
				sendSmsVerifyPin();
			}
			
			return false;
			
		}else{
			
			// Bu login_hash ile ilgili bir kayıt var ise durumunu sorgula.
			$SMSInfo = $SMSInfo->fetch(PDO::FETCH_ASSOC);
			if($SMSInfo['status'] == 0){
				
				return false;
				
			}else{
				
				return true;
				// Durumu true ise zaten kendi sayfasında yönlendirme yapıyoruz.
				
			}
		}
		
	}
	
	function UserControl($return=0)
	{
		
		global $DB;
		
		//$LoginHash = addslashes(htmlspecialchars(strip_tags($_SESSION['mycp_login'])));
		$LoginHash = session_id();
		
		// Eğer login_hash boş ise veritabanında da boş bir kayıt var ise giriş yapıldı gözüküyor.
		// Bu yüzden sessionda ki login_hash boş olması durumunda random bir değer atıyoruz.
		if(trim($LoginHash)==''){
			
			$LoginHash = uniqid().rand(111111,9999999);
			
		}
		
		// Kullanıcı Bilgileri
		$userInfo = $DB->prepare("SELECT * FROM ihit_users WHERE login_hash = :login_hash");
		$userInfo->execute(array(':login_hash'=>$LoginHash));
		
		if($userInfo->rowCount() < 1 ){
			
			if($return==1){
			
				return false;
			
			}else{
				
				header("Location:index.php");
				exit;
				
			}
			
		}else{
			
			return true;
			
			/*
			// Eğer return 1 ise yani sms verify, index sayfasında vs ise sms kontrolü yapmıyoruz.
			// direkt olarak girişin başarılı olduğunu döndürüyoruz.
			if($return==1){
				
				return true;
				
			}else{
			
				// SMS Doğrulaması Yapılmış mı?
				if(SmsVerifyControl()){
					
					// Evet yapılmış
					return true;
					
				}else{
				
					// Hayır yapılmamış
					header("Location:smsverify.php");
				
				}
			
			}
			*/
		}
		
	}
	
	function getLanguageInfo($LanguageID)
	{
	
		global $DB;
		
		$LanguageInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE id = :id");
		$LanguageInfo->execute(array(':id'=>$LanguageID));
		$LanguageInfo = $LanguageInfo->fetch(PDO::FETCH_ASSOC);
		
			return $LanguageInfo;
	
	}
	
	function getMenuInfo($MenuID)
	{
	
		global $DB;
		
		$getMenuName = $DB->prepare("SELECT * FROM ihit_menu WHERE id = :id");
		$getMenuName->execute(array(':id'=>$MenuID));
		$getMenuName = $getMenuName->fetch(PDO::FETCH_ASSOC);
		
			return $getMenuName;
	
	}
	
	function getLanguageName($LanguageID)
	{
		
		$LanguageInfo = getLanguageInfo($LanguageID);
		
		return $LanguageInfo['lang_name'];
		
	}
	
	function userInfo($column)
	{
		
		global $DB;
		
		$userInfo = $DB->prepare("SELECT * FROM ihit_users WHERE login_hash = :login_hash");
		$userInfo->execute(array(':login_hash'=>addslashes(htmlspecialchars(strip_tags(session_id())))));
		//$userInfo->execute(array(':login_hash'=>addslashes(htmlspecialchars(strip_tags($_SESSION['mycp_login'])))));
		$userInfo = $userInfo->fetch(PDO::FETCH_ASSOC);
		
		return $userInfo[$column];
		
	}
	
	function permalink($string)
	{
		$find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
		$replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
		$string = strtolower(str_replace($find, $replace, $string));
		$string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
		$string = trim(preg_replace('/\s+/', ' ', $string));
		$string = str_replace(' ', '-', $string);
		return $string;
	}
	
	function array2csv(array &$array)
	{
	   if (count($array) == 0) {
		 return null;
	   }
	   ob_start();
	   $df = fopen("php://output", 'w');
	   fputcsv($df, array_keys(reset($array)));
	   foreach ($array as $row) {
		  fputcsv($df, $row);
	   }
	   fclose($df);
	   return ob_get_clean();
	}
	
	function download_send_headers($filename)
	{
		// disable caching
		$now = gmdate("D, d M Y H:i:s");
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");

		// force download  
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		// disposition / encoding on response body
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}
	
	function getCityInfo($city_id,$Column){
		
		global $DB;
		
		$getCityInfo = $DB->query("SELECT {$Column} FROM ihit_projects_citys WHERE id = '{$city_id}'");
		$getCityInfo = $getCityInfo->fetch(PDO::FETCH_ASSOC);
		
		return $getCityInfo[$Column];
		
	}

?>