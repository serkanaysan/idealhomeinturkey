<?php
ob_start();
session_start();
header("Content-Type:text/html; Charset=UTF-8;");
require_once('includes/Database.php');
require_once('includes/Functions.php');

// Sayfa Sef URL
$PageSef = addslashes(htmlspecialchars(strip_tags($_GET['sef'])));

// Sayfa var yok kontrolü
$PageInfo = $DB->prepare("SELECT * FROM ihit_pages WHERE sef = :sef");
$PageInfo->execute(array(':sef'=>$PageSef));

if($PageInfo->rowCount() < 1){ header("Location:".$MAIN_URL."/404"); exit; }

// Sayfa Bilgisi
$PageInfo = $PageInfo->fetch(PDO::FETCH_ASSOC);

// Cookie'de dil yoksa veya farklı bir değer taşıyorsa standart dil belirlenecek.
$DefaultLanguage = getSettings('default_language'); // Türkçe ID si.

$DefaultLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE id = :id");
$DefaultLangInfo->execute(array(':id'=>$DefaultLanguage));
$DefaultLangInfo = $DefaultLangInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_GET['lang_sef'])) {
	
	$DilSef = addslashes(htmlspecialchars(strip_tags($_GET['lang_sef'])));
	
	// Dil Sef Kontrolü
	$CheckLang = $DB->prepare("SELECT * FROM ihit_languages WHERE lang_id = :sef");
	$CheckLang->execute(array(':sef'=>$DilSef));
	
	if($CheckLang->rowCount() < 1){
	
		// Dil sefi bulunamadı veritabanında
		$DilSef = $DefaultLangInfo['lang_id'];
		
	}
	
}else{
	
	$DilSef = $DefaultLangInfo['lang_id'];
	
	// Ve sayfayı yeniden yüklüyoruz
	header("Location:".$MAIN_URL.'/'.$DefaultLangInfo['lang_id']);
	exit;
	
}

require_once('includes/languages/'.$DilSef.'.php');

// Eğer newlang var ise ve şuanki dil idsine eşit değil ise.
if(isset($_GET['newlang'])){
	
	//Newlang mevcut, dil için url değiştirilecek.
	// Newlang da ki id ve status kontrolü. YAPILACAK
	$NewLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE id = :id AND status = :status");
	$NewLangInfo->execute(array(':id'=>(int)addslashes($_GET['newlang']),':status'=>1));
	
	if($NewLangInfo->rowCount() > 0){
	
		$NewLangInfo = $NewLangInfo->fetch(PDO::FETCH_ASSOC);
		
		// Yeni dil id sine göre ve mevcut sayfanın unique id sine göre arama yapıyoruz sayfalar tablosunda
		$newPageInfo = $DB->prepare("SELECT * FROM ihit_pages WHERE uniqueid = :uniqueid AND lang_id = :lang_id");
		$newPageInfo->execute(array(':uniqueid'=>$PageInfo['uniqueid'],':lang_id'=>$NewLangInfo['id']));
		$newPageInfo = $newPageInfo->fetch(PDO::FETCH_ASSOC);
	
		// Eğer dil şuanki dil ile aynı değilse bu işlemi yapıcaz.
		if($DilSefInfo['id']!=$_GET['newlang']){
			
			// URL Değiştir
			if($NewLangInfo['lang_id']=='ar-sa'){
			
				header("Location:".$MAIN_URL."/ar-sa/الصفحات/".$newPageInfo['sef']);
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$MAIN_URL."/en-us/pages/".$newPageInfo['sef']);
				exit;
				
			}else{
				
				header("Location:".$MAIN_URL);
				exit;
				
			}
			
		}else{
			
			// Zaten aynı dil o yüzden newlang metodunu urlden silmek için tekrar aynı sayfaya yönlendiriyoruz.
			
			// URL Değiştir
			if($NewLangInfo['lang_id']=='ar-sa'){
			
				header("Location:".$MAIN_URL."/ar-sa/الصفحات/".$newPageInfo['sef']);
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$MAIN_URL."/en-us/pages/".$newPageInfo['sef']);
				exit;
				
			}else{
				
				header("Location:".$MAIN_URL);
				exit;
				
			}
			
		}
		
	}else{ // Bu dil yok, istenirse 404 yönlendirilebilir else ile.
	
		header("Location:".$MAIN_URL);
		exit;
	
	}
	
}

$CurrentLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE lang_id = :lang_id");
$CurrentLangInfo->execute(array(':lang_id'=>$DilSef));
$CurrentLangInfo = $CurrentLangInfo->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE HTML>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<meta name="theme-color" content="#e51c25"/>
		<title><?php echo $PageInfo['title']; ?> - Ideal Home in Turkey</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="theme-color" content="#e51c25"/>
		<link rel="icon" type="image/x-icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="/manifest.json">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/header_content.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/footer.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/fix.css" rel="stylesheet" type="text/css">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Raleway:500,600,700|Montserrat:400,700' rel='stylesheet' type='text/css'>
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
		<style type="text/css">
		
			.property_listing h4 a{
			
				color:#0A63AE!important;
				font-size:20px;
				margin-top:20px;
			
			}
			
			.main-menu-alt ul {
			
			    margin-top: 0px;
				margin-bottom: 0px;
				
			}
		
		</style>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1584638268519572', {
em: 'insert_email_variable,'
});
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1584638268519572&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->


<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-79564150-1', 'auto');
ga('send', 'pageview');

