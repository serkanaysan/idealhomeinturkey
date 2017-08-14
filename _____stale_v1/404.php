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
<!DOCTYPE HTML>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<title>Ideal Home in Turkey | Investment Advisory & Brokerage Company</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="theme-color" content="#e51c25"/>
		<link rel="icon" type="image/x-icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="/manifest.json">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/header.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/footer.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/fix.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Raleway:500,600,700|Montserrat:400,700" rel="stylesheet" type="text/css">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
		<style type="text/css">
		
			.property_listing h4 a{
			
				color:#185271!important;
				font-size:20px;
				margin-top:20px;
			
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
		
			background-color: #0A63AE;
			padding: 10px;
			margin-right: 1px;
			float: left;
			font-size: 14px;
			color: #7e8c99;
			text-transform: uppercase;
			text-decoration: none;
		
		}
		
		#tabs .active{
		
		    background-color: #185271!important;
		
		}
		
		.active .tablink{
		
			color:#FFFFFF;
		
		}
		
		@media only screen and (min-width: 0px) and (max-width: 420px) {
			
			#tabs{
				
				display:inline-block!important;
				
			}
			
			.property-list-tabs {
				
				margin-top: 1px;
				width: 100%!important;
	
			}
			
		}
		
		@media only screen and (min-width: 0px) and (max-width: 500px) {
			
			.listing_wrapper.col-md-12 .property_listing h4 {
				
				padding-left: 20%!important;
				margin-top:5px;
				
			}
			
			.listing_wrapper.col-md-12 .property_location {
				
				margin: 0px 0px 0px 80px!important;
	
			}
			
			.listing_wrapper.col-md-12 .property_listing .listing_details {
				
				padding-left: 15px;
	
			}
			
			.listing_actions {
				top: none!important;
				bottom: 11px!important;
			}
			.listing_unit_price_wrapper {
				
				width: 90%!important;
				position: relative;
			
			}
			
			.listing_unit_price_wrapper {
				
				left: 15px!important;
			
			}
			
		}
		
		
		@media (max-width: 992px){
			#content-wrapper{
				padding-top: 120px!important;
			}
		}
	  
	  </style>
	  
	  <!-- MAIN -->

<div id="content-wrapper" class="site-content-wrapper site-pages" style="height:500px;background-image: url('<?php echo $MAIN_URL; ?>/uploads/404page_bg.jpg');background-size: 100% 100%;">
	<div id="content">
	
		<div class="col-md-12">
			<h1 style="text-align: center; color: #fff; font-size: 55px; font-weight: bolder; text-shadow: 2px 3px 5px #000; margin-top: 4%;">404 NOT FOUND</h1>
			<h1 style="font-size:18px;text-align:center; color: #fff; text-shadow: 2px 3px 5px #000;margin-top:15px;">Don’t worry, we’re here to guide you back on the right route</h1>
			
			<div class="col-md-12" style="margin: 5% auto; text-align: center;">
			
				<a class="btn" style="color:#fff;background-color: #e72e36; border-color: #e72e36;" href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/"><?php echo $Lang['menu_anasayfa']; ?></a>
				<a class="btn" style="color:#fff;background-color: #e72e36; border-color: #e72e36;" href="<?php
		 
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
		 
		 ?>" style="background-color: #e72e36; border-color: #e72e36;color:#fff;" class="btn"><?php echo $Lang['menu_iletisim']; ?></a>
			
			</div>
				
		</div>

	</div>
</div>
</div>
<?php require_once("footer.php"); ?>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/scripts.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#tabs').tab();
    });
	
	$('#tabAll').on('click',function(){
	  $('#tabAll').parent().addClass('active');  
	  $('.tab-pane').addClass('active in');  
	  $('[data-toggle="tab"]').parent().removeClass('active');
	});
</script>
</body>
</html>