<?php
ob_start();
header("Content-Type:text/xml; Charset=UTF-8");
require_once('includes/Database.php');
require_once('includes/Functions.php');

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

$CurrentLangInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE lang_id = :lang_id");
$CurrentLangInfo->execute(array(':lang_id'=>$DilSef));
$CurrentLangInfo = $CurrentLangInfo->fetch(PDO::FETCH_ASSOC);

echo '<?xml version="1.0" encoding="UTF-8"?>'; // PHP HATASI OLDUĞU İÇİN ECHO İÇİNE ALDIK

?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

   <url>

      <loc><?php echo $MAIN_URL; ?>/</loc>

      <priority>1</priority>

   </url>

   <url>

      <loc><?php echo $MAIN_URL; ?>/en-us/</loc>

      <priority>1</priority>

   </url>

   <url>

      <loc><?php echo $MAIN_URL; ?>/ar-sa/</loc>

      <priority>1</priority>

   </url>

	<?php
	
		// Bu dil de ki Aktif projeleri listeleyelim.
		$Projects = $DB->prepare("SELECT * FROM ihit_projects WHERE lang_id = :lang_id AND status = :status");
		$Projects->execute(array(':lang_id'=>$CurrentLangInfo['id'],':status'=>1));
		
		foreach($Projects->fetchAll(PDO::FETCH_ASSOC) AS $Project){
			
			$City = getCityInfo($Project['city_id'],'sef');
	
	?>

   <url>

      <loc><?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/'.$City.'/'.$Project['project_id'].'/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/'.$City.'/'.$Project['project_id'].'/';
				
			}
		 
		 ?></loc>

   </url>
   
	<?php } ?>
	
	<?php
		
			// Bu dil de ki Aktif sayfaları listeleyelim.
			$Pages = $DB->prepare("SELECT * FROM ihit_pages WHERE lang_id = :lang_id");
			$Pages->execute(array(':lang_id'=>$CurrentLangInfo['id']));
			
			foreach($Pages->fetchAll(PDO::FETCH_ASSOC) AS $Page){
		
		?>

	   <url>

		  <loc><?php
			 
				if($DilSef=='ar-sa'){
					
					echo $MAIN_URL.'/ar-sa/الصفحات/'.$Page['sef'].'/';
					
				}elseif($DilSef=='en-us'){
					
					echo $MAIN_URL.'/en-us/pages/'.$Page['sef'].'/';
					
				}
			 
			 ?></loc>

	   </url>
	   
		<?php } ?>

</urlset> 