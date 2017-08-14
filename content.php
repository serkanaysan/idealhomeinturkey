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
<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Meta -->
    <meta charset="utf-8">
	<title><?php echo $PageInfo['title']; ?> - Ideal Home in Turkey</title>
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
		#header{
			border-bottom: 1px solid #216caf;
		}
	</style>
</head>
<body>
<?php require_once("header.php"); ?>
<section id="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-inner-title"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?>><span><?php echo $PageInfo['title']; ?></span></h1>
                <img class="img-responsive page-inner-img" src="<?php echo $REAL_URL; ?>/uploads/page/<?php echo $PageInfo['image']; ?>" style="border-radius:10px;max-height:400px;width:100%;" alt="<?php echo $PageInfo['title']; ?>"/>
            </div>
			<?php
				if($PageInfo['menu_id']!=0){
			?>
				<div class="col-md-3"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?>>
					<div class="list-group list-group-page">
						<?php
							$MenuList = $DB->query("SELECT * FROM ihit_menu WHERE menu_id = '".$PageInfo['menu_id']."' AND lang_id = '".$CurrentLangInfo['id']."' ORDER BY rank ASC");
							foreach($MenuList->fetchAll(PDO::FETCH_ASSOC) as $Menu){
						?>
							<a href="<?php echo $Menu['url']; ?>" class="list-group-item"><?php echo $Menu['title']; ?></a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
            <div class="<?php if($PageInfo['menu_id']!=0){ echo 'col-md-9'; }else{ echo 'col-md-12'; } ?>">
                <?php echo $PageInfo['content']; ?>
            </div>
        </div>
    </div>
</section>
<?php require_once("footer.php"); ?>
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