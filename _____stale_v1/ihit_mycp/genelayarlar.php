<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Config.php');
require_once('includes/Database.php');
require_once('includes/Functions.php');

// Admin yetki kontrolü
UserControl();
// Role kontrolü
if(userInfo('role_settings')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

// Ayarları veritabanından çekelim

$Settings = $DB->query("SELECT * FROM ihit_settings");
$Settings = $Settings->fetch(PDO::FETCH_ASSOC);

// Yeni ayarları güncelle.

if(isset($_POST['editSettings'])){
	
	// Gelen değerleri alalım ve süzgeçten geçirelim.
	$showBlog = (int)$_POST['showblog'];
	$map_lat = addslashes(htmlspecialchars($_POST['map_lat']));
	$map_lng = addslashes(htmlspecialchars($_POST['map_lng']));
	$footer_text = addslashes(htmlspecialchars($_POST['footer_text']));
	$facebook_username = addslashes(htmlspecialchars($_POST['facebook_username']));
	$twitter_username = addslashes(htmlspecialchars($_POST['twitter_username']));
	$instagram_username = addslashes(htmlspecialchars($_POST['instagram_username']));
	$youtube_username = addslashes(htmlspecialchars($_POST['youtube_username']));
	$pinterest_username = addslashes(htmlspecialchars($_POST['pinterest_username']));
	$linkedin_username = addslashes(htmlspecialchars($_POST['linkedin_username']));
	$googleplus_username = addslashes(htmlspecialchars($_POST['googleplus_username']));
	$default_language = addslashes(htmlspecialchars($_POST['default_language']));
	$callbackhunter = addslashes(htmlspecialchars($_POST['callbackhunter']));
	$chatra = addslashes(htmlspecialchars($_POST['chatra']));
	$footer_phone = addslashes(htmlspecialchars($_POST['footer_phone']));
	$header_phone = addslashes(htmlspecialchars($_POST['header_phone']));
	$footer_email = addslashes(htmlspecialchars($_POST['footer_email']));
	$footer_address = addslashes(htmlspecialchars($_POST['footer_address']));
	
	// Güncelleme işlemleri
	$updateSettings = $DB->prepare("UPDATE ihit_settings SET callbackhunter = :callbackhunter, chatra = :chatra, default_language = :default_language, showblog = :showblog, map_lat = :map_lat, map_lng = :map_lng, footer_text = :footer_text, facebook_username = :facebook_username, twitter_username = :twitter_username, footer_phone = :footer_phone, footer_email = :footer_email, footer_address = :footer_address, header_phone = :header_phone, instagram_username = :instagram_username, youtube_username = :youtube_username, pinterest_username = :pinterest_username, linkedin_username = :linkedin_username, googleplus_username = :googleplus_username");
	$updateSettings->execute(array(
	
		'callbackhunter' => $callbackhunter,
		'chatra' => $chatra,
		'default_language' => $default_language,
		'showblog' => $showBlog,
		'map_lat' => $map_lat,
		'map_lng' => $map_lng,
		'footer_text' => $footer_text,
		'facebook_username' => $facebook_username,
		'twitter_username' => $twitter_username,
		'footer_address' => $footer_address,
		'footer_phone' => $footer_phone,
		'header_phone' => $header_phone,
		'footer_email' => $footer_email,
		'instagram_username' => $instagram_username,
		'youtube_username' => $youtube_username,
		'pinterest_username' => $pinterest_username,
		'googleplus_username' => $googleplus_username,
		'linkedin_username' => $linkedin_username
	
	));
	
	if($updateSettings->rowCount() > 0){
		
		header("Location:genelayarlar.php?success=true");
		exit;
		
	}else{
		
		header("Location:genelayarlar.php?error=true");
		exit;
		
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
                                <h4 class="title">Genel Ayarları Düzenle 
										<div class="buttons" style="float:right;">
											<a href="anasayfaprojeleri.php" class="btn btn-success">Anasayfa Projeleri</a>
											<a href="menuyonetimi.php" class="btn btn-success">Menü</a>
											<a href="footerlinkleri.php" class="btn btn-success">Footer Link</a>
											<a href="hizmetlerimiz.php" class="btn btn-success">Hizmetlerimiz</a>
											<a href="tablar.php" class="btn btn-success">Tablar</a>
										</div>
										</h4>
                                <p class="category"></p>
                            </div>
							
                            <div class="content">
							
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Ayarlar başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Ayarlar düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
							<form method="post" enctype="multipart/form-data" action="genelayarlar.php">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Blog</label>
											<select name="showblog" class="form-control border-input">
												<option value="0"<?php if($Settings['showblog']==0){ echo ' SELECTED'; } ?>>Kapalı</option>
												<option value="1"<?php if($Settings['showblog']==1){ echo ' SELECTED'; } ?>>Açık</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Standart Dil</label>
											<select name="default_language" class="form-control border-input">
										<?php
											$getLanguages = $DB->query("SELECT * FROM ihit_languages ORDER BY id ASC");
										
											foreach($getLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){
										?>
												<option value="<?php echo $Language['id']; ?>"<?php if($Settings['default_language']==$Language['id']){ echo ' SELECTED'; } ?>><?php echo $Language['lang_name']; ?></option>
										<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Callbackhunter</label>
											<select name="callbackhunter" class="form-control border-input">
												<option value="0"<?php if($Settings['callbackhunter']==0){ echo ' SELECTED'; } ?>>Kapalı</option>
												<option value="1"<?php if($Settings['callbackhunter']==1){ echo ' SELECTED'; } ?>>Açık</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Chatra</label>
											<select name="chatra" class="form-control border-input">
												<option value="0"<?php if($Settings['chatra']==0){ echo ' SELECTED'; } ?>>Kapalı</option>
												<option value="1"<?php if($Settings['chatra']==1){ echo ' SELECTED'; } ?>>Açık</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Map Lat</label>
											<input type="text" name="map_lat" class="form-control border-input" value="<?php echo $Settings['map_lat']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Map Lng</label>
											<input type="text" name="map_lng" class="form-control border-input" value="<?php echo $Settings['map_lng']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Header Telefon Numarası</label>
											<input type="text" name="header_phone" class="form-control border-input" value="<?php echo $Settings['header_phone']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Footer Telif Hakkı Yazısı</label>
											<input type="text" name="footer_text" class="form-control border-input" value="<?php echo $Settings['footer_text']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Footer Telefon Numarası</label>
											<input type="text" name="footer_phone" class="form-control border-input" value="<?php echo $Settings['footer_phone']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Footer E-Posta</label>
											<input type="text" name="footer_email" class="form-control border-input" value="<?php echo $Settings['footer_email']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Footer Adres</label>
											<input type="text" name="footer_address" class="form-control border-input" value="<?php echo $Settings['footer_address']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Facebook Username</label>
											<input type="text" name="facebook_username" class="form-control border-input" value="<?php echo $Settings['facebook_username']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Twitter Username</label>
											<input type="text" name="twitter_username" class="form-control border-input" value="<?php echo $Settings['twitter_username']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Instagram Username</label>
											<input type="text" name="instagram_username" class="form-control border-input" value="<?php echo $Settings['instagram_username']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Youtube Username</label>
											<input type="text" name="youtube_username" class="form-control border-input" value="<?php echo $Settings['youtube_username']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Pinterest Username</label>
											<input type="text" name="pinterest_username" class="form-control border-input" value="<?php echo $Settings['pinterest_username']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Linkedin Username</label>
											<input type="text" name="linkedin_username" class="form-control border-input" value="<?php echo $Settings['linkedin_username']; ?>">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Google Plus Username</label>
											<input type="text" name="googleplus_username" class="form-control border-input" value="<?php echo $Settings['googleplus_username']; ?>">
										</div>
									</div>
									
									<input type="hidden" name="editSettings" value="<?php echo uniqId(); ?>" />
									
									<div class="text-center col-md-12">
										<button type="submit" class="btn btn-info btn-fill btn-wd">Ayarları Düzenle</button>
									</div>
									<div class="clearfix"></div>
								</div>
							</form>
                        </div>
						
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require_once('footer.php'); ?>