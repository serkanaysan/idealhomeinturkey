<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Database.php');
require_once('includes/Functions.php');
require_once('includes/Config.php');

// Admin yetki kontrolü
UserControl();
// Role kontrolü
if(userInfo('role_users')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_GET['KullaniciSil'])){
	
	$userID = (int)$_GET['KullaniciSil'];
	
	if($userID==1){
		
		die("Bu kullanıcıyı (root) silemezsiniz.");
		
	}
	
	// Kullanıcı bilgisi
	$getUser = $DB->query("SELECT * FROM ihit_users WHERE id = '".$userID."'");
	
	if($getUser->rowCount() > 0){
	
		$getUser = $getUser->fetch(PDO::FETCH_ASSOC);
		
		$userID = $getUser['id'];
		
		// Kullanıcı silme işlemi
		$DB->query("DELETE FROM ihit_users WHERE id = '".$userID."'");
		header("Location:kullaniciyonetimi.php");
	
	}else{
		
		die('Bu kullanıcı bulunamadı.');
		
	}
	
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
<?php require_once('sidebar.php'); ?>
    <div class="main-panel">
<?php require_once('header.php'); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Kullanıcı Yönetimi <a style="float:right;text-align:right;" class="various" data-fancybox-type="iframe" href="kullaniciekle.php"><i class="ti-plus"></i> Yeni Kullanıcı Ekle</a></h4>
                                <p class="category">Kontrol paneline erişim sağlayabililen kullanıcı/yöneticiler.</p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>Ad Soyad</th>
										<th>Kullanıcı Adı</th>
										<th>Telefon Numarası</th>
										<th>Son Islem Tarihi</th>
                                    </thead>
									   <tbody>
										<?php
										
											$getForms = $DB->query("SELECT * FROM ihit_users ORDER BY last_login_time DESC");
										
											foreach($getForms->fetchAll(PDO::FETCH_ASSOC) as $User){
										?>
										  <tr>
											<td><?php echo $User['fullname']; ?></td>
											<td><?php echo $User['username']; ?></td>
											<td><?php echo $User['phone']; ?></td>
											<td><?php echo date('d/m/Y H:i:s',$User['last_login_time']); ?></td>
											<td>
											<a href="kullaniciyonetimi.php?KullaniciSil=<?php echo $User['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')">
												<i class="ti-trash"></i>
											</a>
											<a class="various" data-fancybox-type="iframe" href="kullaniciduzenle.php?ID=<?php echo $User['id']; ?>">
												<i class="ti-pencil"></i>
											</a>
											<?php
												if(userInfo('id') == 1){
											?>
											<a class="various" data-fancybox-type="iframe" href="kullaniciizinleri.php?ID=<?php echo $User['id']; ?>">
												<i class="ti-lock"></i>
											</a>
											<?php } ?>
											</td>
										  </tr>
										<?php } ?>
										</tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require_once('footer.php'); ?>