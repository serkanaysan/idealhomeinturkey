<?php
function sanitize_output($buffer) {

    $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s',       // shorten multiple whitespace sequences
    );

    $replace = array(
        '>',
        '<',
        '\\1',
    );

    $buffer = preg_replace($search, $replace, $buffer);
	//$buffer = str_replace('haberbedava.com','funnygifs.site',$buffer);

    return $buffer;
}
ob_start();
ini_set('session.gc_maxlifetime', 60*60*24);
session_start();
header("Content-Type:text/html; Charset=UTF-8;");
require_once('includes/Database.php');
require_once('includes/Functions.php');
DEFINE('THISPAGE','INDEX');

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
	
		// Eğer dil şuanki dil ile aynı değilse bu işlemi yapıcaz.
		if($DilSefInfo['id']!=$_GET['newlang']){
			
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
			
		}else{
			
			// Zaten aynı dil o yüzden newlang metodunu urlden silmek için tekrar aynı sayfaya yönlendiriyoruz.
			
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
			
		}
		
	}else{ // Bu dil yok, istenirse 404 yönlendirilebilir else ile.
	
		header("Location:".$MAIN_URL);
		exit;
	
	}
	
}

$CurrentLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE lang_id = :lang_id");
$CurrentLangInfo->execute(array(':lang_id'=>$DilSef));
$CurrentLangInfo = $CurrentLangInfo->fetch(PDO::FETCH_ASSOC);

if($CurrentLangInfo['status']!=1){
	
	// Girilen dil durumu aktif değil, ingilizce sayfaya yönlendir.
	header("Location:".$MAIN_URL."/en-us/");
	exit;
	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title><?php
	if($DilSef=='ar-sa'){
		
		echo 'ايديل هوم ان تركي / للاستثمار والتطوير و الخدمات العقاريه';
	
	}else{
	
		echo 'Ideal Home in Turkey | Investment Advisory & Brokerage Company';
	
	}
	?></title>
	<meta name="description" content="<?php
	if($DilSef=='ar-sa'){
		
		echo 'ايديل هوم ان تركي واحده من افضل شركات الاستثمار و التطوير و الخدمات العقاريه في تركيا و اللتي تعطي افضل الاسعار و الخدمات المجانيه .';
	
	}else{
	
		echo 'Ideal Home in Turkey is the Turkey’s Best Real Estate Investment Advisory and Brokerage Company and Giving Best Price Guarantees and Free Services.';
	
	}
	?>">
	<meta property="og:title" content="<?php
	if($DilSef=='ar-sa'){
		
		echo 'ايديل هوم ان تركي / للاستثمار والتطوير و الخدمات العقاريه';
	
	}else{
	
		echo 'Ideal Home in Turkey | Investment Advisory & Brokerage Company';
	
	}
	?>" />
	<meta property="og:description" content="<?php
	if($DilSef=='ar-sa'){
		
		echo 'ايديل هوم ان تركي واحده من افضل شركات الاستثمار و التطوير و الخدمات العقاريه في تركيا و اللتي تعطي افضل الاسعار و الخدمات المجانيه .';
	
	}else{
	
		echo 'Ideal Home in Turkey is the Turkey’s Best Real Estate Investment Advisory and Brokerage Company and Giving Best Price Guarantees and Free Services.';
	
	}
	?>" />
	<meta name="yandex-verification" content="ef4331785146da1a" />
	<meta name="p:domain_verify" content="a817793898e51a3ba5d427a98eb5839f"/>
	<meta name="theme-color" content="#e51c25"/>
	<meta name="dns-prefix" content="//ajax.googleapis.com"/>
	<meta name="dns-prefix" content="//maxcdn.bootstrapcdn.com"/>
	<meta name="dns-prefix" content="//www.googleadservices.com"/>
	<meta name="dns-prefix" content="//cdn.sendpulse.com"/>
	<link rel="alternate" hreflang="en" href="https://www.idealhomeinturkey.com/en-us" />
	<link rel="alternate" hreflang="ar" href="https://www.idealhomeinturkey.com/ar-sa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Stylesheets -->
    <link href="<?php echo $MAIN_URL; ?>/assets/css/combined.css" rel="stylesheet" type="text/css">
</head>
<body>
<section id="home-cover-container">
    <section id="home-cover">
		<?php
		
			// Slider listesi
			$SliderList = $DB->prepare("SELECT * FROM ihit_sliders WHERE language_id = :language_id ORDER BY rank");
			$SliderList->execute(array(':language_id'=>$CurrentLangInfo['id']));
			$SliderNumber = 0;
			
			$SliderOneDetails = $DB->query("SELECT * FROM ihit_sliders WHERE language_id = '".$CurrentLangInfo['id']."' AND rank = '1'");
			$SliderOneDetails = $SliderOneDetails->fetch(PDO::FETCH_ASSOC);
			
			foreach($SliderList->fetchAll(PDO::FETCH_ASSOC) as $Slider){
			$SliderNumber++;
		
		?>
        <div class="home-cover-item" style="background-image: url('<?php echo $REAL_URL; ?>/uploads/slider/<?php echo $Slider['image']; ?>')"></div>
		<?php } ?>
    </section>
    <section id="home-cover-content">
        <h1><?php echo $SliderOneDetails['title']; ?></h1>
        <p>
            <?php echo $SliderOneDetails['description']; ?>
        </p>
        <div class="hidden-xs">
            <a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/';
				
			}
		 
		 ?>" class="btn btn-home-cover blue">
				<?php echo $Lang['menu_tumprojeler']; ?>
            </a>
            <a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/التواصل/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/contact/';
				
			}
		 
		 ?>" class="btn btn-home-cover red">
                <?php echo $Lang['menu_iletisim']; ?>
            </a>
        </div>
    </section>
