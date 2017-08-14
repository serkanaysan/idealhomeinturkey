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

$UserID = (int)$_GET['ID'];

// Kullanıcı bilgileri
$UserInfo = $DB->prepare("SELECT * FROM ihit_users WHERE id = :id");
$UserInfo->execute(array(':id'=>$UserID));

if($UserInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); } // basit güvenlik önlemi, diğer düzenleme sayfalarına da uygulandı.)

$UserInfo = $UserInfo->fetch(PDO::FETCH_ASSOC);

if($UserInfo['id'] == 1 AND userInfo('id') != 1){ die("Root hesabını düzenleme yetkiniz bulunmamakta."); }

if(isset($_POST['editUser'])){
	
	$Fullname = $_POST['fullname'];
	$Username = $_POST['username'];
	$Password = $_POST['password'];
	$phone = $_POST['phone'];
	
	if(strlen($Fullname)<3){
		
			header("Location:kullaniciduzenle.php?error=short&shortname=fullname&ID=".$UserID);
			exit;
	
	}elseif(strlen($Username)<3){
		
			header("Location:kullaniciduzenle.php?error=short&shortname=username&ID=".$UserID);
			exit;
		
	}elseif(trim($Password)!='' AND strlen($Password)<8){
		
			header("Location:kullaniciduzenle.php?error=short&shortname=password&ID=".$UserID);
			exit;
			
	}elseif(strlen($phone)!=10){
		
			header("Location:kullaniciduzenle.php?error=short&shortname=phone&ID=".$UserID);
			exit;
			
	}else{
	
		if(trim($Password)==''){
			
			$Password = $UserInfo['password'];
			
		}else{
			
			$Password = md5(md5($_POST['password']));
			
		}
		
		// Gelen bilgileri güncelliyoruz
		$userUpdate = $DB->prepare("UPDATE ihit_users SET fullname = :fullname, username = :username, password = :password, phone = :phone WHERE id = :id");
		
		$userUpdate->execute(
		
			array(
			
				':id' => $UserID,
				':fullname' => $Fullname,
				':username' => $Username,
				':password' => $Password,
				':phone' => $phone
			
			)
		
		);
		
		if($userUpdate->rowCount() > 0){
		
			header("Location:kullaniciduzenle.php?success=true&ID=".$UserID);
			exit;
			
		}else{

			header("Location:kullaniciduzenle.php?error=true&ID=".$UserID);
			exit;

		}
		
	} // Karakter kontrolü
	
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
	
    <!--  CSS for Demo Purpose, don't include it in your project -->
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
                                <h4 class="title">Kullanıcı Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Kullanıcı başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error']) AND !isset($_GET['shortname'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Kullanıcı düzenlenemedi.</span>
								</div>
							
							<?php }elseif(isset($_GET['error']) AND isset($_GET['shortname'])){
							
									$shortname = $_GET['shortname'];
								
									if($shortname=='fullname'){
									
										echo '
											<div class="alert alert-danger">
												<span><b> Başarısız - </b> "Ad Soyad" en az 8 karakter olmalı.</span>
											</div>
										';
										
									}elseif($shortname=='username'){
									
										echo '
											<div class="alert alert-danger">
												<span><b> Başarısız - </b> "Kullanıcı Adı" en az 3 karakter olmalı.</span>
											</div>
										';
										
									}elseif($shortname=='password'){
									
										echo '
											<div class="alert alert-danger">
												<span><b> Başarısız - </b> "Şifre" en az 8 karakter olmalı.</span>
											</div>
										';
										
									}elseif($shortname=='phone'){
									
										echo '
											<div class="alert alert-danger">
												<span><b> Başarısız - </b> "Telefon" başında 0 olmadan tam 10 karakter olmalı.</span>
											</div>
										';
										
									}
									
								}
								
								?>
							
                                <form method="post" enctype="multipart/form-data" action="kullaniciduzenle.php?ID=<?php echo $UserID; ?>">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Ad Soyad</label>
                                                <input type="text" name="fullname" class="form-control border-input" value="<?php echo $UserInfo['fullname']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Telefon Numarası ( başında 0 olmadan tam 10 karakter )</label>
                                                <input type="text" name="phone" class="form-control border-input" value="<?php echo $UserInfo['phone']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Kullanıcı Adı</label>
                                                <input type="text" name="username" class="form-control border-input" value="<?php echo $UserInfo['username']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                                                <input type="text" name="password" class="form-control border-input" value="">
                                            </div>
                                        </div>
										<input type="hidden" name="editUser" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Kullanıcı Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>