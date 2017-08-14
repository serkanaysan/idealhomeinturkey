<?php
ob_start();
session_start();
header("Content-Type:text/html; Charset=UTF-8;");
DEFINE('PAGE','LIST');
require_once('includes/Database.php');
require_once('includes/Functions.php');

if(isset($_GET['parameters']) AND trim($_GET['parameters'])!=''){

	$Parameters = addslashes(htmlspecialchars(strip_tags($_GET['parameters'])));

	$ParseGetParameter = explode('_',$Parameters);
	
	$getCity = (int)$ParseGetParameter[0];
	$getType = (int)$ParseGetParameter[1];
	$getPs = (int)$ParseGetParameter[2];
	$getText = addslashes(htmlspecialchars(strip_tags($ParseGetParameter[3])));
	$getPriceRange = (int)$ParseGetParameter[4];
	
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
	<title><?php
	if($DilSef=='ar-sa'){
		
		echo 'قائمة خصائق ايديل هوم ان تركي';
	
	}else{
	
		echo 'Properties List - Ideal Home in Turkey';
	
	}
	?></title>
	<meta name="description" content="<?php
	if($DilSef=='ar-sa'){
		
		echo 'ايديل هوم ان تركي واحده من افضل شركات الاستثمار و التطوير و الخدمات العقاريه في تركيا و اللتي تعطي افضل الاسعار و الخدمات المجانيه . ';
	
	}else{
	
		echo 'Ideal Home in Turkey is the Turkey’s Best Real Estate Investment Advisory and Brokerage Company and Giving Best Price Guarantees and Free Services.';
	
	}
	?>">
	<meta property="og:title" content="<?php
	if($DilSef=='ar-sa'){
		
		echo 'قائمة خصائق ايديل هوم ان تركي';
	
	}else{
	
		echo 'Properties List - Ideal Home in Turkey';
	
	}
	?>" />
	<meta property="og:description" content="<?php
	if($DilSef=='ar-sa'){
		
		echo 'ايديل هوم ان تركي واحده من افضل شركات الاستثمار و التطوير و الخدمات العقاريه في تركيا و اللتي تعطي افضل الاسعار و الخدمات المجانيه . ';
	
	}else{
	
		echo 'Ideal Home in Turkey is the Turkey’s Best Real Estate Investment Advisory and Brokerage Company and Giving Best Price Guarantees and Free Services.';
	
	}
	?>" />
	<?php
		if(isset($_GET['parameters']) AND trim($_GET['parameters'])!=''){
			echo '<meta name="robots" content="noindex,follow">';
		}
	?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="yandex-verification" content="ef4331785146da1a" />
	<meta name="p:domain_verify" content="a817793898e51a3ba5d427a98eb5839f"/>
	<meta name="theme-color" content="#e51c25"/>
	<meta name="dns-prefix" content="//ajax.googleapis.com"/>
	<meta name="dns-prefix" content="//maxcdn.bootstrapcdn.com"/>
	<meta name="dns-prefix" content="//www.googleadservices.com"/>
	<meta name="dns-prefix" content="//cdn.sendpulse.com"/>
    <link href="<?php echo $MAIN_URL; ?>/assets/css/combined.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php require_once('header.php'); ?>
<?php

	$PageHeaderImages = array(
		//$MAIN_URL.'/assets/img/title.jpg',
		$MAIN_URL.'/assets/img/list-header.jpg',
	);

?>
<section id="page-title" data-page-title="<?php echo $PageHeaderImages[array_rand($PageHeaderImages)]; ?>">
    <h1 class="page-title-text"><?php echo $Lang['listeleme_anabaslik']; ?></h1>
</section>
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
							<option value="1"<?php if(isset($getPriceRange) AND $getPriceRange==1){ echo ' SELECTED'; } ?>>0 $ - 50.000 $</option>
							<option value="2"<?php if(isset($getPriceRange) AND $getPriceRange==2){ echo ' SELECTED'; } ?>>50.000 $ - 100.000 $</option>
							<option value="3"<?php if(isset($getPriceRange) AND $getPriceRange==3){ echo ' SELECTED'; } ?>>100.000 $ - 150.000 $</option>
							<option value="4"<?php if(isset($getPriceRange) AND $getPriceRange==4){ echo ' SELECTED'; } ?>>150.000 $ - 200.000 $</option>
							<option value="5"<?php if(isset($getPriceRange) AND $getPriceRange==5){ echo ' SELECTED'; } ?>>200.000 $ - 250.000 $</option>
							<option value="6"<?php if(isset($getPriceRange) AND $getPriceRange==6){ echo ' SELECTED'; } ?>>250.000 $ - 300.000 $</option>
							<option value="7"<?php if(isset($getPriceRange) AND $getPriceRange==7){ echo ' SELECTED'; } ?>>300.000 $ - 400.000 $</option>
							<option value="8"<?php if(isset($getPriceRange) AND $getPriceRange==8){ echo ' SELECTED'; } ?>>400.000 $ - 500.000 $</option>
							<option value="9"<?php if(isset($getPriceRange) AND $getPriceRange==9){ echo ' SELECTED'; } ?>>500.000 $ - 1.000.000 $</option>
							<option value="10"<?php if(isset($getPriceRange) AND $getPriceRange==10){ echo ' SELECTED'; } ?>>1.000.000 $ +</option>
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
<section id="properties-info">
    <div class="container clearfix">
        <div class="properties-info-buttons clearfix">
            <div class="dropdown">
                <button id="dLabel" class="btn btn-md dropdown-button-colored" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Order By
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dLabel">
                    <li><a href="?orderby=price&amp;order=asc">Price (asc)</a></li>
                    <li><a href="?orderby=price&amp;order=desc">Price (desc)</a></li>
                    <li class="divider"></li>
                    <li><a href="?orderby=projectname&amp;order=asc">Project Name (asc)</a></li>
                    <li><a href="?orderby=projectname&amp;order=desc">Project Name (desc)</a></li>
                </ul>
            </div>
            <button class="btn btn-default btn-md collapsemapbtn" type="button" data-toggle="collapse" data-target="#collapseMap" aria-expanded="true" aria-controls="collapseMap">
                <i class="fa fa-map-marker"></i>
                Map
            </button>
        </div>
        <div class="clearfix"></div>
        <div class="collapse" id="collapseMap">
            <div id="map"></div>
        </div>
    </div>
</section>
<section id="properties">
    <div class="container">
        <div class="row">
			<?php
			
			// Yeni Tasarımdaki Liste Renkleri
			//$SupportedColors = array('blue');
		
			// Arama Filtreleri $extSearchSql
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
			
			if(isset($getPriceRange) AND $getPriceRange!="0"){
				
				$PriceRange = (int)$getPriceRange;
				
				$SearchSqlArray[] = 'price_range = '.$PriceRange;
				
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
			
			$ListQueryOrderBy = ' ORDER BY project_id_int ASC';
			
			if(isset($_GET['orderby']) AND isset($_GET['order'])){
				
				$getOrderby = addslashes(htmlspecialchars(strip_tags($_GET['orderby'])));
				$getOrder = addslashes(htmlspecialchars(strip_tags($_GET['order'])));
					
				// if asc = asc elseif desc = desc ELSE order = asc
				if($getOrder == 'asc'){
					$getOrder = 'asc';
				}elseif($getOrder == 'desc'){
					$getOrder = 'desc';
				}else{
					$getOrder = 'asc';
				}
				
				// orderby
				if($getOrderby == 'price'){
					
					$ListQueryOrderBy = " ORDER BY CAST(SUBSTRING_INDEX(price, '$', -1) AS SIGNED) ".$getOrder;
					
				}elseif($getOrderby == 'projectname'){
					
					$ListQueryOrderBy = " ORDER BY project_id_int ".$getOrder;
					
				}
				
			}
			
			
			if(count($SearchSqlArray)<1 AND !isset($Type)){
			
				$ListQuery = "SELECT *,CONVERT(SUBSTRING_INDEX(project_id,'-',-1),UNSIGNED INTEGER) AS project_id_int,ihit_projects.project_id AS getproject_id FROM ihit_projects WHERE status = '1' AND lang_id = '".$CurrentLangInfo['id']."'".$ListQueryOrderBy;
			
			}else{
				
				if(count($SearchSqlArray)>0){
					$extSearchSql = 'AND ';
				}
				$extSearchSql .= implode(' AND ',$SearchSqlArray);
				$ListQuery = "SELECT *,CONVERT(SUBSTRING_INDEX(ihit_projects.project_id,'-',-1),UNSIGNED INTEGER) AS project_id_int,ihit_projects.project_id AS getproject_id,ihit_projects.id AS projectdbid FROM ihit_projects ".$TypeInner." WHERE status = '1' AND lang_id = '".$CurrentLangInfo['id']."' ".$extSearchSql." ".$TypeSearch.$ListQueryOrderBy;
				
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
					
				$MapProjectList = array();
				
				foreach($ProjectList->fetchAll(PDO::FETCH_ASSOC) as $Project){
					
					// Şehir Bilgileri
					$getCityInfo = $DB->query("SELECT * FROM ihit_projects_citys WHERE id = '".$Project['city_id']."'");
					$getCityInfo = $getCityInfo->fetch(PDO::FETCH_ASSOC);
					
					// Fotoğraf Bilgileri
					$getImage = $DB->query("SELECT * FROM ihit_project_photos WHERE project_id = '".$Project['projectdbid']."' ORDER BY id ASC LIMIT 1");
					$getImage = $getImage->fetch(PDO::FETCH_ASSOC);
					
					$firstImage = $getImage['image'];
					
					if($DilSef=='ar-sa'){
						
						$ProjectURL = $MAIN_URL.'/ar-sa/تركيا-العقارات/'.getCityInfo($Project['city_id'],'sef').'/'.$Project['getproject_id'].'/';

					}elseif($DilSef=='en-us'){
						
						$ProjectURL = $MAIN_URL.'/en-us/turkey-real-estate/'.getCityInfo($Project['city_id'],'sef').'/'.$Project['getproject_id'].'/';
						
					}
					
					if($Project['project_status']==1){
						$ProjectStatus = $Lang['arama_projedurumu_hazir'];
					}elseif($Project['project_status']==2){
						$ProjectStatus = $Lang['arama_projedurumu_devamediyor'];
					}else{
						$ProjectStatus = 'N/A';
					}
					
					//$ProjectColor = array_rand($SupportedColors);
					
					//Harita için diziye aktarıyoruz.
					$MapProjectList[] = array(
						'ProjectID' => $Project['getproject_id'],
						'Price' => $Project['price'],
						'Media' => $firstImage,
						'MapLat' => $Project['map_lat'],
						'MapLng' => $Project['map_lng'],
						'ProjectURL' => $ProjectURL
					);
				
		?>
			<!-- Prop -->
            <div class="col-md-4 col-sm-6">
                <div class="properties-card-container clearfix">
                    <a href="<?php echo $ProjectURL; ?>" title="<?php echo $getCityInfo['title']; ?> <?php echo $Project['getproject_id']; ?> Project">
                        <div class="properties-card-image">
                            <div class="properties-card blue">
                                <img src="" class="properties-card-image lazyload" data-original="https://www.idealhomeinturkey.com/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/project/<?php echo $firstImage; ?>&w=360&h=255&q=75&zc=0" />
                                <div class="properties-card-info">
                                    <span class="city"><?php echo $getCityInfo['title']; ?></span>
                                    <span class="type"><?php echo $Project['getproject_id']; ?></span>
                                </div>
                                <div class="properties-card-coast">
                                    <?php echo $Project['price']; ?>
                                </div>
                                <div class="properties-card-status">
                                    <?php echo $ProjectStatus; ?>
                                </div>
                                <a class="properties-card-link" href="<?php echo $ProjectURL; ?>" title="Card"></a>
                            </div>
                        </div>
                        <p class="properties-card-paragraph"><?php echo strip_tags($Project['short_text']); ?></p>
                        <a class="btn btn-properties btn-review" href="<?php echo $ProjectURL; ?>"><?php echo $Lang['list_review']; ?></a>
                        <button class="btn btn-properties btn-contactus" data-toggle="modal" data-target="#listcontactus" onclick="ListProjectID('<?php echo $Project['getproject_id']; ?>')"><?php echo $Lang['list_contactus']; ?></a>
                    </a>
                </div>
            </div>
			<!-- Prop -->
			<?php } // Foreach ?>
			<?php } // Else rowCount < 1 ?>
        </div>
        <nav class="pagination-container">
            <ul class="pagination">
			<?php
				for($i=1; $i<=$ToplamSayfa; $i++){
			?>
			  <li<?php if($i==$Sayfa){ echo ' class="active"'; }?>><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
			<?php } ?>
            </ul>
        </nav>
    </div>
</section>
<?php require_once('footer.php'); ?>
</body>
</html>