</section>
<?php require_once('header.php'); ?>
<section id="search-container">
    <div class="container">
        <div class="row">
			<form class="form-inline" action="javascript:void(0);" method="post">
				<div class="col-md-2">
					<div class="selectbox">
						<select name="city" id="search_city">
						  <option value="0"><?php echo $Lang['arama_sehir']; ?></option>
						  <?php
							$CityList = $DB->query("SELECT * FROM ihit_projects_citys ORDER BY rank ASC");
							foreach($CityList->fetchAll(PDO::FETCH_ASSOC) as $City){
						  ?>
						  <option value="<?php echo $City['id']; ?>"<?php if(isset($getCity) AND $getCity==$City['id']){ echo ' SELECTED'; } ?>><?php echo $City['title']; ?></option>
						  <?php } ?>
						</select>
						<div class="selectbox-text"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="selectbox">
						<select name="type" id="search_type">
							<option value=""><?php echo $Lang['arama_konuttipi']; ?></option>
							<?php

							$ProjectTypeList = $DB->query("SELECT * FROM ihit_projects_types WHERE lang_id = '".$CurrentLangInfo['id']."' ORDER BY rank ASC");
							foreach($ProjectTypeList->fetchAll(PDO::FETCH_ASSOC) as $Type){
							?>
							<option value="<?php echo $Type['id']; ?>"<?php if(isset($getType) AND $getType==$Type['id']){ echo ' SELECTED'; } ?>><?php echo $Type['title']; ?></option>
							<?php } ?>
						</select>
						<div class="selectbox-text"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="selectbox">
						<select name="project_status" id="search_ps">
							<option value=""><?php echo $Lang['arama_projedurumu']; ?></option>
							<option value="1"<?php if(isset($getPs) AND $getPs==1){ echo ' SELECTED'; } ?>><?php echo $Lang['arama_projedurumu_hazir']; ?></option>
							<option value="2"<?php if(isset($getPs) AND $getPs==2){ echo ' SELECTED'; } ?>><?php echo $Lang['arama_projedurumu_devamediyor']; ?></option>
						</select>
						<div class="selectbox-text"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="selectbox">
						<select name="price_range" id="search_pr">
							<option value=""><?php echo $Lang['arama_pricerange']; ?></option>
							<option value="1">0 $ - 50.000 $</option>
							<option value="2">50.000 $ - 100.000 $</option>
							<option value="3">100.000 $ - 150.000 $</option>
							<option value="4">150.000 $ - 200.000 $</option>
							<option value="5">200.000 $ - 250.000 $</option>
							<option value="6">250.000 $ - 300.000 $</option>
							<option value="7">300.000 $ - 400.000 $</option>
							<option value="8">400.000 $ - 500.000 $</option>
							<option value="9">500.000 $ - 1.000.000 $</option>
							<option value="10">1.000.000 $ +</option>
						</select>
						<div class="selectbox-text"></div>
					</div>
				</div>
				<div class="col-md-2">
					<input type="text" class="form-control" name="text" id="search_text"<?php if(isset($getText) AND $getText!='0'){?> value="<?php echo addslashes(htmlspecialchars(strip_tags($getText))); ?>"<?php }else{?> placeholder="<?php echo $Lang['arama_aramakelimesi']; ?>"<?php } ?> autocomplete="off" />
				</div>
				<div class="col-md-2">
					<button type="submit" onclick="goSearch();" id="searchPropertySubmit" class="btn btn-search"><i class="fa fa-search"></i> <?php echo $Lang['arama_aramayap']; ?></button>
				</div>
			</form>
        </div>
    </div>
