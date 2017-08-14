<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Database.php');
require_once('includes/Functions.php');
require_once('includes/Config.php');

//var_dump(session_id());
//53be4c5c1e6252910e0e91bf7822f850

// Giriş yapılmış ise smsverify.php sayfasına gönder.
if(UserControl(1)){
	
	header("Location:smsverify.php");
	//header("Location:controlpanel.php");
	
}

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<title>Control Panel Login - IdealHomeInTurkey.com</title>
	
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
		if(isset($_POST['doLogin'])){
			
			$formUser = addslashes(htmlspecialchars($_POST['username']));
			$formPass = md5(md5($_POST['password']));
			
			$getUserInfo = $DB->prepare("SELECT * FROM ihit_users WHERE username = :username AND password = :password");
			$getUserInfo->execute(array(':username'=>$formUser,':password'=>$formPass));
			
			// Boş Alan Kontrolü
			if(trim($formUser) == '' OR trim($formPass) == ''){
				
				echo '<script type="text/javascript">$(document).ready(function () { swal("Dikkat", "Lütfen Boş Alan Bırakmayınız", "warning"); });</script>';
				
			// Kullanıcı adı ve Şifre doğruluk kontrolü.
			}elseif($getUserInfo->rowCount() < 1){
				
				echo '<script type="text/javascript">$(document).ready(function () { swal("Dikkat", "Kullanıcı adı veya şifre yanlış.", "warning"); });</script>';
			
			// Eksik yada yanlış bilgi yok ise session ve yönlendirme işlemleri.
			}else{
				
				// Session işlemleri
				//$UserInfo = $getUserInfo->fetch(PDO::FETCH_ASSOC);
				$UniqLoginHash = uniqid().md5('_pamuknettr_ihit_mycp_').strtotime('-354 minute').rand(10000,99999);
				
				// Session login_hash güncelle
				$_SESSION['mycp_login'] = session_id();
				
				// Veritabanında login_hash güncelle
				$updateLoginHash = $DB->prepare("UPDATE ihit_users SET login_hash = :login_hash WHERE username = :username");
				$updateLoginHash->execute(array(':login_hash'=>session_id(),':username'=>$formUser));
				
				// Timeout yada çıkış olduğunda kullanıcı adı hatırlama özelliği için cookie ye kullanıcı adı kayıt et
				setcookie('ihitmycp_username',$formUser,time()+3600*24*365);
				
				// Mesaj ve Yönlendirme
				echo '<script type="text/javascript">$(document).ready(function () { swal("Başarılı", "SMS Doğrulaması İçin Yönlendiriliyorsunuz.", "success"); });</script>';
				//header('Refresh:1;controlpanel.php');
				header('Refresh:1;smsverify.php');
				
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
	
	<form action="index.php" method="post">
		
		<input type="text" name="username" class="form-control"<?php if(isset($_COOKIE['ihitmycp_username'])){ echo ' value="'.addslashes(htmlspecialchars($_COOKIE['ihitmycp_username'])).'" '; }else{ ?>placeholder="Kullanıcı Adı"<?php } ?> /> <br />
		<input type="password" name="password" class="form-control" placeholder="Şifreniz" /> <br />
		<input type="hidden" name="doLogin" class="form-control" value="<?php echo uniqID(); ?>" />
		<a class="form-control" style="background-color: #005dab; color: #fff; border: 0px;width:150px;float:left;" href="sifremiunuttum.php">Şifremi Unuttum</a>
		<input type="submit" class="form-control" style="background-color: #e51b24; color: #fff; border: 0px;width:150px;float:right;" value="Giriş Yap &rsaquo;&rsaquo;" />
	
	</form>
</div>
</div>
</body>
</html>