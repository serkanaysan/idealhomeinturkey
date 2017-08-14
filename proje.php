<?php
ob_start();
session_start();
header("Content-Type:text/html; Charset=UTF-8;");
DEFINE('PAGE','PROJECT');
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

if($ProjectInfo['project_status']==1){
	$ProjectStatus = $Lang['arama_projedurumu_hazir'];
}elseif($ProjectInfo['project_status']==2){
	$ProjectStatus = $Lang['arama_projedurumu_devamediyor'];
}else{
	$ProjectStatus = 'N/A';
}

/*
//Projenin Tipi
$ProjectTypeInfo = $DB->prepare("SELECT * FROM ihit_projects_types WHERE id = :id");
$ProjectTypeInfo->execute(array(':id'=>$ProjectInfo['type_id']));

$ProjectTypeInfo = $ProjectTypeInfo->fetch(PDO::FETCH_ASSOC);
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Meta -->
    <meta charset="utf-8">
	<title><?php echo $ProjectInfo['project_id']; ?> <?php echo getCityInfo($ProjectInfo['city_id'],'title'); ?> <?php
	if($DilSef=='ar-sa'){
		
		echo 'تفاصيل مشاريع ايديل هوم ان تركي';
	
	}else{
	
		echo 'Project Details | Ideal Home in Turkey';
	
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

    <!-- Stylesheets -->
    <link href="<?php echo $MAIN_URL; ?>/assets/css/combined.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $MAIN_URL; ?>/assets/css/library/libraries.owl.css" rel="stylesheet" type="text/css">

</head>
<body>
<?php require_once('header.php'); ?>
<section id="page-title" data-page-title="<?php if(trim($ProjectInfo['project_page_image'])!=''){ echo $ProjectInfo['project_page_image']; }else{ ?><?php echo $MAIN_URL; ?>/assets/img/properties.jpg<?php } ?>">
    <h1 class="page-title-text">
        <?php echo $ProjectInfo['project_id']; ?><?php if(!$detect->isMobile()){ ?> - <?php }else{ echo '<br />'; } echo $ProjectInfo['price']; ?></span>
    </h1>
</section>
<section id="single">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <ol class="breadcrumb breadcrumb-single">
                    <li><a href="<?php echo $MAIN_URL; ?>"><?php echo $Lang['breadcrumb_anasayfa']; ?></a></li>
                    <li><a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/';
				
			}
		 
		 ?>"><?php echo $Lang['breadcrumb_turkeyrealestate']; ?></a></li>
                    <li><a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/home/'.$ProjectInfo['city_id'];
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/home/'.$ProjectInfo['city_id'];
				
			}
		 
		 ?>"><?php echo getCityInfo($ProjectInfo['city_id'],'title'); ?></a></li>
                    <li class="active"><?php echo $ProjectInfo['project_id']; ?></li>
                </ol>
                <div class="slider">
                    <div class="slider-inner">
                        <div class="owl-carousel">
							<?php
							
								$CountImg = 0;
								$listImages = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$ProjectInfo['id']."' ORDER BY rank");
								foreach($listImages as $Image){
								$CountImg++;
							
							?>
								<div class="item">
									<div class="slider-image" data-slider-image="<?php echo $MAIN_URL; ?>/mthumb.php?src=<?php echo $REAL_URL.'/uploads/project/'.$Image['image']; ?>&w=850&h=430&q=75&zc=2" data-title="<?php echo $ProjectInfo['project_id']; ?>" data-toggle="modal" data-target="#modal">
									<?php if(trim($Image['title'])!='' OR trim($Image['description'])!=''){ ?><div class="description"><b><?php echo $Image['title']; ?></b> <?php echo $Image['description']; ?></div><?php } ?>
									</div>
								</div>
							<?php } ?>
                        </div>
                        <div class="slider-prev"><i class="fa fa-angle-left"></i></div>
                        <div class="slider-next"><i class="fa fa-angle-right"></i></div>
                    </div>
                    <div class="thumbnails-container">
                        <div class="thumbnails">
							<?php
							
								$CountImg = 0;
								$listImages = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$ProjectInfo['id']."' ORDER BY rank");
								foreach($listImages as $Image){
								$CountImg++;
							
							?>
								<div class="item thumbnails-item<?php if($CountImg==1){ echo ' active'; } ?>" data-thumb-image="<?php echo $MAIN_URL; ?>/mthumb.php?src=<?php echo $REAL_URL.'/uploads/project/'.$Image['image']; ?>&w=125&h=85&q=75&zc=0"></div>
							<?php } ?>
                        </div>
                        <div class="thumbnails-prev"><i class="fa fa-angle-left"></i></div>
                        <div class="thumbnails-next"><i class="fa fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="single-title">
                    <?php echo $ProjectInfo['slogan']; ?>
                </div>
				<?php
				
					/* Project Description */
					if(trim($ProjectInfo['description'])!=''){
				
				?>
					<div class="panel panel-default panel-single">
						<div class="panel-heading">
							<h3 class="panel-title"><?php echo $Lang['box_aciklama']; ?></h3>
						</div>
						<div class="panel-body">
							<div class="entry-content">
								<?php echo $ProjectInfo['description']; ?>
							</div>
						</div>
					</div>
				<?php } ?>
			   
			   <?php
				
				/* Project Features */
				$countFeatures = $DB->query("SELECT * FROM ihit_project_features WHERE pid = '".$ProjectInfo['id']."'");
				if($countFeatures->rowCount() > 0){
			   
			   ?>
                <div class="panel panel-default panel-single">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $Lang['projectdetails_amenities']; ?></h3>
                    </div>
                    <div class="panel-body">
					
						<?php
						/* Genel Özellikleri */
						$FeaturesCat = $DB->query("SELECT * FROM ihit_project_features WHERE category = '1' AND pid = '".$ProjectInfo['id']."'");
						if($FeaturesCat->rowCount() > 0){

						?>
							<div class="col-single clearfix">
								<h3><?php echo $Lang['genel_ozellikleri']; ?></h3>
								<ul class="property-amenities">
									<?php
									foreach($FeaturesCat->fetchAll(PDO::FETCH_ASSOC) as $Feature){

									// Özelliğin isimini almak için sorgu
									$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
									$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);

									?>
										<li><?php 	echo $FeatureInfo['name']; ?></li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>	
					
						<?php
						/* guvenlik_ozellikleri */
						$FeaturesCat = $DB->query("SELECT * FROM ihit_project_features WHERE category = '2' AND pid = '".$ProjectInfo['id']."'");
						if($FeaturesCat->rowCount() > 0){

						?>
							<div class="col-single clearfix">
								<h3><?php echo $Lang['guvenlik_ozellikleri']; ?></h3>
								<ul class="property-amenities">
									<?php
									foreach($FeaturesCat->fetchAll(PDO::FETCH_ASSOC) as $Feature){

									// Özelliğin isimini almak için sorgu
									$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
									$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);

									?>
										<li><?php 	echo $FeatureInfo['name']; ?></li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>	
					
						<?php
						/* sosyal_imkanlar */
						$FeaturesCat = $DB->query("SELECT * FROM ihit_project_features WHERE category = '3' AND pid = '".$ProjectInfo['id']."'");
						if($FeaturesCat->rowCount() > 0){

						?>
							<div class="col-single clearfix">
								<h3><?php echo $Lang['sosyal_imkanlar']; ?></h3>
								<ul class="property-amenities">
									<?php
									foreach($FeaturesCat->fetchAll(PDO::FETCH_ASSOC) as $Feature){

									// Özelliğin isimini almak için sorgu
									$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
									$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);

									?>
										<li><?php 	echo $FeatureInfo['name']; ?></li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>	
					
						<?php
						/* spor_aktiviteleri */
						$FeaturesCat = $DB->query("SELECT * FROM ihit_project_features WHERE category = '4' AND pid = '".$ProjectInfo['id']."'");
						if($FeaturesCat->rowCount() > 0){

						?>
							<div class="col-single clearfix">
								<h3><?php echo $Lang['spor_aktiviteleri']; ?></h3>
								<ul class="property-amenities">
									<?php
									foreach($FeaturesCat->fetchAll(PDO::FETCH_ASSOC) as $Feature){

									// Özelliğin isimini almak için sorgu
									$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
									$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);

									?>
										<li><?php 	echo $FeatureInfo['name']; ?></li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>	
					
						<?php
						/* teknik_ozellikler */
						$FeaturesCat = $DB->query("SELECT * FROM ihit_project_features WHERE category = '5' AND pid = '".$ProjectInfo['id']."'");
						if($FeaturesCat->rowCount() > 0){

						?>
							<div class="col-single clearfix">
								<h3><?php echo $Lang['teknik_ozellikler']; ?></h3>
								<ul class="property-amenities">
									<?php
									foreach($FeaturesCat->fetchAll(PDO::FETCH_ASSOC) as $Feature){

									// Özelliğin isimini almak için sorgu
									$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Feature['feature_id']."'");
									$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);

									?>
										<li><?php 	echo $FeatureInfo['name']; ?></li>
									<?php } ?>
								</ul>
							</div>
						<?php } ?>		
						
                    </div>
                </div>
				<?php } ?>
								
				<?php
				
					/* Lokasyon */
					if (trim($ProjectInfo['map_lat']) != '' AND trim($ProjectInfo['map_lng']) != '') {
					
				?>
                <div class="panel panel-default panel-single">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $Lang['box_konum']; ?></h3>
                    </div>
                    <div class="panel-body">
						<div style="overflow:hidden;height:320px;width:100%;"><div id="projectMap" style="height:320px;width:100%;"></div></div>
                    </div>
                </div>
				<?php } ?>
				
				<?php 
					/* Near by Places Nam-ı diğer "etrafında ne var" */
					$ENVList = $DB->query("SELECT * FROM ihit_project_social_areas WHERE project_id = '".$ProjectInfo['id']."' ORDER BY rank");
					
					if($ENVList->rowCount() > 0){
				?>
                <div class="panel panel-default panel-single">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $Lang['box_etrafindanevar']; ?></h3>
                    </div>
                    <table class="table table-hover">
                        <tr>
                            <th style="padding-left: 15px;"><?php echo $Lang['projectdetails_places']; ?></th>
                            <th><?php echo $Lang['projectdetails_timedistance']; ?></th>
                        </tr>
						<?php

						foreach($ENVList->fetchAll(PDO::FETCH_ASSOC) as $SocialArea){
							
							// Sosyal alanların bilgilerini çekelim.
							$SocialAreaInfo = $DB->query("SELECT * FROM ihit_social_areas WHERE id = '".$SocialArea['socialarea_id']."'");
							$SocialAreaInfo = $SocialAreaInfo->fetch(PDO::FETCH_ASSOC);

						?>
                        <tr>
                            <td width="40%" style="padding-left: 15px;"><?php echo $SocialAreaInfo['name']; ?></td>
                            <td width="60%"><?php
								if(trim($SocialArea['proximity'])!=''){ echo $SocialArea['proximity']; }
								
								if(trim($SocialArea['proximity']!='') AND trim($SocialArea['time'])!=''){ echo ' / '; }
								
								if(trim($SocialArea['time'])!=''){ echo $SocialArea['time']; }
							?></td>
                        </tr>
						<?php } ?>
                    </table>
                </div>
				<?php } ?>
				
				<?php

				// Eğer 1 yada 1 den fazla dosya var ise bu alanı gösterelim.
				$CountFiles = $DB->query("SELECT * FROM ihit_project_files WHERE project_id = '".$ProjectInfo['id']."'");
				if($CountFiles->rowCount() > 0){

				?>
                <div class="panel panel-default panel-single">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $Lang['box_dosyalar']; ?></h3>
                    </div>
                    <div class="panel-body">
						<div class="file-single clearfix">
							<ul class="property-amenities">
								<?php

									// Dosyaları listeletelim.
									$ListFiles = $DB->query("SELECT * FROM ihit_project_files WHERE project_id = '".$ProjectInfo['id']."'");
									foreach($ListFiles->fetchAll(PDO::FETCH_ASSOC) as $File){

								?>
									<li><a class="icon-link" target="_blank" href="<?php echo $MAIN_URL; ?>/uploads/project/files/<?php echo $File['file_url']; ?>"><?php echo $File['file_name']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
                    </div>
                </div>
				<?php } ?>
								
				<?php
					/* Kat Planları (Floor Plans) */
					$TabsList = $DB->query("SELECT * FROM ihit_fp_tabs INNER JOIN ihit_fp_tab_contents WHERE ihit_fp_tab_contents.project_id = '".$ProjectInfo['id']."' AND ihit_fp_tab_contents.tab_id = ihit_fp_tabs.id ORDER BY ihit_fp_tab_contents.rank");
					if($TabsList->rowCount() > 0){

				?>
                <div class="panel panel-default panel-single">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $Lang['box_katplanlari']; ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="tab-single">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-pills" role="tablist">
							<?php
							
								//ihit_fp_tabs tablosunu listeleceğiz. Ancak Group BY ile tab_id leri mükerrer olmadan gruplamak gerekli.
								//sonrasında çağrılan benzersiz tab_id ler ihit_fp_tabs tablosuna bağlanılıp nameleri alınacak.
								$Count = 0;
								foreach($TabsList->fetchAll(PDO::FETCH_ASSOC) as $Tab){
									$Count++;
									
									$TabInfo = $DB->query("SELECT * FROM ihit_fp_tabs WHERE id = '".$Tab['tab_id']."'");
									$TabInfo = $TabInfo->fetch(PDO::FETCH_ASSOC);
							
							?>
                                <li role="presentation"<?php if($Count == 1){ ?> class="active"<?php }?>><a href="#single-<?php echo $Count; ?>" aria-controls="single-<?php echo $Count; ?>" role="tab" data-toggle="tab"><?php echo $TabInfo['tab_name']; ?></a></li>
							<?php } ?>
                            </ul>

                            <!-- Floor plans tab contents -->
                            <div class="tab-content">
							<?php

								// Tab contentlerini oluşturuyoruz.
								$Count = 0;
								$TabsList = $DB->query("SELECT * FROM ihit_fp_tab_contents WHERE project_id = '".$ProjectInfo['id']."' GROUP BY tab_id ORDER BY rank");
								foreach($TabsList->fetchAll(PDO::FETCH_ASSOC) as $Tab){
									$Count++;
									
									// ihit_fb_tab_contents listesi $Tab['tab_id'] ye göre
									
									$CountPlanImg = 0;
									$getTabContent = $DB->query("SELECT * FROM ihit_fp_tab_contents WHERE tab_id = '".$Tab['tab_id']."' AND project_id = '".$ProjectInfo['id']."' ORDER BY id DESC");
									foreach($getTabContent->fetchAll(PDO::FETCH_ASSOC) as $TabContent){
									$CountPlanImg++;
							
							?>
                                <div role="tabpanel" class="tab-pane fade in<?php if($Count == 1){ ?> active<?php }?>" id="single-<?php echo $Count; ?>">
								<a href="<?php echo $MAIN_URL; ?>/uploads/project/floorplans/<?php echo $TabContent['image']; ?>" class="FPGallery"><img class="img-responsive" src="<?php echo $MAIN_URL; ?>/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/project/floorplans/<?php echo $TabContent['image']; ?>&w=800&h=575&q=85&zc=0" alt="<?php echo $ProjectInfo['project_id'].' Floor Plans '.$CountPlanImg; ?>"/></a>
                                </div>
							<?php } } ?>
                            </div>

                        </div>
                    </div>
                </div>
				<?php } ?>
				
			   <?php
					/* House Price Index (Zingat) */
					if(trim($ProjectInfo['zingat_sale_url'])!='' OR trim($ProjectInfo['zingat_rent_url'])!=''){
			   ?>
                <div class="panel panel-default panel-single">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $Lang['box_bolgeraporu']; ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="tab-single">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 20px">
                               <?php if(trim($ProjectInfo['zingat_sale_url'])!=''){ ?><li role="presentation" class="active"><a href="#sales-1" aria-controls="single-1" role="tab" data-toggle="tab"><?php echo $Lang['box_bolgeraporu_satilikrapor']; ?></a></li><?php } ?>
                                <?php if(trim($ProjectInfo['zingat_rent_url'])!=''){ ?><li role="presentation"><a href="#sales-2" aria-controls="sales-2" role="tab" data-toggle="tab"><?php echo $Lang['box_bolgeraporu_kiralikrapor']; ?></a></li><?php } ?>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="sales-1">
                                    <?php if(trim($ProjectInfo['zingat_sale_url'])!=''){ ?>
										<div id="tab1" class="lt_content" style="display: block;"><?php echo $ProjectInfo['zingat_sale_url']; ?></div>
									<?php } ?>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="sales-2">
									<?php if(trim($ProjectInfo['zingat_rent_url'])!=''){ ?>
										<div id="tab2" class="lt_content" style="display: block;"><?php echo $ProjectInfo['zingat_rent_url']; ?></div>
									<?php } ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
				<?php } ?>
				
                <div class="panel panel-default panel-single">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $Lang['projectdetails_getmoreinformations']; ?></h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="javascript:void(0);" id="contact_form_project_page">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="input-one"><?php echo $Lang['form_adsoyad']; ?></label>
                                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="<?php echo $Lang['form_adsoyad']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="input-three"><?php echo $Lang['form_telefon']; ?></label>
										<input type="tel" class="form-control" id="phone_number_project" name="phone" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="input-four"><?php echo $Lang['form_eposta']; ?></label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="<?php echo $Lang['form_eposta']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="input-five"><?php echo $Lang['form_mesaj']; ?></label>
                                        <textarea rows="5" class="form-control" id="message" name="message" placeholder="<?php echo $Lang['form_mesaj']; ?>"></textarea>
                                    </div>
                                </div>
								<input type="hidden" id="formname" name="formname" value="<?php echo $ProjectInfo['project_id']; ?> Proje Form" />
								<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
                                <div class="col-md-offset-4 col-md-4">
                                    <button type="submit" onclick="sendForm('contact_form_project_page','phone_number_project');" id="submitted" class="btn btn-danger btn-lg btn-block" style="background-color: #e51b24;
    border-color: #d43f3a;"><?php echo $Lang['form_gonder']; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			
            <div class="col-md-3">
                <div class="input-group input-group-single input-group-search">
                    <input type="text" id="search_text" class="form-control" placeholder="<?php echo $Lang['proje_sidebar_arama']; ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-default" id="searchPropertySubmit" onclick="sidebarSearch();" type="button"><i class="fa fa-search"></i></button>
                    </span>
                </div>
                <div class="single-social">
                    <a class="print" href="javascript:window.print()" title="Print"><i class="fa fa-print"></i></a>
                    <a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $ProjeURL; ?>" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
                    <a class="twitter" href="http://twitter.com/share?url=<?php echo $ProjeURL; ?>&amp;text= &amp;hashtags=idealhomeinturkey" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
                    <a class="mail" href="mailto:?subject=Project <?php echo $ProjectInfo['project_id']; ?> - idealhomeinturkey.com&amp;body=<?php echo $ProjeURL; ?>"><i class="fa fa-envelope"></i></a>
                </div>
                <div class="list-group list-group-project">
                    <div class="list-group-item active"><?php echo $Lang['projectdetails_projectdetails']; ?></div>
					
                    <table class="table table-hover">
                        <tr class="list-group-item">
                            <td style="width:40%;font-weight:bold;"><?php echo $Lang['projectdetails_price']; ?></td>
                            <td style="width:60&;"><?php echo $ProjectInfo['price']; ?></td>
                        </tr>
                        <tr class="list-group-item">
                            <td style="width:40%;font-weight:bold;"><?php echo $Lang['projectdetails_location']; ?></td>
                            <td style="width:60%;"><?php echo getCityInfo($ProjectInfo['city_id'],'title'); ?></td>
                        </tr>
                        <tr class="list-group-item">
                            <td style="width:40%;font-weight:bold;"><?php echo $Lang['projectdetails_completion']; ?></td>
                            <td style="width:60%;"><?php echo $ProjectStatus; ?></td>
                        </tr>
                        <tr class="list-group-item">
                            <td style="width:40%;font-weight:bold;"><?php echo $Lang['projectdetails_unittype']; ?></td>
                            <td style="width:60%;"><?php echo getProjectType($ProjectInfo['id']); ?></td>
                        </tr>
                    </table>
                </div>
				<form method="post" action="javascript:void(0);" id="contact_form_sidebar">
                <div class="contact hidden-xs hidden-sm" id="get-full-details">
                    <h3 class="contact-title">
                        <span class="blink">
							<i class="fa fa-rocket"></i> <?php echo $Lang['projectdetails_getfulldetails']; ?>
						</span>
                    </h3>
                    <label class="contact-item">
                        <span class="text"><?php echo $Lang['form_adsoyad']; ?></span>
                        <span class="input">
                            <input class="form-control" id="fullname" name="fullname" type="text"/>
                        </span>
                    </label>
                    <label class="contact-item">
                        <span class="text"><?php echo $Lang['form_telefon']; ?></span>
                        <span class="input">
                            <input class="form-control" id="phone_number_sidebar" type="tel"/>
                        </span>
                    </label>
                    <label class="contact-item">
                        <span class="text"><?php echo $Lang['form_eposta']; ?></span>
                        <span class="input">
                            <input class="form-control" id="email" name="email" type="email"/>
                        </span>
                    </label>
                    <label class="contact-item">
                        <span class="text"><?php echo $Lang['form_mesaj']; ?></span>
                        <span class="input">
                            <textarea class="form-control" id="message" name="message"></textarea>
                        </span>
                    </label>
					<input type="hidden" id="formname" name="formname" value="Proje Sidebar Form (<?php echo $ProjectInfo['project_id']; ?>)" />
					<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
                    <button class="btn btn-contact" onclick="sendForm('contact_form_sidebar','phone_number_sidebar');" id="submitted" type="submit" style="background-color: #000000; color: #fff; border-radius: 2px;width: 190px;"><?php echo $Lang['form_gonder']; ?></button>
                </div>
				</form>
            </div>
        </div>
		<div id="stop-animate-sidebar"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default panel-single">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $Lang['box_benzerilanlar']; ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
							<div class="smilar_carousel">
								<div class="owl-carousel">
									<?php
										$SimilarIDs = array();
										if($ProjectInfo['related_1']!=0){
											
											$SimilarIDs[] = $ProjectInfo['related_1'];
											
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
					
											if($Related1['project_status']==1){
												$ProjectStatus = $Lang['arama_projedurumu_hazir'];
											}elseif($Related1['project_status']==2){
												$ProjectStatus = $Lang['arama_projedurumu_devamediyor'];
											}else{
												$ProjectStatus = 'N/A';
											}
									
									?>
									<!-- PROP -->
									<div class="item">
											<div class="col-md-12">
												<div class="properties-card blue">
													<div class="properties-card-image" data-image="<?php echo $MAIN_URL; ?>/mthumb.php?src=<?php echo $REAL_URL;?>/uploads/project/<?php echo $firstImage; ?>&w=360&h=325&q=85"></div>
													<div class="properties-card-info">
														<span class="city"><?php echo getCityInfo($Related1['city_id'],'title'); ?></span>
														<span class="type"><?php echo $Related1['project_id']; ?></span>
													</div>
													<div class="properties-card-coast"><?php echo $Related1['price']; ?></div>
													<div class="properties-card-status"><?php echo $ProjectStatus; ?></div>
													<a class="properties-card-link" href="<?php echo $Related1_URL; ?>"></a>
												</div>
											</div>
									</div>
									<!-- PROP -->
									<?php } ?>
									
									<?php
									
										if($ProjectInfo['related_2']!=0){
											$SimilarIDs[] = $ProjectInfo['related_2'];
											
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
					
											if($Related2['project_status']==1){
												$ProjectStatus = $Lang['arama_projedurumu_hazir'];
											}elseif($Related2['project_status']==2){
												$ProjectStatus = $Lang['arama_projedurumu_devamediyor'];
											}else{
												$ProjectStatus = 'N/A';
											}
									
									?>
									<!-- PROP -->
									<div class="item">
											<div class="col-md-12">
												<div class="properties-card blue">
													<div class="properties-card-image" data-image="<?php echo $MAIN_URL; ?>/mthumb.php?src=<?php echo $REAL_URL;?>/uploads/project/<?php echo $firstImage; ?>&w=360&h=325&q=85"></div>
													<div class="properties-card-info">
														<span class="city"><?php echo getCityInfo($Related2['city_id'],'title'); ?></span>
														<span class="type"><?php echo $Related2['project_id']; ?></span>
													</div>
													<div class="properties-card-coast"><?php echo $Related2['price']; ?></div>
													<div class="properties-card-status"><?php echo $ProjectStatus; ?></div>
													<a class="properties-card-link" href="<?php echo $Related2_URL; ?>"></a>
												</div>
											</div>
									</div>
									<!-- PROP -->
									<?php } ?>
									
									<?php
									
										if($ProjectInfo['related_3']!=0){
											$SimilarIDs[] = $ProjectInfo['related_3'];
											
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
					
											if($Related3['project_status']==1){
												$ProjectStatus = $Lang['arama_projedurumu_hazir'];
											}elseif($Related3['project_status']==2){
												$ProjectStatus = $Lang['arama_projedurumu_devamediyor'];
											}else{
												$ProjectStatus = 'N/A';
											}
									
									?>
									<!-- PROP -->
									<div class="item">
											<div class="col-md-12">
												<div class="properties-card blue">
													<div class="properties-card-image" data-image="<?php echo $MAIN_URL; ?>/mthumb.php?src=<?php echo $REAL_URL;?>/uploads/project/<?php echo $firstImage; ?>&w=360&h=325&q=85"></div>
													<div class="properties-card-info">
														<span class="city"><?php echo getCityInfo($Related3['city_id'],'title'); ?></span>
														<span class="type"><?php echo $Related3['project_id']; ?></span>
													</div>
													<div class="properties-card-coast"><?php echo $Related3['price']; ?></div>
													<div class="properties-card-status"><?php echo $ProjectStatus; ?></div>
													<a class="properties-card-link" href="<?php echo $Related3_URL; ?>"></a>
												</div>
											</div>
									</div>
									<!-- PROP -->
									<?php } ?>
									
									<?php
										// Rastgele 7 adet benzer ilan daha..
											
											$SimilarIDListQuery = null;
										
											if(count($SimilarIDs)>0){
												$SimilarIDList = implode(',',$SimilarIDs);
												$SimilarIDListQuery = " AND id NOT IN (".$SimilarIDList.")";
											}
											
											$RandomSmilarQuery = $DB->query("SELECT * FROM ihit_projects WHERE status = '1' AND lang_id = '".$DilSefInfo['id']."'$SimilarIDListQuery ORDER BY RAND() LIMIT 7");
											$SimilarPropertiesRandList = $RandomSmilarQuery->fetchAll(PDO::FETCH_ASSOC);
											foreach($SimilarPropertiesRandList as $SimilarPropRand){
													
											// Fotoğraf Bilgileri
											$getImage = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$SimilarPropRand['id']."' ORDER BY id ASC LIMIT 0,1");
											$getImage = $getImage->fetch(PDO::FETCH_ASSOC);
											
											$firstImage = $getImage['image'];
											
											if($DilSefInfo['id']==4){

												$SimilarPropRand_URL = $MAIN_URL."/ar-sa/تركيا-العقارات/".getCityInfo($SimilarPropRand['city_id'],'sef')."/".$SimilarPropRand['project_id']."/";

											}elseif($DilSefInfo['id']==2){
												
												$SimilarPropRand_URL = $MAIN_URL."/en-us/turkey-real-estate/".getCityInfo($SimilarPropRand['city_id'],'sef')."/".$SimilarPropRand['project_id']."/";
												
											}
					
											if($SimilarPropRand['project_status']==1){
												$ProjectStatus = $Lang['arama_projedurumu_hazir'];
											}elseif($SimilarPropRand['project_status']==2){
												$ProjectStatus = $Lang['arama_projedurumu_devamediyor'];
											}else{
												$ProjectStatus = 'N/A';
											}
									
									?>
									<!-- PROP -->
									<div class="item">
											<div class="col-md-12">
												<div class="properties-card blue">
													<div class="properties-card-image" data-image="<?php echo $MAIN_URL; ?>/mthumb.php?src=<?php echo $REAL_URL;?>/uploads/project/<?php echo $firstImage; ?>&w=360&h=325&q=85"></div>
													<div class="properties-card-info">
														<span class="city"><?php echo getCityInfo($SimilarPropRand['city_id'],'title'); ?></span>
														<span class="type"><?php echo $SimilarPropRand['project_id']; ?></span>
													</div>
													<div class="properties-card-coast"><?php echo $SimilarPropRand['price']; ?></div>
													<div class="properties-card-status"><?php echo $ProjectStatus; ?></div>
													<a class="properties-card-link" href="<?php echo $SimilarPropRand_URL; ?>"></a>
												</div>
											</div>
									</div>
									<!-- PROP -->
									<?php } ?>
								</div>
								<div class="similar-prev"><i class="fa fa-angle-left"></i></div>
								<div class="similar-next"><i class="fa fa-angle-right"></i></div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once('footer.php'); ?>
<!-- Modal -->
<div class="content-modal modal fade bs-example-modal-lg" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg project-slider-modal" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div id="carousel-single-slider" class="carousel slide" data-ride="carousel">

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
					<?php
					
						$CountImg = 0;
						$listImages = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$ProjectInfo['id']."' ORDER BY rank");
						foreach($listImages as $Image){
						$CountImg++;
					
					?>
                        <div class="item<?php if($CountImg==1){ echo ' active'; } ?>">
                            <img class="img-responsive project-full-slide-img" src="<?php echo $REAL_URL.'/uploads/project/'.$Image['image']; ?>" alt="Project Image <?php echo $Image['id']; ?>" />
                        </div>
						<?php } ?>
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-single-slider" role="button" data-slide="prev">
                        <span class="glyphicon fa fa-chevron-left glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-single-slider" role="button" data-slide="next">
                        <span class="glyphicon fa fa-chevron-right glyphicon-chevron-right" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #Modal# -->
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/libraries.owl.js"></script>
<link rel="stylesheet" href="<?php echo $MAIN_URL; ?>/assets/css/featherlight.css">
<link rel="stylesheet" href="<?php echo $MAIN_URL; ?>/assets/css/featherlight.gallery.min.css">
<script src="<?php echo $MAIN_URL; ?>/assets/js/featherlight.js"></script>
<script src="<?php echo $MAIN_URL; ?>/assets/js/featherlight.gallery.js"></script>
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
function sidebarSearch(){

	var search_text = $("#search_text").val();
	$("#searchPropertySubmit").attr("disabled", "disabled");

	if(search_text==''){ var search_text = 0; }
	
	window.location = "<?php

			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/home/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/home/';
				
			}
			
			?>0_0_0_"+search_text;
		
}

$(document).ready(function(){
	$('.FPGallery').featherlightGallery({
		gallery: {
			fadeIn: 0,
			fadeOut: 0
		},
		openSpeed:    0,
		closeSpeed:   0
	});
});
</script>
</body>
</html>