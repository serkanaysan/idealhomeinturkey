<?php
ob_start();
session_start();
header("Content-Type:text/html; Charset=UTF-8;");
require_once('includes/Database.php');
require_once('includes/Functions.php');

if(isset($_GET['parameters']) AND trim($_GET['parameters'])!=''){

	$Parameters = addslashes(htmlspecialchars(strip_tags($_GET['parameters'])));

	$ParseGetParameter = explode('_',$Parameters);
	
	$getCity = (int)$ParseGetParameter[0];
	$getType = (int)$ParseGetParameter[1];
	$getPs = (int)$ParseGetParameter[2];
	$getText = addslashes(htmlspecialchars(strip_tags($ParseGetParameter[3])));
	
	$ArabicURL = $MAIN_URL."/ar-sa/".urlencode('تركيا-العقارات')."/home/".$getCity."_".$getType."_".$getPs."_".$getText;
	$EnglishURL = $MAIN_URL."/en-us/turkey-real-estate/home/".$getCity."_".$getType."_".$getPs."_".$getText;
	
}else{
	
	$ArabicURL = $MAIN_URL."/ar-sa/تركيا-العقارات/";
	$EnglishURL = $MAIN_URL."/en-us/turkey-real-estate/";
	
}

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
			
				header("Location:".$ArabicURL);
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$EnglishURL);
				exit;
				
			}else{
				
				header("Location:".$MAIN_URL);
				exit;
				
			}
			
		}else{
			
			// Zaten aynı dil o yüzden newlang metodunu urlden silmek için tekrar aynı sayfaya yönlendiriyoruz.
			
			// URL Değiştir
			if($NewLangInfo['lang_id']=='ar-sa'){
			
				header("Location:".$ArabicURL);
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$EnglishURL);
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
		<title>Properties List - Ideal Home in Turkey</title>
		<meta name="description" content="Ideal Home in Turkey is the Turkey’s Best Real Estate Investment Advisory and Brokerage Company and Giving Best Price Guarantees and Free Services.">
		<meta name="keywords" content="Ideal Home in Turkey, Turkey Properties, All Properties in Turkey, Residence, Flats, Apartments, Villa, Office, Hotel Apartments Sales in Turkey.">
		<meta property="og:title" content=" Ideal Home in Turkey | Investment Advisory & Brokerage Company " />
		<meta property="og:description" content="Ideal Home in Turkey is the Turkey’s Best Real Estate Investment Advisory and Brokerage Company and Giving Best Price Guarantees and Free Services." />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="theme-color" content="#e51c25"/>
		<link rel="icon" type="image/x-icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="/manifest.json">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
		
		<?php
			if(isset($_GET['parameters']) AND trim($_GET['parameters'])!=''){
				echo '
		<META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
		';
			}
		?>
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
	  
		.pb_property_list_desc{
			
			float: left; margin: 5px 0px 10px 30px;
			
		}
	  
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
			
			.listing_wrapper.col-md-12 .property_listing .listing_details {
				
				padding-left: 15px;
	
			}
			
			.listing_actions {
				top: none!important;
				bottom: 11px!important;
			}
			.listing_unit_price_wrapper {
				
				width: 90%;
				position: relative;
			
			}
			
			.listing_unit_price_wrapper {
				
				left: 15px;
			
			}
			
			
		}
	  
	  </style>
	  
	<!-- MAIN -->
	<div class="col-md-12" style="background-image: url(https://www.idealhomeinturkey.com/uploads/project_list_bg.jpg); background-size: 100% 100%; height: 245px; position: relative; margin: 0 auto; padding: 0 10px;">
			<div class="container" style="padding-bottom: 10px; color: #fff; font-weight: bolder; text-shadow: 2px 3px 5px #000; font-size: 25px; position: relative; max-width: 960px; margin: 0 auto; padding: 0 10px; height: 100%; bottom: -210px;"><?php echo $Lang['listeleme_anabaslik']; ?></div>
	</div>

