<?php
ob_start();
session_start();
header("Content-Type:text/html; Charset=UTF-8;");
require_once('includes/Database.php');
require_once('includes/Functions.php');
require_once 'includes/Mobile_Detect.php';
$detect = new Mobile_Detect;

$ProjectID = addslashes(htmlspecialchars(strip_tags($_GET['project_id'])));

$DefaultLanguage = getSettings('default_language');

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
	
}

require_once('includes/languages/'.$DilSef.'.php');

// Şehir Kontrolü
if(!isset($_GET['city'])){
	
	// Proje listesine yönlendir.
	if($LanguageInfo['lang_id']=='ar-sa'){
		
		header("Location:".$MAIN_URL."/ar-sa/تركيا-العقارات/");
		exit;
		
	}else{
		
		header("Location:".$MAIN_URL."/en-us/turkey-real-estate/");
		exit;
		
	}
	
}else{
	
	// ihit_projects_citys de sef kontrol edilecek 1 den küçük proje listesine yönlendirilecek.
	$CityControl = $DB->prepare("SELECT * FROM ihit_projects_citys WHERE sef = :sef");
	$CityControl->execute(array(':sef'=>addslashes(htmlspecialchars($_GET['city']))));
	if($CityControl->rowCount() < 1){

		// Hayır şehir bulunamazsa 404
		header("Location:".$MAIN_URL."/404");
		
	}else{
		
		$City = addslashes(htmlspecialchars($_GET['city']));
		
	}
	
}

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////
/*
if($DilSef=='ar-sa' AND ){
	
	header("Location:".$MAIN_URL."/ar-sa/تركيا-العقارات/".$City."/".$ProjectID."/");
	exit;
	
}elseif($DilSef=='en-us'){
	
	print("Location:".$MAIN_URL."/en-us/turkey-real-estate/".$City."/".$ProjectID."/");
	exit;
	
}else{
	
	print("Location:".$MAIN_URL);
	exit;
	
}

*/

// Gelen dilsef bilgileri
$DilSefInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE lang_id = :lang_id");
$DilSefInfo->execute(array(':lang_id'=>$DilSef));
$DilSefInfo = $DilSefInfo->fetch(PDO::FETCH_ASSOC);

// Eğer newlang var ise ve şuanki dil idsine eşit değil ise.
if(isset($_GET['newlang'])){
	
	//Newlang mevcut, dil için url değiştirilecek.
	// Newlang da ki id ve status kontrolü. YAPILACAK
	$NewLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE id = :id AND status = :status");
	$NewLangInfo->execute(array(':id'=>(int)addslashes($_GET['newlang']),':status'=>1));
	
	if($NewLangInfo->rowCount() > 0){
	
		$NewLangInfo = $NewLangInfo->fetch(PDO::FETCH_ASSOC);
	
		// Eğer dil şuanki dil ile aynı değilse bu işlemi yapıcaz.
		if($DilSefInfo['id']!=$_GET['newlang']){
			
			// URL Değiştir
			if($NewLangInfo['lang_id']=='ar-sa'){
			
				header("Location:".$MAIN_URL."/ar-sa/تركيا-العقارات/".$City."/".$ProjectID."/");
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$MAIN_URL."/en-us/turkey-real-estate/".$City."/".$ProjectID."/");
				exit;
				
			}else{
				
				header("Location:".$MAIN_URL);
				exit;
				
			}
			
		}else{
			
			// Zaten aynı dil o yüzden newlang metodunu urlden silmek için tekrar aynı sayfaya yönlendiriyoruz.
			
			// URL Değiştir
			if($NewLangInfo['lang_id']=='ar-sa'){
			
				header("Location:".$MAIN_URL."/ar-sa/تركيا-العقارات/".$City."/".$ProjectID."/");
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$MAIN_URL."/en-us/turkey-real-estate/".$City."/".$ProjectID."/");
				exit;
				
			}else{
				
				header("Location:".$MAIN_URL);
				exit;
				
			}
			
		}
		
	} // Bu dil yok, istenirse 404 yönlendirilebilir else ile.
	
}

// Gelen dilin id si ve proje id ile veritabanında arama yapıyoruz
$CheckProject = $DB->prepare("SELECT * FROM ihit_projects WHERE lang_id = :lang_id AND project_id = :project_id AND status = :status");
$CheckProject->execute(array(':lang_id'=>$DilSefInfo['id'],':project_id'=>$ProjectID,':status'=>1));

// Eğer proje bulunursa ProjectInfo değişkeni ile proje bilgilerini çekiyoruz
if($CheckProject->rowCount() > 0){
	
	$ProjectInfo = $CheckProject->fetch(PDO::FETCH_ASSOC);
	
}else{
	
	// Hayır proje bulunamazsa 404
	header("Location:".$MAIN_URL."/404");
	
}


if($DilSefInfo['lang_id']=='ar-sa'){

	$ProjeURL = $MAIN_URL."/ar-sa/تركيا-العقارات/".$City."/".$ProjectID."/";

}elseif($DilSefInfo['lang_id']=='en-us'){
	
	$ProjeURL = $MAIN_URL."/en-us/turkey-real-estate/".$City."/".$ProjectID."/";
	
}

$CurrentLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE lang_id = :lang_id");
$CurrentLangInfo->execute(array(':lang_id'=>$DilSef));
$CurrentLangInfo = $CurrentLangInfo->fetch(PDO::FETCH_ASSOC);