</section>
<section id="free-services">
    <div class="container">
        <h1 class="page-title" style="margin-bottom: 0px;"><span><?php echo $Lang['blok_hizmetlerimiz']; ?></span></h1>
        <div class="row">
		 <?php
		 
			$ourServicesQuery = $DB->prepare("SELECT * FROM ihit_services WHERE lang_id = :lang_id ORDER BY rank ASC");
			$ourServicesQuery->execute(array(':lang_id'=>$CurrentLangInfo['id']));
			
			foreach($ourServicesQuery->fetchAll(PDO::FETCH_ASSOC) as $Service){
		 
		 ?>
            <div class="col-md-3 col-sm-6">
                <div class="free-services-card">
                    <?php if(trim($Service['url'])!=''){ ?><a href="<?php echo $Service['url']; ?>"><?php } ?>
                        <div class="icon">
                            <i class="fa fa-<?php echo $Service['icon']; ?>"></i>
                        </div>
                        <h3 class="title"><?php echo $Service['title']; ?></h3>
                        <p class="paragraph"><?php echo $Service['description']; ?></p>
                    <?php if(trim($Service['url'])!=''){ ?></a><?php } ?>
                </div>
            </div>
			<?php } ?>
        </div>
    </div>
</section>
<section id="properties" style="margin-top:0px;">
    <div class="container">
        <h1 class="page-title"><span><?php echo $Lang['blok_projeler']; ?></span></h1>
        <div class="properties-table clearfix">
            <div class="col-properties-half">
				<!-- Project 1 -->
				<?php
					$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE project_id = '".getHPP(1,'project_id')."' AND lang_id = '".$CurrentLangInfo['id']."'");
					$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);
				?>
                <div class="col-properties-half">
                    <div class="row-properties-half">
                        <div class="properties-card blue">
                            <div class="properties-card-image" data-image="<?php echo $REAL_URL; ?>/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/homepage_projects/<?php echo getHPP(1,'image'); ?>&w=281&h=302&q=75"></div>
                            <div class="properties-card-info">
                                <span class="city"><?php echo getHPP(1,'city'); ?></span>
                                <span class="type"><?php echo getHPP(1,'project_id'); ?></span>
                            </div>
                            <div class="properties-card-coast">
                                <?php echo getHPP(1,'price'); ?>
                            </div>
							<a class="properties-card-link"href="<?php
										
								if($DilSef=='ar-sa'){
									
									echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
								
								}elseif($DilSef=='en-us'){
									
									echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
									
								}
								
								?>/" title="<?php echo getHPP(1,'project_id'); ?>"></a>
                        </div>
                    </div>
                </div>
				<!-- Project 1 -->
			
			
				<!-- Project 2 -->
				<?php
					$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE project_id = '".getHPP(2,'project_id')."' AND lang_id = '".$CurrentLangInfo['id']."'");
					$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);
				?>
                <div class="col-properties-half">
                    <div class="row-properties-half">
                        <div class="properties-card red">
                            <div class="properties-card-image" data-image="<?php echo $REAL_URL; ?>/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/homepage_projects/<?php echo getHPP(2,'image'); ?>&w=281&h=302&q=75"></div>
                            <div class="properties-card-info">
                                <span class="city"><?php echo getHPP(2,'city'); ?></span>
                                <span class="type"><?php echo getHPP(2,'project_id'); ?></span>
                            </div>
                            <div class="properties-card-coast">
                               <?php echo getHPP(2,'price'); ?>
                            </div>
							<a class="properties-card-link"href="<?php
										
								if($DilSef=='ar-sa'){
									
									echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
								
								}elseif($DilSef=='en-us'){
									
									echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
									
								}
								
								?>/" title="<?php echo getHPP(2,'project_id'); ?>"></a>
                        </div>
                    </div>
                </div>
				<!-- Project 2 -->
				
				<!-- Project 3 -->
				<?php
					$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE project_id = '".getHPP(3,'project_id')."' AND lang_id = '".$CurrentLangInfo['id']."'");
					$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);
				?>
                <div class="col-properties-full">
                    <div class="row-properties-full">
                        <div class="properties-card cyan">
                            <div class="properties-card-image" data-image="<?php echo $REAL_URL; ?>/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/homepage_projects/<?php echo getHPP(3,'image'); ?>&w=570&h=606&q=75"></div>
                            <div class="properties-card-info">
                                <span class="city"><?php echo getHPP(3,'city'); ?></span>
                                <span class="type"><?php echo getHPP(3,'project_id'); ?></span>
                            </div>
                            <div class="properties-card-coast">
                                <?php echo getHPP(3,'price'); ?>
                            </div>
							<a class="properties-card-link"href="<?php
										
								if($DilSef=='ar-sa'){
									
									echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
								
								}elseif($DilSef=='en-us'){
									
									echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
									
								}
								
								?>/" title="<?php echo getHPP(3,'project_id'); ?>"></a>
                        </div>
                    </div>
                </div>
				<!-- Project 3 -->
            </div>
            <div class="col-properties-half">
			
				<!-- Project 4 -->
				<?php
					$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE project_id = '".getHPP(4,'project_id')."' AND lang_id = '".$CurrentLangInfo['id']."'");
					$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);
				?>
                <div class="col-properties-half">
                    <div class="row-properties-full">
                        <div class="properties-card yellow">
                            <div class="properties-card-image" data-image="<?php echo $REAL_URL; ?>/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/homepage_projects/<?php echo getHPP(4,'image'); ?>&w=285&h=606&q=75"></div>
                            <div class="properties-card-info">
                                <span class="city"><?php echo getHPP(4,'city'); ?></span>
                                <span class="type"><?php echo getHPP(4,'project_id'); ?></span>
                            </div>
                            <div class="properties-card-coast">
                                <?php echo getHPP(4,'price'); ?>
                            </div>
							<a class="properties-card-link"href="<?php
										
								if($DilSef=='ar-sa'){
									
									echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
								
								}elseif($DilSef=='en-us'){
									
									echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
									
								}
								
								?>/" title="<?php echo getHPP(4,'project_id'); ?>"></a>
                        </div>
                    </div>
                </div>
				<!-- Project 4 -->
				
                <div class="col-properties-half">
					<!-- Project 5 -->
					<?php
						$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE project_id = '".getHPP(5,'project_id')."' AND lang_id = '".$CurrentLangInfo['id']."'");
						$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);
					?>
                    <div class="row-properties-half" style="height: 303px">
                        <div class="properties-card green">
                            <div class="properties-card-image" data-image="<?php echo $REAL_URL; ?>/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/homepage_projects/<?php echo getHPP(5,'image'); ?>&w=281&h=303&q=75"></div>
                            <div class="properties-card-info">
                                <span class="city"><?php echo getHPP(5,'city'); ?></span>
                                <span class="type"><?php echo getHPP(5,'project_id'); ?></span>
                            </div>
                            <div class="properties-card-coast">
                                <?php echo getHPP(5,'price'); ?>
                            </div>
							<a class="properties-card-link"href="<?php
										
								if($DilSef=='ar-sa'){
									
									echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
								
								}elseif($DilSef=='en-us'){
									
									echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
									
								}
								
								?>/" title="<?php echo getHPP(5,'project_id'); ?>"></a>
                        </div>
                    </div>
					<!-- Project 5 -->
					
					<!-- Project 6 -->
					<?php
						$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE project_id = '".getHPP(6,'project_id')."' AND lang_id = '".$CurrentLangInfo['id']."'");
						$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);
					?>
                    <div class="row-properties-half" style="height: 303px">
                        <div class="properties-card purple">
                            <div class="properties-card-image" data-image="<?php echo $REAL_URL; ?>/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/homepage_projects/<?php echo getHPP(6,'image'); ?>&w=281&h=303&q=75"></div>
                            <div class="properties-card-info">
                                <span class="city"><?php echo getHPP(6,'city'); ?></span>
                                <span class="type"><?php echo getHPP(6,'project_id'); ?></span>
                            </div>
                            <div class="properties-card-coast">
                                <?php echo getHPP(6,'price'); ?>
                            </div>
							<a class="properties-card-link"href="<?php
										
								if($DilSef=='ar-sa'){
									
									echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
								
								}elseif($DilSef=='en-us'){
									
									echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
									
								}
								
								?>/" title="<?php echo getHPP(6,'project_id'); ?>"></a>
                        </div>
                    </div>
					<!-- Project 6 -->
                </div>
                <div class="col-properties-full">
					<!-- Project 7 -->
					<?php
						$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE project_id = '".getHPP(7,'project_id')."' AND lang_id = '".$CurrentLangInfo['id']."'");
						$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);
					?>
                    <div class="row-properties-half">
                        <div class="properties-card blue">
                            <div class="properties-card-image" data-image="<?php echo $REAL_URL; ?>/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/homepage_projects/<?php echo getHPP(7,'image'); ?>&w=570&h=306&q=75"></div>
                            <div class="properties-card-info">
                                <span class="city"><?php echo getHPP(7,'city'); ?></span>
                                <span class="type"><?php echo getHPP(7,'project_id'); ?></span>
                            </div>
                            <div class="properties-card-coast">
                                <?php echo getHPP(7,'price'); ?>
                            </div>
							<a class="properties-card-link"href="<?php
										
								if($DilSef=='ar-sa'){
									
									echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
								
								}elseif($DilSef=='en-us'){
									
									echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($ProjectInfo['city_id'],'sef'); ?>/<?php echo $ProjectInfo['project_id'];
									
								}
								
								?>/" title="<?php echo getHPP(7,'project_id'); ?>"></a>
                        </div>
                    </div>
					<!-- Project 7 -->
                </div>
            </div>
        </div>
    </div>
