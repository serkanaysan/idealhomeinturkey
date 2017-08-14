<?php
ob_start();
session_start();
header("HTTP/1.0 404 Not Found");
require_once('includes/Database.php');
require_once('includes/Functions.php');
$CurrentPage = '404PAGE';
$CurrentLangInfo['id'] = '2';

// Cookie'de dil yoksa veya farklı bir değer taşıyorsa standart dil belirlenecek.
$DefaultLanguage = getSettings('default_language'); // Türkçe ID si.

$DefaultLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE id = :id");
$DefaultLangInfo->execute(array(':id'=>$DefaultLanguage));
$DefaultLangInfo = $DefaultLangInfo->fetch(PDO::FETCH_ASSOC);

require_once('includes/languages/'.$DefaultLangInfo['lang_id'].'.php');

// Eğer newlang var ise ve şuanki dil idsine eşit değil ise.
if(isset($_GET['newlang'])){
	
	//Newlang mevcut, dil için url değiştirilecek.
	// Newlang da ki id ve status kontrolü. YAPILACAK
	$NewLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE id = :id AND status = :status");
	$NewLangInfo->execute(array(':id'=>(int)addslashes($_GET['newlang']),':status'=>1));
	
	if($NewLangInfo->rowCount() > 0){
	
		$NewLangInfo = $NewLangInfo->fetch(PDO::FETCH_ASSOC);
			
			// URL Değiştir
			if($NewLangInfo['lang_id']=='ar-sa'){
			
				header("Location:".$MAIN_URL."/ar-sa/");
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$MAIN_URL."/en-us/");
				exit;
				
			}else{
				
				header("Location:".$MAIN_URL);
				exit;
				
			}
		
	}else{ // Bu dil yok, istenirse 404 yönlendirilebilir else ile.
	
		header("Location:".$MAIN_URL);
		exit;
	
	}
	
}

// 404 Sayfasına Özel
$DilSef = 'en-us';

?>
<!DOCTYPE html>
<html lang="tr">
<head>

    <!-- Meta -->
    <meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow"/>
	<title><?php
	if($DilSef=='ar-sa'){
		
		echo '404 Page Not Found | Ideal Home in Turkey';
	
	}else{
	
		echo '404 Page Not Found | Ideal Home in Turkey';
	
	}
	?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#e51c25"/>
	<meta name="yandex-verification" content="ef4331785146da1a" />
	<meta name="p:domain_verify" content="a817793898e51a3ba5d427a98eb5839f"/>
	<meta name="dns-prefix" content="//ajax.googleapis.com"/>
	<meta name="dns-prefix" content="//maxcdn.bootstrapcdn.com"/>
	<meta name="dns-prefix" content="//www.googleadservices.com"/>
	<meta name="dns-prefix" content="//cdn.sendpulse.com"/>
	<link rel="icon" type="image/x-icon" href="/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="/manifest.json">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <link href="<?php echo $MAIN_URL; ?>/assets/css/combined.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		.not-found-text{
			margin-top: 8%;
			margin-bottom: 25px;
			text-align: center;
			text-transform: uppercase;
			color: #ffffff;
			font-size: 55px;
			font-weight: bolder;
			text-shadow: 2px 3px 5px #111;
		}
		#page{
			margin: 0;
			background: url(http://4.bp.blogspot.com/-KKShsbSUmgA/VJVC44l1RkI/AAAAAAAADEc/_L6SMb1HYzI/s1600/kapadokya-rooteto3.jpg) no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			padding-bottom: 10%;
		}
		#footer{
			margin:0;
		}
		.not-found-bg{
			background-color: #e51b24;
			color: #fff;
			-webkit-box-shadow: 0px 0px 25px 2px rgb(191, 191, 191);
			-moz-box-shadow: 0px 1px 10px 2px rgba(0, 0, 0, 0.1);
			box-shadow: 0px 0px 25px 2px rgb(191, 191, 191);
		}
		.not-found-desc{
			text-align: center;
			display: inline-block;
			width: 100%;
			margin-bottom: 25px;
			font-size: 17px;
			text-shadow: 2px 3px 5px #333;
			color: #fff;
		}
		
		.arrow{
			position: absolute;
			top: 77%;
			left: 63%;
			width: 45px;
			-ms-transform: rotate(-82deg);
			-webkit-transform: rotate(-82deg);
			transform: rotate(40deg);
		}
		
		.btn.focus, .btn:focus, .btn:hover {
			color: #eee;
			text-decoration: none;
		}
		
	</style>
</head>
<body>
<?php require_once("header.php"); ?>
<section id="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="not-found-text">Page Not Found</h1>
				<span class="not-found-desc">Don’t worry, we’re here to guide you back on the right route</span>
				<img src="<?php echo $MAIN_URL; ?>/assets/img/right-down.png" class="arrow" />
				<div style="text-align:center;">
					<a class="btn not-found-bg" href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/';
				
			}
		 
		 ?>"><?php echo $Lang['menu_projeler']; ?></a>
				<a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/contact/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/contact/';
				
			}
		 
		 ?>" class="btn not-found-bg"><?php echo $Lang['menu_iletisim']; ?></a>
				</div>
            </div>
        </div>
    </div>
</section>
<?php require_once("footer.php"); ?>
</body>
</html>