/*
//Projenin Tipi
$ProjectTypeInfo = $DB->prepare("SELECT * FROM ihit_projects_types WHERE id = :id");
$ProjectTypeInfo->execute(array(':id'=>$ProjectInfo['type_id']));

$ProjectTypeInfo = $ProjectTypeInfo->fetch(PDO::FETCH_ASSOC);
*/
?>
<!DOCTYPE HTML>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $ProjectInfo['project_id']; ?> <?php echo getCityInfo($ProjectInfo['city_id'],'title'); ?> Project Details - Ideal Home in Turkey</title>
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
		<link href="<?php echo $MAIN_URL; ?>/assets/css/detail.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/footer.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/pgwslideshow.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $MAIN_URL; ?>/assets/css/fix.css" rel="stylesheet" type="text/css">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Raleway:500,600,700|Montserrat:400,700" rel="stylesheet" type="text/css">
		<style type="text/css">

		.fix-sidebar {
			top:15px;
			position:fixed;
			max-width:220px;
		}
		
		.shortcode_slider_list li {
			
			float:none;
			
		}
		
		@media only screen and (min-width: 0px) and (max-width: 650px) {
			

			.shortcode_slider_list{

				width:300px!important;

			}

			.shortcode_slider_list li {

				float:none;

			}
			
		}
		
		a{
			color: #fff;
		}
		
		.floor-plans-img{
			
			width:235px;
			height:200px;
			
		}
		
		.related-projects-a{
		
			color:#0A63AE;
		
		}

						
		.lt_tab .lt_tab_content {
			width: 100%!important;
			margin: 0 auto 20px auto;
			padding: 15px 20px 20px 20px;
			background: #fff!important;
		}
		
		.lt_tab .lt_nav li span {
			
			font-family: sans-serif!important;
			font-size: 15px!important;
			margin-right: 2px!important;
			
		}
		
		.ps-caption{
		
			font-size: 16px!important;
		
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
<script type="text/javascript">
    window.smartlook||(function(d) {
    var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
    var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
    c.charset='utf-8';c.src='//rec.smartlook.com/recorder.js';h.appendChild(c);
    })(document);
    smartlook('init', '5156e85daad8210994d92b7dadd1380dc07bc68d');
</script>
<script charset="UTF-8" src="//cdn.sendpulse.com/28edd3380a1c17cf65b137fe96516659/js/push/5cbef200ce4d8d0fb4bdb4a97d1397b5_1.js" async></script>
</head>
<body>
<?php
require_once("header.php");
?>
<main class="main">
   <div class="container">
      <div class="row mobile-menu-fixer">

         <div class="col-md-9 col-sm-8 col-xs-12">
            <article>
			
			   <!-- Breadcrumb -->
				<div style="height:50px;background-color: #616161; color: #fff;">
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 20px;"><a href="<?php echo $MAIN_URL; ?>"><?php echo $Lang['breadcrumb_anasayfa']; ?></a> &rsaquo; <a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/';
				
			}
		 
		 ?>"><?php echo $Lang['breadcrumb_turkeyrealestate']; ?></a> &rsaquo; <a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/home/'.$ProjectInfo['city_id'];
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/home/'.$ProjectInfo['city_id'];
				
			}
		 
		 ?>"><?php echo getCityInfo($ProjectInfo['city_id'],'title'); ?></a> &rsaquo; <?php echo $ProjectInfo['project_id']; ?></div>
				
				<!--
				Favorilere Ekle Kısımı Kaldırıldı.
				<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 20px;font-size: 12px; text-align: right; float: right; color: #607D8B;">
					<a href="" style="float:right;color:#EEEEEE;"><i class="fa fa-star"></i> <?php echo $Lang['favorilere_ekle']; ?></a>
				</div>
				-->
				</div>
				
			   <div class="price_info_grid" style="padding: 20px; background-color: #FAFAFA; color: #171C26;font-size:25px;">
					<div class="row">
						<div class="col-md-8 col-sm-8 col-xs-6" style="margin-left: -10px;">
							<?php echo $Lang['fiyat_minimum']; ?> <b style="text-align:right;color:#E31B23;"><?php echo $ProjectInfo['price']; ?></b>
						</div>
					<div class="col-md-4 col-sm-4 col-xs-6 project_social_share" style="font-size: 16px; text-align: right; float: right; color: #607D8B;">
					
						<a href="javascript:window.print()"><i class="fa fa-print" style="color:#32506d!important"></i></a> &nbsp;
						<a class="facebook customer share" href="http://www.facebook.com/sharer.php?u=<?php echo $ProjeURL; ?>" target="_blank">
							<i class="fa fa-facebook" style="color:#3b5998!important"></i>
						</a> &nbsp;
						<a class="twitter customer share" href="http://twitter.com/share?url=<?php echo $ProjeURL; ?>&amp;text= &amp;hashtags=idealhomeinturkey" target="_blank">
							<i class="fa fa-twitter" style="color:#55acee!important"></i>
						</a>&nbsp;
						<?php
							if($detect->isMobile()){
						?>
						<a href="whatsapp://send?text=<?php echo $ProjeURL; ?>">
						<i class="fa fa-whatsapp" style="color:#4dc247!important"></i>
						</a>
						&nbsp;
						<?php } ?>
						<a href="mailto:?subject=Project <?php echo $ProjectInfo['project_id']; ?> - idealhomeinturkey.com&amp;body=<?php echo $ProjeURL; ?>">
						<i class="fa fa-envelope" style="color:#45668e!important"></i>
						</a>
					
					</div>
					</div>
				</div>
			   
			   <!-- SLIDER -->
					<section data-featherlight-gallery data-featherlight-filter="a">
				<ul class="pgwSlideshow">
					<?php
					
						$CountImg = 0;
						$listImages = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$ProjectInfo['id']."' ORDER BY rank");
						foreach($listImages as $Image){
						$CountImg++;
					
					?>
						<li><a href="<?php echo $MAIN_URL.'/uploads/project/'.$Image['image']; ?>"><img src="https://www.idealhomeinturkey.com/mthumb.php?src=<?php echo $MAIN_URL.'/uploads/project/'.$Image['image']; ?>&w=180&h=110&q=75&zc=3" alt="<?php echo $ProjectInfo['project_id']." Turkey Real Estate ".$CountImg; ?>"<?php if(trim($Image['description'])!=''){?> data-description="<?php echo $Image['description']; ?>"<?php } ?><?php if(trim($Image['title'])!=''){ ?> data-title="<?php echo $Image['title']; ?>"<?php } ?> /></a></li>
					<?php } ?>
				</ul>
			
					</section>
			    <?php
					if (trim($ProjectInfo['slogan']) != '') {
				?>
			   <!-- SLOGAN -->
			   <h1 style="margin-top: 20px;font-size: 1.8em;text-align: center; padding: 20px; background-color: #FFFFFF; color: #171C26; border: 1px solid #171C26;">
				   <?php echo $ProjectInfo['slogan']; ?>
				</h1>
				<?php } ?>
				
				
			   <?php
				if($detect->isMobile() AND !$detect->isTablet()){
			   ?>
               <div class="box-container">
                  <div class="row">
                     <div class="col-md-8">
                        <h3><?php echo $Lang['box_formbilgialin']; ?></h3>
                        <form class="property-contact" method="post" action="javascript:void(0);" id="contact_form_project_page">
                              <label for="fullname"><?php echo $Lang['form_adsoyad']; ?></label>
                              <input type="text" name="fullname" id="fullname" />
							  <br /><br />
                              <label for="phone"><?php echo $Lang['form_telefon']; ?></label>
                              <input type="tel" id="phone_number_project" name="phone" />
							  <br />
                              <label for="email"><?php echo $Lang['form_eposta']; ?></label>
                              <input type="email" id="email" name="email" />
							  <br /><br />
                              <label for="message"><?php echo $Lang['form_mesaj']; ?></label>
                              <textarea id="message" name="message" cols="30" rows="10"></textarea>
							  <br /><br />
							<input type="hidden" id="formname" name="formname" value="<?php echo $ProjectInfo['project_id']; ?> Proje Form" />
							<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
                           <input type="submit" class="btn" onclick="sendForm('contact_form_project_page','phone_number_project');" style="background: #e31b23; color: #fff;" id="submitted" value="<?php echo $Lang['form_gonder']; ?>" />
                        </form>
                     </div>
                  </div>
			</div>
			<?php } ?>
			
			<?php
			
				if(trim($ProjectInfo['description'])!=''){
			
			?>
			<!-- ÖZELLİKLER -->
               <div class="box-container">
                  <div class="row">
                     <div class="col-md-12">
                        <h3><?php echo $Lang['box_aciklama']; ?></h3>
                        <div class="entry-content">
                           <p><?php echo $ProjectInfo['description']; ?></p>
                        </div>
                     </div>
                  </div>
               </div>
			   <?php } ?>
			   
			   <?php
			  
				$countFeatures = $DB->query("SELECT * FROM ihit_project_features WHERE pid = '".$ProjectInfo['id']."'");
				if($countFeatures->rowCount() > 0){
			   
			   ?>
               <div class="box-container">
			   <?php
			   
				// Category ID ve Proje ID si ile total sonucu alalım, eğer 0 dan yüksek ise bu alanı gösterelim.
				$FeaturesCat1 = $DB->query("SELECT * FROM ihit_project_features WHERE category = '1' AND pid = '".$ProjectInfo['id']."'");
				if($FeaturesCat1->rowCount() > 0){
			   
			   ?>
                  <h3><?php echo $Lang['genel_ozellikleri']; ?></h3>
                  <ul class="property-amenities">
				  <?php
					foreach($FeaturesCat1->fetchAll(PDO::FETCH_ASSOC) as $Feature){
						
					// Özelliğin isimini almak için sorgu
					$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
					$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);
					
				  ?>
                     <li><?php 	echo $FeatureInfo['name']; ?></li>
				  <?php }?>
                  </ul>
				  <br /><br />
				<?php } ?>
			   <?php
			   
				// Category ID ve Proje ID si ile total sonucu alalım, eğer 0 dan yüksek ise bu alanı gösterelim.
				$FeaturesCat2 = $DB->query("SELECT * FROM ihit_project_features WHERE category = '2' AND pid = '".$ProjectInfo['id']."'");
				if($FeaturesCat2->rowCount() > 0){
			   
			   ?>
                  <h3><?php echo $Lang['guvenlik_ozellikleri']; ?></h3>
                  <ul class="property-amenities">
				  <?php
					foreach($FeaturesCat2->fetchAll(PDO::FETCH_ASSOC) as $Feature){
						
					// Özelliğin isimini almak için sorgu
					$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
					$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);
					
				  ?>
                     <li><?php 	echo $FeatureInfo['name']; ?></li>
				  <?php }?>
                  </ul>
				  <br /><br />
				<?php } ?>
			   <?php
			   
				// Category ID ve Proje ID si ile total sonucu alalım, eğer 0 dan yüksek ise bu alanı gösterelim.
				$FeaturesCat3 = $DB->query("SELECT * FROM ihit_project_features WHERE category = '3' AND pid = '".$ProjectInfo['id']."'");
				if($FeaturesCat3->rowCount() > 0){
			   
			   ?>
                  <h3><?php echo $Lang['sosyal_imkanlar']; ?></h3>
                  <ul class="property-amenities">
				  <?php
					foreach($FeaturesCat3->fetchAll(PDO::FETCH_ASSOC) as $Feature){
						
					// Özelliğin isimini almak için sorgu
					$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
					$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);
					
				  ?>
                     <li><?php 	echo $FeatureInfo['name']; ?></li>
				  <?php }?>
                  </ul>
				  <br /><br />
				<?php } ?>
			   <?php
			   
				// Category ID ve Proje ID si ile total sonucu alalım, eğer 0 dan yüksek ise bu alanı gösterelim.
				$FeaturesCat4 = $DB->query("SELECT * FROM ihit_project_features WHERE category = '4' AND pid = '".$ProjectInfo['id']."'");
				if($FeaturesCat4->rowCount() > 0){
			   
			   ?>
                  <h3><?php echo $Lang['spor_aktiviteleri']; ?></h3>
                  <ul class="property-amenities">
				  <?php
					foreach($FeaturesCat4->fetchAll(PDO::FETCH_ASSOC) as $Feature){
						
					// Özelliğin isimini almak için sorgu
					$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
					$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);
					
				  ?>
                     <li><?php 	echo $FeatureInfo['name']; ?></li>
				  <?php }?>
                  </ul>
				  <br /><br />
				<?php } ?>
			   <?php
			   
				// Category ID ve Proje ID si ile total sonucu alalım, eğer 0 dan yüksek ise bu alanı gösterelim.
				$FeaturesCat5 = $DB->query("SELECT * FROM ihit_project_features WHERE category = '5' AND pid = '".$ProjectInfo['id']."'");
				if($FeaturesCat5->rowCount() > 0){
			   
			   ?>
                  <h3><?php echo $Lang['teknik_ozellikler']; ?></h3>
                  <ul class="property-amenities">
				  <?php
					foreach($FeaturesCat5->fetchAll(PDO::FETCH_ASSOC) as $Feature){
						
					// Özelliğin isimini almak için sorgu
					$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
					$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);
					
				  ?>
                     <li><?php 	echo $FeatureInfo['name']; ?></li>
				  <?php }?>
                  </ul>
				  <br /><br />
				<?php } ?>
               </div>
			   
				<?php } // Features kontrol ?>
				
			   <?php

					if (trim($ProjectInfo['youtube_id']) != '') {
					
				?>
               <div class="box-container">
                  <h3><?php echo $Lang['box_tanitim_videosu']; ?></h3>
                  <div class="fluid-width-video-wrapper"><iframe src="https://www.youtube.com/embed/<?php echo $ProjectInfo['youtube_id']; ?>?feature=oembed" frameborder="0" allowfullscreen="" id="fitvid994425" style="margin-bottom: 15px; width: 100%; min-height:400px;"></iframe></div>
               </div>
			<?php
				}
			?>
				
				<?php

					if (trim($ProjectInfo['map_lat']) != '' AND trim($ProjectInfo['map_lng']) != '') {
					
				?>
				<!-- LOKASYON -->
               <div class="box-container">
                  <div class="row">
                     <div class="col-md-12">
                        <h3><?php echo $Lang['box_konum']; ?></h3>
						<div style='overflow:hidden;height:320px;width:100%;'><div id='projectMap' style='height:320px;width:100%;'></div>
                     </div>
                  </div>
               </div>
			   </div>
			    <?php
					}
				?>
				
				<?php 
				
					$ENVList = $DB->query("SELECT * FROM ihit_project_social_areas WHERE project_id = '".$ProjectInfo['id']."' ORDER BY rank");
					
					if($ENVList->rowCount() > 0){
					
				?>
               <div class="box-container">
                  <h3><?php echo $Lang['box_etrafindanevar']; ?></h3>
                  <ul class="property-data">
				  <?php
					
					foreach($ENVList->fetchAll(PDO::FETCH_ASSOC) as $SocialArea){
						
						// Sosyal alanların bilgilerini çekelim.
						$SocialAreaInfo = $DB->query("SELECT * FROM ihit_social_areas WHERE id = '".$SocialArea['socialarea_id']."'");
						$SocialAreaInfo = $SocialAreaInfo->fetch(PDO::FETCH_ASSOC);
					
				  ?>
                     <li>
						<div class="col-md-6 col-sm-6 col-xs-6" style="font-weight: 100;"><?php echo $SocialAreaInfo['name']; ?></div>
						
						<div class="col-md-6 col-sm-6 col-xs-6">
						<?php
							if(trim($SocialArea['proximity'])!=''){ echo $SocialArea['proximity']; }
							
							if(trim($SocialArea['proximity']!='') AND trim($SocialArea['time'])!=''){ echo ' / '; }
							
							if(trim($SocialArea['time'])!=''){ echo $SocialArea['time']; }
						?>
						</div>
                     </li>
					<?php } ?>
                  </ul>
               </div>
			<?php } ?>
			   
				<?php

				// Eğer 1 yada 1 den fazla dosya var ise bu alanı gösterelim.
				$CountFiles = $DB->query("SELECT * FROM ihit_project_files WHERE project_id = '".$ProjectInfo['id']."'");
				if($CountFiles->rowCount() > 0){

				?>
					<div class="box-container">
						<h3><?php echo $Lang['box_dosyalar']; ?></h3>
						<?php

							// Dosyaları listeletelim.
							$ListFiles = $DB->query("SELECT * FROM ihit_project_files WHERE project_id = '".$ProjectInfo['id']."'");
							foreach($ListFiles->fetchAll(PDO::FETCH_ASSOC) as $File){

						?>
							<a class="icon-link" target="_blank" href="<?php echo $MAIN_URL; ?>/uploads/project/files/<?php echo $File['file_url']; ?>"><i class="fa fa-file-pdf-o"></i><?php echo $File['file_name']; ?></a>
						<?php } ?>
					</div>
				<?php } ?>
				
				<?php

					$TabsList = $DB->query("SELECT * FROM ihit_fp_tabs INNER JOIN ihit_fp_tab_contents WHERE ihit_fp_tab_contents.project_id = '".$ProjectInfo['id']."' AND ihit_fp_tab_contents.tab_id = ihit_fp_tabs.id ORDER BY ihit_fp_tab_contents.rank");
					if($TabsList->rowCount() > 0){

				?>
               <div class="box-container">
                  <h3><?php echo $Lang['box_katplanlari']; ?></h3>
				  
					<div class="lt_tab">
						<ul class="lt_nav">
						<?php
						
							//ihit_fp_tabs tablosunu listeleceğiz. Ancak Group BY ile tab_id leri mükerrer olmadan gruplamak gerekli.
							//sonrasında çağrılan benzersiz tab_id ler ihit_fp_tabs tablosuna bağlanılıp nameleri alınacak.
							$Count = 0;
							foreach($TabsList->fetchAll(PDO::FETCH_ASSOC) as $Tab){
								$Count++;
								
								$TabInfo = $DB->query("SELECT * FROM ihit_fp_tabs WHERE id = '".$Tab['tab_id']."'");
								$TabInfo = $TabInfo->fetch(PDO::FETCH_ASSOC);
						
						?>
							<li><span<?php if($Count == 1){ ?> class="active"<?php }?>><?php echo $TabInfo['tab_name']; ?></span></li>
						<?php } ?>
						</ul>

						<div class="lt_tab_content">
							<section data-featherlight-gallery data-featherlight-filter="a">
						<?php
						
							// Tab contentlerini oluşturuyoruz.
							$Count = 0;
							$TabsList = $DB->query("SELECT * FROM ihit_fp_tab_contents WHERE project_id = '".$ProjectInfo['id']."' GROUP BY tab_id ORDER BY rank");
							foreach($TabsList->fetchAll(PDO::FETCH_ASSOC) as $Tab){
								$Count++;
						
						?>
							<?php
								// ihit_fb_tab_contents listesi $Tab['tab_id'] ye göre
								
								$CountPlanImg = 0;
								$getTabContent = $DB->query("SELECT * FROM ihit_fp_tab_contents WHERE tab_id = '".$Tab['tab_id']."' AND project_id = '".$ProjectInfo['id']."' ORDER BY id DESC");
								foreach($getTabContent->fetchAll(PDO::FETCH_ASSOC) as $TabContent){
								$CountPlanImg++;
							?>
							<div id="tab<?php echo $Count; ?>" class="lt_content" style="display: block;">
								<a href="<?php echo $MAIN_URL; ?>/uploads/project/floorplans/<?php echo $TabContent['image']; ?>" class="FPGallery"><img src="https://www.idealhomeinturkey.com/mthumb.php?src=<?php echo $MAIN_URL; ?>/uploads/project/floorplans/<?php echo $TabContent['image']; ?>&w=2350&h=200&q=75&zc=3" class="floor-plans-img" alt="<?php echo $ProjectInfo['project_id'].' Floor Plans '.$CountPlanImg; ?>" /></a>
							<?php } ?>
							</div>
								</section>
							<?php } ?>
						</div>
					</div>
               </div>
			   <?php } ?>
			   
			   <?php
				if(trim($ProjectInfo['zingat_sale_url'])!='' OR trim($ProjectInfo['zingat_rent_url'])!=''){
			   ?>
               <div class="box-container">
                  <div class="row">
				  <h3><?php echo $Lang['box_bolgeraporu']; ?></h3>
				  
					<div class="lt_tab">
						<ul class="lt_nav">
							<?php if(trim($ProjectInfo['zingat_sale_url'])!=''){ ?><li><span class="active"><?php echo $Lang['box_bolgeraporu_satilikrapor']; ?></span></li><?php } ?>
							<?php if(trim($ProjectInfo['zingat_rent_url'])!=''){ ?><li><span><?php echo $Lang['box_bolgeraporu_kiralikrapor']; ?></span></li><?php } ?>
						</ul>

						<div class="lt_tab_content">
					<?php if(trim($ProjectInfo['zingat_sale_url'])!=''){ ?>
						<div id="tab1" class="lt_content" style="display: block;"><?php echo $ProjectInfo['zingat_sale_url']; ?></div>
					<?php
						}
					
					if(trim($ProjectInfo['zingat_rent_url'])!=''){ ?>
						<div id="tab2" class="lt_content" style="display: block;"><?php echo $ProjectInfo['zingat_rent_url']; ?></div>
					<?php } ?>
						</div>
					</div>
					   
                  </div>
               </div>
				<?php } ?>
			   <?php
				if(!$detect->isMobile()){
			   ?>
               <div class="box-container">
                  <div class="row">
                     <div class="col-md-8">
                        <h3><?php echo $Lang['box_formbilgialin']; ?></h3>
                        <form class="property-contact" method="post" action="javascript:void(0);" id="contact_form_project_page">
                              <label for="fullname"><?php echo $Lang['form_adsoyad']; ?></label>
                              <input type="text" name="fullname" id="fullname" />
							  <br /><br />
                              <label for="phone"><?php echo $Lang['form_telefon']; ?></label>
                              <input type="tel" id="phone_number_project" name="phone" />
							  <br />
                              <label for="email"><?php echo $Lang['form_eposta']; ?></label>
                              <input type="email" id="email" name="email" />
							  <br /><br />
                              <label for="message"><?php echo $Lang['form_mesaj']; ?></label>
                              <textarea id="message" name="message" cols="30" rows="10"></textarea>
							  <br /><br />
							<input type="hidden" id="formname" name="formname" value="Proje Form (<?php echo $ProjectInfo['project_id']; ?>)" />
							<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
                           <input type="submit" class="btn" onclick="sendForm('contact_form_project_page','phone_number_project');" style="background: #e31b23; color: #fff;" id="submitted" value="<?php echo $Lang['form_gonder']; ?>" />
                        </form>
                     </div>
                  </div>
			</div>
			<?php } ?>
			
			<div id="stop-animate-sidebar">&nbsp;</div>
	<?php
	
		if($ProjectInfo['related_1']!=0 OR $ProjectInfo['related_2']!=0 OR $ProjectInfo['related_3']!=0){
	
	?>
	  <div class="box-container box-similiar" style=" bottom: 0; z-index: 9; position: relative;">
		<h3><?php echo $Lang['box_benzerilanlar']; ?></h3>
		<div class="wpb_wrapper">
            <div class="article_container slider_container bottom-estate_property nobutton">
               <div class="shortcode_slider_wrapper" data-auto="0">
                  <ul class="shortcode_slider_list" style="margin-left: -35px;">
					<?php
					
						if($ProjectInfo['related_1']!=0){
							
							$Related1Query = $DB->query("SELECT * FROM ihit_projects WHERE id = '".$ProjectInfo['related_1']."'");
							$Related1 = $Related1Query->fetch(PDO::FETCH_ASSOC);
									
							// Fotoğraf Bilgileri
							$getImage = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$Related1['id']."' ORDER BY id ASC LIMIT 0,1");
							$getImage = $getImage->fetch(PDO::FETCH_ASSOC);
							
							$firstImage = $getImage['image'];
							
							if($DilSefInfo['id']==4){

								$Related1_URL = $MAIN_URL."/ar-sa/تركيا-العقارات/".getCityInfo($Related1['city_id'],'sef')."/".$Related1['project_id']."/";

							}elseif($DilSefInfo['id']==2){
								
								$Related1_URL = $MAIN_URL."/en-us/turkey-real-estate/".getCityInfo($Related1['city_id'],'sef')."/".$Related1['project_id']."/";
								
							}
					
					?>
                     <li>
                        <div class="col-md- shortcode-col listing_wrapper" style="width:290px!important;" data-org="3" data-listid="132">
                           <div class="property_listing" style="max-width: 290px;">
                              <div class="listing-unit-img-wrapper">
                                 <a href="<?php echo $Related1_URL; ?>"><img width="525" style="max-width: 289px; min-width: 289px; min-height: 220px; max-height: 220px;" height="328" src="https://www.idealhomeinturkey.com/mthumb.php?src=<?php echo $MAIN_URL;?>/uploads/project/<?php echo $firstImage; ?>&w=289&h=220&q=75&zc=3" class="lazyload img-responsive wp-post-image" alt="<?php echo $Related1['project_id']; ?>"></a>
                              </div>
								<div class="col-md-12" style="margin-top:10px;">
									
									<!-- BAŞLIK -->
									<h4><a style="color:#5563ae;" href="<?php echo $Related1_URL; ?>"><?php echo $Related1['project_id']; ?></a></h4>
								
									<!-- DETAYLAR -->
									<div class="property_location" style="margin-top:5px;margin-bottom:10px;"><?php echo getCityInfo($Related1['city_id'],'title'); ?></div>
								</div>
                              <div class="listing_unit_price_wrapper" style="padding: 10px; text-align: left!important; bottom: 0; position: absolute; width: 100%;">
                                <?php echo $Lang['fiyat_minimum']; ?> <?php echo $Related1['price']; ?>
									<div class="share_unit" style="display: none;">
										<!-- Facebook Share -->
										<a class="facebook customer share" href="http://www.facebook.com/sharer.php?u=<?php echo $Related1_URL; ?>" target="_blank">
											<i class="fa fa-facebook" style="color:#fff!important"></i>
										</a>
										<!-- Twitter Share -->
										<a class="twitter customer share" href="http://twitter.com/share?url=<?php echo $Related1_URL; ?>&amp;text= &amp;hashtags=idealhomeinturkey" target="_blank">
											<i class="fa fa-twitter" style="color:#fff!important"></i>
										</a>
										<?php
											if($detect->isMobile()){
										?>
										<!-- Whatsapp Share -->
										<a href="whatsapp://send?text=<?php echo $Related1_URL; ?>">
											<i class="fa fa-whatsapp" style="color:#fff!important"></i>
										</a>
										<?php } ?>
										<!-- Mail Share -->
										<a href="mailto:?subject=Project <?php echo $Related1['project_id']; ?> - idealhomeinturkey.com&amp;body=<?php echo $Related1_URL; ?>">
											<i class="fa fa-envelope" style="color:#fff!important"></i>
										</a>
									</div>
									<span class="share_list" style="float:right;background-image: url(&quot;<?php echo $MAIN_URL; ?>/assets/img/unitshare.png&quot;); background-size: 40px 16px;"></span>
                              </div>
                           </div>
                        </div>
                     </li>
					 <?php } ?>
					<?php
					
						if($ProjectInfo['related_2']!=0){
							
							$Related2Query = $DB->query("SELECT * FROM ihit_projects WHERE id = '".$ProjectInfo['related_2']."'");
							$Related2 = $Related2Query->fetch(PDO::FETCH_ASSOC);
									
							// Fotoğraf Bilgileri
							$getImage = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$Related2['id']."' ORDER BY id ASC LIMIT 0,1");
							$getImage = $getImage->fetch(PDO::FETCH_ASSOC);
							
							$firstImage = $getImage['image'];
							
							if($DilSefInfo['id']==4){

								$Related2_URL = $MAIN_URL."/ar-sa/تركيا-العقارات/".getCityInfo($Related2['city_id'],'sef')."/".$Related2['project_id']."/";

							}elseif($DilSefInfo['id']==2){
								
								$Related2_URL = $MAIN_URL."/en-us/turkey-real-estate/".getCityInfo($Related2['city_id'],'sef')."/".$Related2['project_id']."/";
								
							}
					
					?>
                    
                     <li>
                        <div class="col-md- shortcode-col listing_wrapper" style="width:290px!important;" data-org="3" data-listid="132">
                           <div class="property_listing" style="max-width: 290px;">
                              <div class="listing-unit-img-wrapper">
                                 <a href="<?php echo $Related2_URL; ?>"><img width="525" style="max-width: 289px; min-width: 289px; min-height: 220px; max-height: 220px;" height="328" src="https://www.idealhomeinturkey.com/mthumb.php?src=<?php echo $MAIN_URL;?>/uploads/project/<?php echo $firstImage; ?>&w=289&h=220&q=75&zc=3" class="lazyload img-responsive wp-post-image" alt="<?php echo $Related2['project_id']; ?>"></a>
                              </div>
								<div class="col-md-12" style="margin-top:10px;">
									
									<!-- BAŞLIK -->
									<h4><a style="color:#5563ae;" href="<?php echo $Related2_URL; ?>"><?php echo $Related2['project_id']; ?></a></h4>
								
									<!-- DETAYLAR -->
									<div class="property_location" style="margin-top:5px;margin-bottom:10px;"><?php echo getCityInfo($Related2['city_id'],'title'); ?></div>
								
								</div>
                              <div class="listing_unit_price_wrapper" style="padding: 10px; text-align: left!important; bottom: 0; position: absolute; width: 100%;">
                                <?php echo $Lang['fiyat_minimum']; ?> <?php echo $Related2['price']; ?>
									<div class="share_unit" style="display: none;">
										<!-- Facebook Share -->
										<a class="facebook customer share" href="http://www.facebook.com/sharer.php?u=<?php echo $Related2_URL; ?>" target="_blank">
											<i class="fa fa-facebook" style="color:#fff!important"></i>
										</a>
										<!-- Twitter Share -->
										<a class="twitter customer share" href="http://twitter.com/share?url=<?php echo $Related2_URL; ?>&amp;text= &amp;hashtags=idealhomeinturkey" target="_blank">
											<i class="fa fa-twitter" style="color:#fff!important"></i>
										</a>
										<?php
											if($detect->isMobile()){
										?>
										<!-- Whatsapp Share -->
										<a href="whatsapp://send?text=<?php echo $Related2_URL; ?>">
											<i class="fa fa-whatsapp" style="color:#fff!important"></i>
										</a>
										<?php } ?>
										<!-- Mail Share -->
										<a href="mailto:?subject=Project <?php echo $Related2['project_id']; ?> - idealhomeinturkey.com&amp;body=<?php echo $Related2_URL; ?>">
											<i class="fa fa-envelope" style="color:#fff!important"></i>
										</a>
									</div>
									<span class="share_list" style="float:right;background-image: url(&quot;<?php echo $MAIN_URL; ?>/assets/img/unitshare.png&quot;); background-size: 40px 16px;"></span>
                              </div>
                           </div>
                        </div>
                     </li>
					 <?php } ?>
					<?php
					
						if($ProjectInfo['related_3']!=0){
							
							$Related3Query = $DB->query("SELECT * FROM ihit_projects WHERE id = '".$ProjectInfo['related_3']."'");
							$Related3 = $Related3Query->fetch(PDO::FETCH_ASSOC);
									
							// Fotoğraf Bilgileri
							$getImage = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$Related3['id']."' ORDER BY id ASC LIMIT 0,1");
							$getImage = $getImage->fetch(PDO::FETCH_ASSOC);
							
							$firstImage = $getImage['image'];
					
							if($DilSefInfo['id']==4){

								$Related3_URL = $MAIN_URL."/ar-sa/تركيا-العقارات/".getCityInfo($Related3['city_id'],'sef')."/".$Related3['project_id']."/";

							}elseif($DilSefInfo['id']==2){
								
								$Related3_URL = $MAIN_URL."/en-us/turkey-real-estate/".getCityInfo($Related3['city_id'],'sef')."/".$Related3['project_id']."/";
								
							}
							
					?>
                     <li>
                        <div class="col-md- shortcode-col listing_wrapper" style="width:290px!important;" data-org="3" data-listid="132">
                           <div class="property_listing" style="max-width: 290px;">
                              <div class="listing-unit-img-wrapper">
                                 <a href="<?php echo $Related3_URL; ?>"><img width="525" style="max-width: 289px; min-width: 289px; min-height: 220px; max-height: 220px;" height="328" src="https://www.idealhomeinturkey.com/mthumb.php?src=<?php echo $MAIN_URL;?>/uploads/project/<?php echo $firstImage; ?>&w=289&h=220&q=75&zc=3" class="img-responsive wp-post-image" alt="<?php echo $Related3['project_id']; ?>"></a>
                              </div>
								<div class="col-md-12" style="margin-top:10px;">
									
									<!-- BAŞLIK -->
									<h4><a style="color:#5563ae;" href="<?php echo $Related3_URL; ?>"><?php echo $Related3['project_id']; ?></a></h4>
								
									<!-- DETAYLAR -->
									<div class="property_location" style="margin-top:5px;margin-bottom:10px;"><?php echo getCityInfo($Related3['city_id'],'title'); ?></div>
								
								</div>
                              <div class="listing_unit_price_wrapper" style="padding: 10px; text-align: left!important; bottom: 0; position: absolute; width: 100%;">
                                <?php echo $Lang['fiyat_minimum']; ?> <?php echo $Related3['price']; ?>
									<div class="share_unit" style="display: none;">
										<!-- Facebook Share -->
										<a class="facebook customer share" href="http://www.facebook.com/sharer.php?u=<?php echo $Related3_URL; ?>" target="_blank">
											<i class="fa fa-facebook" style="color:#fff!important"></i>
										</a>
										<!-- Twitter Share -->
										<a class="twitter customer share" href="http://twitter.com/share?url=<?php echo $Related3_URL; ?>&amp;text= &amp;hashtags=idealhomeinturkey" target="_blank">
											<i class="fa fa-twitter" style="color:#fff!important"></i>
										</a>
										<?php
											if($detect->isMobile()){
										?>
										<!-- Whatsapp Share -->
										<a href="whatsapp://send?text=<?php echo $Related3_URL; ?>">
											<i class="fa fa-whatsapp" style="color:#fff!important"></i>
										</a>
										<?php } ?>
										<!-- Mail Share -->
										<a href="mailto:?subject=Project <?php echo $Related3['project_id']; ?> - idealhomeinturkey.com&amp;body=<?php echo $Related3_URL; ?>">
											<i class="fa fa-envelope" style="color:#fff!important"></i>
										</a>
									</div>
									<span class="share_list" style="float:right;background-image: url(&quot;<?php echo $MAIN_URL; ?>/assets/img/unitshare.png&quot;); background-size: 40px 16px;"></span>
                              </div>
                           </div>
                        </div>
                     </li>
					 <?php } ?>
                  </ul>
               </div>
            </div>
         </div>
         </div>
		 <?php } ?>
			   
            </article>
         </div>
		 <!-- RIGHT SIDEBAR -->
         <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="sidebar">
			
               <div style="background-color: #FFF; border: 1px solid #BDBDBD; padding: 8px; margin: 0 0 20px;">
               <aside id="ci-loan-calculator-2" class="widget widget_ci-loan-calculator group">
			   <form method="post" action="javascript:void(0);">
                  <h3 class="widget-title"><?php echo $Lang['proje_sidebar_arama']; ?></h3>
				   <input style="width:75%" type="text" placeholder="istanbul, bursa etc." name="search_text" id="search_text" required="required">
					<button type="submit" id="searchPropertySubmit" onclick="sidebarSearch();" style="border: 1px solid #e31b23; height: 36px; background: #e31b23;" class="btn">
						<i style="color:#fff" class="fa fa-search"></i>
					</button>
				</form>
               </aside>
				</div>
			
               <aside style="width:220px!important;" id="ci-loan-calculator-3" class="proje-sag-form widget widget_ci-loan-calculator group hidden-xs">
			   <form method="post" action="javascript:void(0);" id="contact_form_sidebar">
                  <h3 class="widget-title blink" style="color:#e31b23!important;"><?php echo $Lang['proje_sidebar_formbaslik']; ?></h3>
				   <input style="margin-bottom:5px;" type="text" name="fullname" id="fullname" placeholder="<?php echo $Lang['form_adsoyad']; ?>" />
				   <input type="tel" id="phone_number_sidebar" name="phone" />
				   <input style="margin-bottom:5px;" type="email" name="email" id="email" placeholder="<?php echo $Lang['form_eposta']; ?>" />
				   <input style="margin-bottom:5px;" type="text" name="message" id="message" placeholder="<?php echo $Lang['form_mesaj']; ?>" />
				   <input type="hidden" id="formname" name="formname" value="Proje Sidebar Form (<?php echo $ProjectInfo['project_id']; ?>)" />
					<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
					<button type="submit" onclick="sendForm('contact_form_sidebar','phone_number_sidebar');" style="color: #ffffff; margin-bottom: 5px; width: 100%; height: 34px; background: #ff0000;" id="submitted" class="btn">
						<?php echo $Lang['form_gonder']; ?>
					</button>
				</form>
               </aside>
			   
            </div>
         </div>
      </div>
   </div>
