<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Config.php');
require_once('includes/Database.php');
require_once('includes/Functions.php');
require_once('includes/upload/class.upload.php');

$Msg = NULL;

// Admin yetki kontrolü
UserControl();
// Role kontrolü
if(userInfo('role_users')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_POST['addNewUser'])){
	
	//Gelen bilgileri veritabanına kayıt edelim
	$fullname = $_POST['fullname'];
	$username = $_POST['username'];
	$phone = $_POST['phone'];
	$password = md5(md5($_POST['password']));
	
	if(strlen($fullname)<3){
	
		$Msg = '
							
			<div class="alert alert-danger">
				<span><b> Dikkat - </b> "Ad Soyad" en az 3 karakter olmalı.</span>
			</div>
		
		';
	
	}elseif(strlen($username)<3){
		
		$Msg = '
		
			<div class="alert alert-danger">
				<span><b> Dikkat - </b> "Kullanıcı Adı" en az 3 karakter olmalı.</span>
			</div>
		
		';
		
	}elseif(strlen($_POST['password'])<8){
		
		$Msg = '
		
			<div class="alert alert-danger">
				<span><b> Dikkat - </b> "Şifre" en az 8 karakter olmalı.</span>
			</div>
		
		';
		
	}elseif(strlen($_POST['phone'])!=10){
		
		$Msg = '
		
			<div class="alert alert-danger">
				<span><b> Dikkat - </b> "Numara" basinda 0 olmadan tam 10 karakterli olmalidir. Örn : 5336667788</span>
			</div>
		
		';
		
	}else{
	
		
		// username kontrol edilecek, eğer var ise msg döndürülecek.
		$UserInfo = $DB->prepare("SELECT * FROM ihit_users WHERE username = :username");
		$UserInfo->execute(array(':username'=>$username));

		if($UserInfo->rowCount() > 0){
			
			$Msg = '
								
				<div class="alert alert-danger">
					<span><b> Başarısız - </b> Bu kullanıcı adı zaten sistemde kayıtlı.</span>
				</div>
			
			';

		}else{
			
			$addUser = $DB->prepare("INSERT INTO ihit_users (fullname,username,password,phone,last_login_time) VALUES (:fullname,:username,:password,:phone,:last_login_time)");
			$addUser->execute(array(
			
				':fullname' => $fullname,
				':username' => $username,
				':password' => $password,
				':phone' => $phone,
				':last_login_time' => strtotime('now')
			
			));
			
			if($addUser->rowCount() > 0){
				
				$Msg = '
									
					<div class="alert alert-success">
						<span><b> Başarılı - </b> Kullanıcı başarıyla eklendi.</span>
					</div>
				
				';
				
			}else{
				
				$Msg = '
									
					<div class="alert alert-danger">
						<span><b> Başarısız - </b> Kullanıcı eklenemedi.</span>
					</div>
				
				';
				
			}
		
		} //username kontrolü
		
	} // Karakter sınırı kontrolü
	
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Kontrol Paneli</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css" rel="stylesheet"/>
	
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />
	
    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">

</head>
<body>

<div class="wrapper">
    <div class="main-panel">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Kullanıcı Ekle</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" action="kullaniciekle.php">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Ad Soyad</label>
                                                <input type="text" name="fullname" class="form-control border-input" placeholder="Ad Soyad">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Telefon Numarası (10 karakter başında 0 olmadan)</label>
                                                <input type="text" name="phone" class="form-control border-input" placeholder="5336667788">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Kullanıcı Adı</label>
                                                <input type="text" name="username" class="form-control border-input" placeholder="Kullanıcı Adı">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Şifre</label>
                                                <input type="text" name="password" class="form-control border-input" placeholder="Şifre">
                                            </div>
                                        </div>
                                        
										<input type="hidden" name="addNewUser" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Kullanıcı Ekle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>