</script>
<script charset="UTF-8" src="//cdn.sendpulse.com/28edd3380a1c17cf65b137fe96516659/js/push/5cbef200ce4d8d0fb4bdb4a97d1397b5_1.js" async></script>
</head>
   <body>
	<?php require_once("header.php"); ?>
	  <style type="text/css">
	  
		.property-list-tabs a{
		
			color: #EEEEEE;
		
		}
		
		.property-list-tabs{
		
			background-color: #0A63AE; padding: 5px;
			margin-right:15px;
			float:left;
			font-size: 14px;
			font-weight: bold;
			color: #7e8c99;
			text-transform: uppercase;
			text-decoration: none;
		
		}
		
		.active .tablink{
		
			color:#FFFFFF;
		
		}
		
		ul.content-left-menu {
			position:relative;
			background:#fff;
			margin:auto;
			padding:0;
			list-style: none;
			overflow:hidden;
			
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;	
			
			-webkit-box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.1);
			-moz-box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.1);
			box-shadow:  1px 1px 10px rgba(0, 0, 0, 0.1);	
		}

		.content-left-menu li a {
			width: 225px;
			padding: 8px;
			line-height: 19px;
			display: block;
			overflow: hidden;
			position: relative;
			text-decoration: none;
			color: #646565;
			-webkit-transition: all 0.2s linear;
			-moz-transition: all 0.2s linear;
			-o-transition: all 0.2s linear;
			transition: all 0.2s linear;	
		}

		.content-left-menu li a:hover {
			background: #0a63ae;
			color: #fff;
		}

		.content-left-menu li a.ihit {
			padding-left:8px;
			border-left:5px solid #0a63ae;
			width: 100%;
		}


		.content-left-menu li:first-child a:hover, .content-left-menu li:first-child a {
			-webkit-border-radius: 5px 5px 0 0;
			-moz-border-radius: 5px 5px 0 0;
			border-radius: 5px 5px 0 0;
		}

		.content-left-menu li:last-child a:hover, .content-left-menu li:last-child a {
			-webkit-border-radius: 0 0 5px 5px;
			-moz-border-radius: 0 0 5px 5px;
			border-radius: 0 0 5px 5px;
		}

		.content-left-menu li a:hover i {
			color:#ea4f35;
		}

		.content-left-menu i {
			margin-right:15px;
			
			-webkit-transition:all 0.2s linear;
			-moz-transition:all 0.2s linear;
			-o-transition:all 0.2s linear;
			transition:all 0.2s linear;	
		}

		.content-left-menu em {
			font-size: 10px;
			background: #ea4f35;
			padding: 3px 5px;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;		
			font-style: normal;
			color: #fff;
			margin-top: 17px;
			margin-right: 15px;
			line-height: 10px;
			height: 10px;		
			float:right;
		}

		.content-left-menu li.selected a {
			background:#efefef;
		}
	  
		.ihit-content a{
			color: #00aff2;
		}
		
		@media (max-width: 992px){
			.content-title{
				margin-top: 85px!important;
			}
		}
		

		.mobile-menu-main ul{
			margin: 0;
			padding: 0;
			border: 0;
			outline: 0;
			font-weight: inherit;
			font-style: inherit;
			font-size: 100%;
			font-family: inherit;
			vertical-align: baseline;
			list-style: none;
		}
		.mobile-menu-main li{
			margin: 0;
			padding: 0;
			border: 0;
			outline: 0;
			font-weight: inherit;
			font-style: inherit;
			font-size: 100%;
			font-family: inherit;
			vertical-align: baseline;
			list-style: none;
		}
		
		.ihit-content li{
			
			padding:5px;
			
		}
	  
	  </style>
	  
<!-- MAIN -->
<div class="container">

<h1 class="content-title"><?php echo $PageInfo['title']; ?></h1>
<div class="cizgi" style="margin-top:5px;background: #0a63ae none repeat scroll 0 0; bottom: -20px; height: 3px; width: 65px;"></div>

<div class="col-md-12" style="padding:25px;font-size:14px;font-height:100;line-height:normal;">

<img src="<?php echo $MAIN_URL; ?>/uploads/page/<?php echo $PageInfo['image']; ?>" style="border-radius:10px;height:300px;width:100%;" class="page_image" alt="<?php echo $PageInfo['title']; ?>" />

<?php

	if($PageInfo['menu_id']!=0){

?>
	<div class="col-md-3" style="margin-top:20px;padding:0px!important;">
		<ul class="content-left-menu">
		<?php
			$MenuList = $DB->query("SELECT * FROM ihit_menu WHERE menu_id = '".$PageInfo['menu_id']."' AND lang_id = '".$CurrentLangInfo['id']."' ORDER BY rank ASC");
			foreach($MenuList->fetchAll(PDO::FETCH_ASSOC) as $Menu){
		?>
			<li><a class="ihit" href="<?php echo $Menu['url']; ?>"><?php echo $Menu['title']; ?></a></li>
		<?php } ?>
		</ul>
	</div>
<?php } ?>
	<div class="ihit-content<?php if($PageInfo['menu_id']!=0){ echo ' col-md-9'; }else{ echo ' col-md-12'; }?>" style="margin-top:20px;font-size:14px;font-height:100;line-height:normal;">
		<?php echo $PageInfo['content']; ?>
	</div>

</div>
</div>
<!-- MAIN BITIS -->
	  
<?php require_once('footer.php'); ?>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/scripts.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#tabs').tab();
		
		
	 $("#faqs dd").hide();
		$("#faqs dt").click(function () {
			$(this).next("#faqs dd").slideToggle(500);
			$(this).toggleClass("expanded");
		});
		
    });
	
	$('#tabAll').on('click',function(){
	  $('#tabAll').parent().addClass('active');  
	  $('.tab-pane').addClass('active in');  
	  $('[data-toggle="tab"]').parent().removeClass('active');
	});
	
	
</script>
</body>
</html>