</main>
<?php
require_once("footer.php");
?>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/scripts.js"></script>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/bxslider.js"></script>
<script src="<?php echo $MAIN_URL; ?>/assets/js/pgwslideshow.min.js"></script>

<link rel="stylesheet" href="<?php echo $MAIN_URL; ?>/assets/css/featherlight.css">
<link rel="stylesheet" href="<?php echo $MAIN_URL; ?>/assets/css/featherlight.gallery.min.css">
<script src="<?php echo $MAIN_URL; ?>/assets/js/featherlight.js"></script>
<script src="<?php echo $MAIN_URL; ?>/assets/js/featherlight.gallery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.FPGallery').featherlightGallery({
		gallery: {
			fadeIn: 300,
			fadeOut: 300
		},
		openSpeed:    300,
		closeSpeed:   300
	});
});

$(document).ready(function(){
	$('.ProjectGallery').featherlightGallery({
		gallery: {
			fadeIn: 300,
			fadeOut: 300
		},
		openSpeed:    300,
		closeSpeed:   300
	});
});
</script>

<?php if (trim($ProjectInfo['map_lat']) != '' AND trim($ProjectInfo['map_lng']) != '') {?>

	<!-- Google Maps Api -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeB_gxckxN1Np70JPXA9sP5xjxYYJhr94&callback=projectMap" async defer></script>

<?php } ?>
<script type="text/javascript">
<?php