</section>
<section class="band">
    <a class="btn btn-band" href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/';
				
			}
		 
		 ?>" title="<?php echo $Lang['loadmore_tumprojeler']; ?>"><?php echo $Lang['loadmore_tumprojeler']; ?></a>
</section>
<section id="why">
    <div class="container">
        <h1 class="page-title"><span><?php echo $Lang['blok_bizinedensecmelisiniz']; ?></span></h1>
        <div id="homepage-tab">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified" role="tablist">
				<?php
				
					$TabList = $DB->prepare("SELECT * FROM ihit_hp_tabs WHERE lang_id = :lang_id ORDER BY rank");
					$TabList->execute(array(':lang_id'=>$CurrentLangInfo['id']));
					
					$Count = 0;
					foreach($TabList->fetchAll(PDO::FETCH_ASSOC) as $TabTitle){
					$Count ++;
				
				?>
                <li role="presentation" class="btn-tab<?php if($Count == 1){ ?> active<?php } ?>"><a href="#tab<?php echo $Count; ?>" aria-controls="tab<?php echo $Count; ?>" role="tab" data-toggle="tab"><?php echo $TabTitle['tabtitle']; ?></a></li>
				<?php } ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
			<?php
				$CountTab = 0;
				$TabInfo = $DB->prepare("SELECT * FROM ihit_hp_tabs WHERE lang_id = :lang_id ORDER BY rank");
				$TabInfo->execute(array(':lang_id'=>$CurrentLangInfo['id']));
				foreach($TabInfo->fetchAll(PDO::FETCH_ASSOC) as $Tab){
				$CountTab++;
			?>
                <div role="tabpanel" class="tab-pane fade in<?php if($CountTab == 1){ ?> active<?php } ?>" id="tab<?php echo $CountTab; ?>">
                    <div class="tab-item">
                        <div class="row"><!--
                            <div class="col-md-4">
                                <img class="img-responsive" src="<?php echo $MAIN_URL; ?>/assets/img/tab.jpg" width="3000" height="1990" alt="Image"/>
                            </div> -->
                            <div class="col-md-12" style="padding-left: 0;padding-right:0">
								<div class="col-md-3" style="padding-left: 0;padding-right:0"><img src="https://idealhomeinturkey.com/mthumb.php?src=<?php echo $Tab['tabimage']; ?>&w=300&h=199&q=80&zc=0" class="img-responsive" alt="Tab 1 image" style="width:100%;" /></div>
								<div class="col-md-9" style="padding:15px;padding: 25px; font-size: 14px; line-height: 32px; font-weight: bold; text-align: center;"><?php echo $Tab['tabcontent']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php } ?>
            </div>
        </div>
    </div>
</section>
<?php require_once('footer.php'); ?>
</body>
</html>