<div class="container" style="margin-bottom:100px;">
	<div id="content">
	<div class="col-md-12 search_project" style="margin-top:15px;margin-bottom:25px;background-color: rgba(0, 0, 0, 0.55); padding: 25px;">
	
         <form class="form-inline" action="javascript:void(0);" method="post">
            <div class="form-group" style="width:18%;">
               <select class="form-control" name="city" id="search_city" style="width:100%;">
                  <option value="0"><?php echo $Lang['arama_sehir']; ?></option>
				  <?php
					$CityList = $DB->query("SELECT * FROM ihit_projects_citys ORDER BY rank ASC");
					foreach($CityList->fetchAll(PDO::FETCH_ASSOC) as $City){
				  ?>
                  <option value="<?php echo $City['id']; ?>"<?php if(isset($getCity) AND $getCity==$City['id']){ echo ' SELECTED'; } ?>><?php echo $City['title']; ?></option>
				  <?php } ?>
               </select>
            </div>
            <div class="form-group adv" style="width:18%;">
               <select name="type" style="width:100%;" id="search_type" class="form-control">
                  <option value=""><?php echo $Lang['arama_konuttipi']; ?></option>
				  <?php
				  
					$ProjectTypeList = $DB->query("SELECT * FROM ihit_projects_types WHERE lang_id = '".$CurrentLangInfo['id']."' ORDER BY rank ASC");
					foreach($ProjectTypeList->fetchAll(PDO::FETCH_ASSOC) as $Type){
				  ?>
                  <option value="<?php echo $Type['id']; ?>"<?php if(isset($getType) AND $getType==$Type['id']){ echo ' SELECTED'; } ?>><?php echo $Type['title']; ?></option>
				  <?php } ?>
               </select>
            </div>
            <div class="form-group adv" style="width:25%;">
               <select name="project_status" style="width:100%;" id="search_ps" class="form-control">
                  <option value=""><?php echo $Lang['arama_projedurumu']; ?></option>
                  <option value="1"<?php if(isset($getPs) AND $getPs==1){ echo ' SELECTED'; } ?>><?php echo $Lang['arama_projedurumu_hazir']; ?></option>
                  <option value="2"<?php if(isset($getPs) AND $getPs==2){ echo ' SELECTED'; } ?>><?php echo $Lang['arama_projedurumu_devamediyor']; ?></option>
               </select>
            </div>
            <div class="form-group" style="width:18%;">
               <input type="text" id="search_text" style="border: 1px solid #dfdfdf;width:100%;" class="form-control auto" name="text"<?php if(isset($getText) AND $getText!='0'){?>value="<?php echo addslashes(htmlspecialchars(strip_tags($getText))); ?>"<?php }else{?>placeholder="<?php echo $Lang['arama_aramakelimesi']; ?>"<?php } ?> autocomplete="off" />
            </div>
            <div class="form-group" style="width:18%;">
               <input type="submit" onclick="goSearch('<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/';
				
			}
		 
		 ?>');" id="searchPropertySubmit" style="width:100%;" class="btn btn-green" value="<?php echo $Lang['arama_aramayap']; ?>" />
            </div>
         </form>
	
	</div>
	
			<!-- Tab İçeriği -->
			<?php
			
				// Arama Filtereleri $extSearchSql
				$extSearchSql = NULL;
				$SearchSqlArray = array();
				
				if(isset($getCity) AND $getCity!="0"){
					
					$City = (int)$getCity;
					
					// Güvenlik kontrolü >> Bu şehir veritabanımızda kayıtlı değil.
					
					$SearchSqlArray[] = 'city_id = '.$City;
					
				}
				
				if(isset($getPs) AND $getPs!="0"){
					
					$ProjectStatus = (int)$getPs;
					
					$SearchSqlArray[] = 'project_status = '.$ProjectStatus;
					
				}
				
				if(isset($getText) AND $getText!="0"){
				
					$Text = addslashes(htmlspecialchars($getText));
					
					$SearchSqlArray[] = '(description LIKE \'%'.$Text.'%\' OR short_text LIKE \'%'.$Text.'%\' OR slogan LIKE \'%'.$Text.'%\' OR project_id LIKE \'%'.$Text.'%\')';
					
				}
				
				$TypeInner = NULL;
				$TypeSearch = NULL;
				
				if(isset($getType) AND $getType!="0"){
					
					$Type = (int)$getType;
					
					$TypeInner = 'INNER JOIN ihit_project_types_id as types_table';
					$TypeSearch = ' AND types_table.project_id = ihit_projects.id AND types_table.type_id = \''.$getType.'\' ';
					
				}
				
				if(count($SearchSqlArray)<1 AND !isset($Type)){
				
					$ListQuery = "SELECT *,CONVERT(SUBSTRING_INDEX(project_id,'-',-1),UNSIGNED INTEGER) AS project_id_int FROM ihit_projects WHERE status = '1' AND lang_id = '".$CurrentLangInfo['id']."' ORDER BY project_id_int ASC";
				
				}else{
					
					if(count($SearchSqlArray)>0){
						$extSearchSql = 'AND ';
					}
					$extSearchSql .= implode(' AND ',$SearchSqlArray);
					$ListQuery = "SELECT *,CONVERT(SUBSTRING_INDEX(ihit_projects.project_id,'-',-1),UNSIGNED INTEGER) AS project_id_int,ihit_projects.project_id AS getproject_id,ihit_projects.id AS projectdbid FROM ihit_projects ".$TypeInner." WHERE status = '1' AND lang_id = '".$CurrentLangInfo['id']."' ".$extSearchSql." ".$TypeSearch."ORDER BY project_id_int ASC";
					
				}
				
				// Sayfa da kaç içerik gösterilecek
				$Sayfada = (int)15;
				
				// Listelenecek Proje Sayısı
				$CountProject = $DB->query($ListQuery);
				$TotalProject = $CountProject->rowCount();
				
				if($TotalProject < 1){
				
					echo '<h1 style="padding: 25px; text-align: center; width: 100%; display: inline-block;">'.$Lang['listeleme_bulunamadi'].'</h1>';
				
				}else{
				
					$ToplamSayfa = ceil($TotalProject / $Sayfada);
					
					$Sayfa = (int)$_GET['page'];
					if($Sayfa < 1){ $Sayfa = 1; }
					if($Sayfa > $ToplamSayfa){ $Sayfa = $ToplamSayfa; }
					
					$Limit = ($Sayfa -1 ) * $Sayfada;
					
					// Type ID ve Lang Id Ye Göre Projeleri Listeletiyoruz
					$ProjectList = $DB->query($ListQuery." LIMIT $Limit,$Sayfada");
					
					foreach($ProjectList->fetchAll(PDO::FETCH_ASSOC) as $Project){
						
						// Şehir Bilgileri
						$getCityInfo = $DB->query("SELECT * FROM ihit_projects_citys WHERE id = '".$Project['city_id']."'");
						$getCityInfo = $getCityInfo->fetch(PDO::FETCH_ASSOC);
						
						// Fotoğraf Bilgileri
						$getImage = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$Project['projectdbid']."' ORDER BY id ASC LIMIT 1");
						$getImage = $getImage->fetch(PDO::FETCH_ASSOC);
						
						$firstImage = $getImage['image'];
					
			?>
				<div class="col-md-12 listing_wrapper">
					<div class="property_listing">
					<!-- LIST RESIM -->
						<div class="col-md-4" style="padding: 0px;">
							<a href="<?php

							if($DilSef=='ar-sa'){
								
								echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($Project['city_id'],'sef'); ?>/<?php echo $Project['getproject_id'];

							}elseif($DilSef=='en-us'){
								
								echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($Project['city_id'],'sef'); ?>/<?php echo $Project['getproject_id'];
								
							}

							?>/">
								<div style="background-image:url('<?php echo $MAIN_URL; ?>/uploads/project/<?php echo $firstImage; ?>'); width: 100%; background-size: 100% 100%; height: 165px;">&nbsp;</div>
							</a>
						</div>
						
						<div class="col-md-8" style="margin-top:10px;">
							
							<!-- BAŞLIK -->
							<h4><a href="<?php
											
											if($DilSef=='ar-sa'){
												
												echo $MAIN_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($Project['city_id'],'sef'); ?>/<?php echo $Project['getproject_id'];
											
											}elseif($DilSef=='en-us'){
												
												echo $MAIN_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($Project['city_id'],'sef'); ?>/<?php echo $Project['getproject_id'];
												
											}
											
											?>/"><?php echo $Project['getproject_id']; ?></a></h4>
						
							<!-- DETAYLAR -->
							<div class="property_location" style="margin-top:5px;"><?php echo $getCityInfo['title']; ?></div>
						
						</div>
						
						<!-- AÇIKLAMA -->
						<div class="col-md-8 properties_excerpt" style="margin-top: 5px; height: 55px; overflow: hidden;">
							<?php echo $Project['short_text']; ?>
						</div>
						<!-- FIYAT VE PAYLAŞ BUTONLARI -->
						<div class="col-md-8 listing_unit_price_wrapper">
							<?php echo $Lang['fiyat_minimum']; ?>  <?php echo $Project['price']; ?>
						</div>
					</div>
				</div><!-- list bitti -->
						<div class="clearfix"></div>
					<?php } // Proje Listesi Foreach ?>
					
			<ul class="pagination pagination-lg">
			<?php
				for($i=1; $i<=$ToplamSayfa; $i++){
			?>
			  <li<?php if($i==$Sayfa){ echo ' class="active"'; }?>><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
			  <?php } ?>
			</ul>
				<?php } // Else rowCount < 1 ?>
		</div>
	</div>
</div>
<?php require_once("footer.php"); ?>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/scripts.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script type="text/javascript">

function goSearch(URL) {
	
	var search_ps = $("#search_ps").val();
	var search_text = $("#search_text").val();
	var search_type = $("#search_type").val();
	var search_city = $("#search_city").val();
	$("#searchPropertySubmit").attr("disabled", "disabled");
	
	if(search_ps==''){ var search_ps = 0; }
	if(search_text==''){ var search_text = 0; }
	if(search_type==''){ var search_type = 0; }
	if(search_city==''){ var search_city = 0; }
	
	
	window.location.replace("<?php
	
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/home/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/home/';
				
			}
			
			?>"+search_city+"_"+search_type+"_"+search_ps+"_"+search_text);
	
	
}

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