if (trim($ProjectInfo['map_lat']) != '' AND trim($ProjectInfo['map_lng']) != '') {
    
?>

function projectMap() {
	
	var myLatLng = {lat: <?php echo $ProjectInfo['map_lat']; ?>, lng: <?php echo $ProjectInfo['map_lng']; ?>};

	var map = new google.maps.Map(document.getElementById('projectMap'), {
		zoom: 10,
		scrollwheel: false,
		center: myLatLng
	});

	var marker = new google.maps.Marker({
		position: myLatLng,
		map: map,
		icon: 'https://www.idealhomeinturkey.com/assets/img/mapicon.png'
	});

	infowindow = new google.maps.InfoWindow({content:'<div style="z-index: 99999; overflow: hidden!important; height: 15px!important;"><?php echo $Lang['proje_haritaaciklama']; ?></div>'});
	google.maps.event.addListener(marker, 'click', function(){infowindow.open(map,marker);});
	infowindow.open(map,marker);

}
<?php
}
?>

$("document").ready(function() {

		//$("#phone_number_project").intlTelInput();

    var telInput = $("#phone_number_project"),
        errorMsg = $("#error-msg"),
        validMsg = $("#valid-msg");
    telInput.intlTelInput({
        autoHideDialCode: true,
        autoPlaceholder: true,
        customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
            return "e.g. " + selectedCountryPlaceholder;
        },
        separateDialCode: false,
        nationalMode: false,
	  utilsScript: "<?php echo $MAIN_URL; ?>/assets/js/utils.js",
		geoIpLookup: function(callback) {
		  $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			var countryCode = (resp && resp.country) ? resp.country : "";
			callback(countryCode);
		  });
		},
		initialCountry: "auto"
    });
    var reset = function() {
        telInput.removeClass("phone-error");
        errorMsg.addClass("hide");
        validMsg.addClass("hide");
    };
    telInput.blur(function() {
        reset();
        if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
                validMsg.removeClass("hide");
            } else {
                swal("Telefon Numarası Geçersiz", " ", "error");
                telInput.addClass("phone-error");
                errorMsg.removeClass("hide");
            }
        }
    });
    telInput.on("keyup change", reset);

    var telInput = $("#phone_number_sidebar"),
        errorMsg = $("#error-msg"),
        validMsg = $("#valid-msg");
    telInput.intlTelInput({
        autoHideDialCode: true,
        autoPlaceholder: true,
        customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
            return "e.g. " + selectedCountryPlaceholder;
        },
        separateDialCode: false,
        nationalMode: false,
	  utilsScript: "<?php echo $MAIN_URL; ?>/assets/js/utils.js",
		geoIpLookup: function(callback) {
		  $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			var countryCode = (resp && resp.country) ? resp.country : "";
			callback(countryCode);
		  });
		},
		initialCountry: "auto"
    });
    var reset = function() {
        telInput.removeClass("phone-error");
        errorMsg.addClass("hide");
        validMsg.addClass("hide");
    };
    telInput.blur(function() {
        reset();
        if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
                validMsg.removeClass("hide");
            } else {
                swal("Telefon Numarası Geçersiz", " ", "error");
                telInput.addClass("phone-error");
                errorMsg.removeClass("hide");
            }
        }
    });
    telInput.on("keyup change", reset);
});
</script>

