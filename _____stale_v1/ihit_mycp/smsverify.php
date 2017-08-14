<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Database.php');
require_once('includes/Functions.php');
require_once('includes/Config.php');

// Giriş Yapılmamış ise index.php yönlendir.
if(UserControl(1)==false){
	
	header("Location:index.php");
	exit;
	
}

// SMS Doğrulaması yapılmış ise controlpanel.php ye yönlendir.
if(SmsVerifyControl(1)){
	
	header("Location:controlpanel.php");
	exit;
	
}

if(isset($_GET['sendnewsms'])){

	// SMS Gönder
	sendSmsVerifyPin();

}


?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<title>MyCP</title>
	
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
		if(isset($_POST['smsLogin'])){
			
			$smspass = addslashes(htmlspecialchars($_POST['smspass']));
			
			$getSmsInfo = $DB->prepare("SELECT * FROM ihit_smspass WHERE smspass = :smspass AND login_hash = :login_hash ORDER BY id DESC LIMIT 0,1");
			$getSmsInfo->execute(array(':smspass'=>$smspass,':login_hash'=>userInfo('login_hash')));
			
			// Boş Alan Kontrolü
			if(trim($smspass) == ''){
				
				echo '<script type="text/javascript">$(document).ready(function () { swal("Dikkat", "Lütfen Boş Alan Bırakmayınız.", "warning"); });</script>';
				
			// SMS Şifresi Doğruluk Kontrolü.
			}elseif($getSmsInfo->rowCount() < 1){
				
				echo '<script type="text/javascript">$(document).ready(function () { swal("Dikkat", "Girdiğiniz SMS Şifresi Geçersiz.", "warning"); });</script>';
			
			// Eksik yada yanlış bilgi yok ise session ve yönlendirme işlemleri.
			}else{
				
				$SMSInfo = $getSmsInfo->fetch(PDO::FETCH_ASSOC);
				
				// SMS durumunu '1'e güncelle
				$UpdateSMSStatus = $DB->prepare("UPDATE ihit_smspass SET status = :status WHERE login_hash = :login_hash AND smspass = :smspass");
				$UpdateSMSStatus->execute(array(':login_hash'=>userInfo('login_hash'),':smspass'=>$SMSInfo['smspass'],':status'=>1));
				
				// Mesaj ve Yönlendirme
				echo '<script type="text/javascript">$(document).ready(function () { swal("Hoşgeldiniz", "SMS Doğrulaması Başarılı, Yönlendiriliyorsunuz.", "success"); });</script>';
				header('Refresh:1;controlpanel.php');
				
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
	
	<form action="smsverify.php" method="post">
	
		<input type="number" name="smspass" class="form-control" placeholder="SMS Şifresi" /> <br />
		<input type="hidden" name="smsLogin" class="form-control" value="<?php echo uniqID(); ?>" />
		<a href="?sendnewsms=true"><span class="form-control" style="background-color: #607D8B; color: #fff; border: 0px;width:137px;float:left;">Yeni SMS Gönder</span></a>
		<input type="submit" class="form-control" style="background-color: #e51b24; color: #fff; border: 0px;width:105px;float:right;" value="Doğrula &rsaquo;&rsaquo;" />
	
	</form>
</div>
</div>
</body>
</html>