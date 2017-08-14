<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Database.php');
require_once('includes/Functions.php');
require_once('includes/Config.php');

// Giriş yapılmış ise smsverify.php sayfasına gönder.
if(UserControl(1)){
	
	header("Location:smsverify.php");
	
}

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<title>Şifremi Unuttum - IdealHomeInTurkey.com</title>
	
	<!-- Minified jQuery -->
	<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
	<!-- SweetAlert Files -->
	<script src="assets/sweetalert.min.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/sweetalert.css">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	
	<?php
		
		// Form Kontrolü
		if(isset($_POST['doForgot'])){
			
			$PhoneNumber = addslashes(htmlspecialchars($_POST['phone']));
			$Username = addslashes(htmlspecialchars($_POST['username']));
			
			// Gelen Telefon numarasını ve kullanıcı adını veritabanında kontrol ediyoruz.
			$UserControl = $DB->prepare("SELECT * FROM ihit_users WHERE username = :username AND phone = :phone");
			$UserControl->execute(array(':username'=>$Username,':phone'=>$PhoneNumber));
			
			// Eğer telefon numarası ve username doğruysa yeni şifre oluşturup,güncelleştirip sms olarak gönderiyoruz ve başarılı mesajı yazdırıyoruz.
			if($UserControl->rowCount() > 0){
				
				// Yeni Şifre
				function randomPassword() {
					$alphabet = "abcdefghijklmnopqrstuwxyz0123456789";
					$pass = array(); //remember to declare $pass as an array
					$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
					for ($i = 0; $i < 8; $i++) {
						$n = rand(0, $alphaLength);
						$pass[] = $alphabet[$n];
					}
					return implode($pass); //turn the array into a string
				}
			
				$NewPass = randomPassword();
				
				// Şifre Güncelle
				$UpdatePassword = $DB->prepare("UPDATE ihit_users SET password = :password WHERE username = :username");
				$UpdatePassword->execute(array(':password'=>md5(md5($NewPass)),':username'=>$Username));
				
				if($UpdatePassword->rowCount() < 1){
					
					$DB->errorInfo();
					exit;
					
				}
				
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
				
				$xml = '<SMS>'.
							'<oturum>'.
								'<kullanici>8506801088</kullanici>'.
								'<sifre>alfatoshiba</sifre>'.
							'</oturum>'.
							'<mesaj>'.
								'<baslik>IDEAL HOME</baslik>'.
								'<metin>Yeni Şifreniz : '.$NewPass.'</metin>'.
								'<alicilar>'.$PhoneNumber.'</alicilar>'.
							'</mesaj>'.
							'<karaliste>kendi</karaliste>'.
							'<izin_link>false</izin_link>'.
							'<izin_telefon>false</izin_telefon>'.
						'</SMS>';

				sendRequest('http://www.dakiksms.com/api/xml_api.php', $xml);
				
				echo '<script type="text/javascript">$(document).ready(function () { swal("Başarılı", "Yeni Şifreniz SMS ile Gönderildi.", "success"); });</script>';
				header("Refresh:3;index.php");
				
			}else{
				
				echo '<script type="text/javascript">$(document).ready(function () { swal("Dikkat", "Kullanıcı adı veya telefon numarası yanlış.", "warning"); });</script>';
				
			}
			
			
		}
	
	?>
	
	<style type="text/css">
	
		*{
			font-family: 'Open Sans', sans-serif;
		}
	
		body {
			
			padding-top: 5rem;
			background-color:#fafafa;
		  
		}
		
		.ortala{
			
			float:none;
			margin:0 auto;
			
		}
		
		.logo{
			
			width: 250px;
			height: 100px;
			background-image: url('assets/img/logo_header.png');
			background-repeat: no-repeat;
			background-size: contain;
			margin: 15px auto;
			
		}
		
		.form-control{
			
			border-radius:0px!important;
			
		}
	
	</style>
	
	
</head>
<body>
<div class="container">

	<div class="logo">&nbsp;</div>
	
<div class="col-md-5 ortala">

	<!-- <h1 style="text-align:center;">MyCp Login</h1>-->
	
	<form action="sifremiunuttum.php" method="post">
		
		<input type="text" name="username" class="form-control" placeholder="Kullanıcı Adınız" /> <br />
		<input type="text" name="phone" class="form-control" placeholder="Telefon Numaranız" /> <br />
		<input type="hidden" name="doForgot" class="form-control" value="<?php echo uniqID(); ?>" />
		<input type="submit" class="form-control" style="background-color: #e51b24; color: #fff; border: 0px;width:150px;float:right;" value="Sıfırla &rsaquo;&rsaquo;" />
	
	</form>
</div>
</div>
</body>
</html>