<script type="text/javascript">

$(document).ready(function() {
	$('.pgwSlideshow').pgwSlideshow();
});

</script>
<script type="text/javascript">
function sidebarSearch(){

	var search_text = $("#search_text").val();
	$("#searchPropertySubmit").attr("disabled", "disabled");

	if(search_text==''){ var search_text = 0; }
	
	window.location.replace("<?php

			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/home/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/home/';
				
			}
			
			?>0_0_0_"+search_text);
		
}

jQuery(document).ready(function() {
	
	
function checkOffset() {
    if($('#ci-loan-calculator-3').offset().top + $('#ci-loan-calculator-3').height()>= $('#stop-animate-sidebar').offset().top)
        $('#ci-loan-calculator-3').css('position', 'absolute');
    if($(document).scrollTop() + window.innerHeight < $('#stop-animate-sidebar').offset().top)
        $('#ci-loan-calculator-3').css('position', 'fixed'); // restore when you scroll up
    //$('#ci-loan-calculator-3').text($(document).scrollTop() + window.innerHeight);
}
$(document).scroll(function() {
    checkOffset();
});


/*
	$(window).scroll(function (event) {
    var scroll = $(window).scrollTop();
	
	var wrap = $(".proje-sag-form");

		if (jQuery(window).scrollTop() > 260) {
			wrap.addClass("fix-sidebar");
		} else {
			wrap.removeClass("fix-sidebar");
		}
		
	});
*/
	
});

</script>
</body>
</html>