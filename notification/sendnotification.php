<?php
date_default_timezone_set('Europe/Istanbul');
function SaveLog($Text = NULL){

	$LogFile = 'notification_logs.txt';

	// Eğer log dosyası bulunursa yazma işlemi yapıyoruz.
	if(file_exists($LogFile)){
		
		$OpenLogFile = fopen($LogFile,'a+');
		fwrite($OpenLogFile,$Text." | ".date('d/m/Y H:i:s',strtotime('Now'))."\n");
		fclose($OpenLogFile);

	}

}

if(trim($_GET['veriables'])!=''){
	SaveLog('Notification system successfull executed.');
	
	$Veriables = unserialize(base64_decode($_GET['veriables']));

	// SMS GÖNDERİMİ
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
	//5326058044,5070701508
	$xml = '<SMS>'.
				'<oturum>'.
					'<kullanici>8506801088</kullanici>'.
					'<sifre>alfatoshiba</sifre>'.
				'</oturum>'.
				'<mesaj>'.
					'<baslik>IDEAL HOME</baslik>'.
					'<metin>Yeni İletişim Formu. '.date('d/m/Y H:i:s',strtotime('Now')).'</metin>'.
					'<alicilar>5326058044</alicilar>'.
				'</mesaj>'.
				'<karaliste>kendi</karaliste>'.
				'<izin_link>false</izin_link>'.
				'<izin_telefon>false</izin_telefon>'.
			'</SMS>';

	$SMSLog = sendRequest('http://www.dakiksms.com/api/xml_api.php', $xml);
	SaveLog($SMSLog);

	require 'PHPMailerAutoload.php';

	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output
	$mail->isSMTP();                                 // Set mailer to use SMTP
	$mail->CharSet = "UTF-8";
	$mail->Host = 'smtp.yandex.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'form.idealhomeinturkey@yandex.com';                 // SMTP username
	$mail->Password = 'Idealhome2468';                           // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;                                    // TCP port to connect to

	$mail->setFrom('form.idealhomeinturkey@yandex.com', 'Form Bildirimi');
	// $mail->addAddress('farukpam@gmail.com');               // Name is optional
	$mail->addAddress('yigit.ertem@idealhomeinturkey.com');  // Name is optional
	//$mail->addAddress('jimmy.sami@idealhomeinturkey.com');   // Name is optional

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Yeni İletişim Formu - '.date('d/m/Y H:i:s',strtotime('Now'));
	$mail->Body    = 'Yeni İletişim Formu. [ '.date('d/m/Y H:i:s',strtotime('Now')).'  ] <br /><br />
						Full Name : '.$Veriables['fullname'].' <br />
						E-Mail : '.$Veriables['email'].' <br />
						Phone : '.$Veriables['phone'].' <br />
						Message : '.$Veriables['message'].' <br />
						Form Name : '.$Veriables['formname'].' <br />
						Project Name : '.$Veriables['projectname'].' <br />
						Created At : '.date('d/m/Y H:i:s',strtotime('Now')).' <br />
	';
	if($mail->send()){
		
		SaveLog('email successful sended.');
		
	}else{
		
		SaveLog('EMAIL ERROR! | ' . $mail->ErrorInfo);
		
	}
	
}else{
	SaveLog('Notification system executed, but veriables not found. >> '.$_GET['veriables']);
}
?>