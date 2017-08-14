<?php
ob_start();
session_start();
header("Content-Type:text/html; Charset=UTF-8;");
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

if($CurrentLangInfo['status']!=1){
	
	// Girilen dil durumu aktif değil, ingilizce sayfaya yönlendir.
	header("Location:".$MAIN_URL."/en-us/contact");
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
			
				header("Location:".$MAIN_URL."/ar-sa/التواصل/");
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$MAIN_URL."/en-us/contact");
				exit;
				
			}else{
				
				header("Location:".$MAIN_URL);
				exit;
				
			}
			
		}else{
			
			// Zaten aynı dil o yüzden newlang metodunu urlden silmek için tekrar aynı sayfaya yönlendiriyoruz.
			
			// URL Değiştir
			if($NewLangInfo['lang_id']=='ar-sa'){
			
				header("Location:".$MAIN_URL."/ar-sa/التواصل/");
				exit;
			
			}elseif($NewLangInfo['lang_id']=='en-us'){
				
				header("Location:".$MAIN_URL."/en-us/contact");
				exit;
				
			}else{
				
				header("Location:".$MAIN_URL);
				exit;
				
			}
			
		}
		
	} // Bu dil yok, istenirse 404 yönlendirilebilir else ile.
	
}

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php
	
	if($DilSef=='ar-sa'){
		
		echo 'اتصل بنا / ايديل هوم ان تركي';
	
	}else{
	
		echo 'Contact Us | Ideal Home in Turkey';
	
	}
	?></title>
	<meta name="description" content="<?php
	if($DilSef=='ar-sa'){
		
		echo 'تستطيع الايجاد في هذه الصفحه رقم هاتف ايديل هوم ان تركي و البريد الالكتروني , العنوان , ساعات العمل و قابلية ملئ الاستماره التواصل .';
	
	}else{
	
		echo 'Ideal Home in Turkey’s Contact Page. You can find Ideal Home in Turkey’s Telephone Number, Address, Mail Address, Working Hours and Able To Fill Out Contact Form.';
	
	}
?>" />
	<meta property="og:title" content="Contact Us | Ideal Home in Turkey" />
	<meta property="og:description" content="Ideal Home in Turkey’s Contact Page. You can find Ideal Home in Turkey’s Telephone Number, Address, Mail Address, Working Hours and Able To Fill Out Contact Form." />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="#e51c25"/>
	<meta name="yandex-verification" content="ef4331785146da1a" />
	<meta name="p:domain_verify" content="a817793898e51a3ba5d427a98eb5839f"/>
	<meta name="dns-prefix" content="//ajax.googleapis.com"/>
	<meta name="dns-prefix" content="//maxcdn.bootstrapcdn.com"/>
	<meta name="dns-prefix" content="//www.googleadservices.com"/>
	<meta name="dns-prefix" content="//cdn.sendpulse.com"/>
    <!-- Stylesheets -->
    <link href="<?php echo $MAIN_URL; ?>/assets/css/combined.css" rel="stylesheet" type="text/css">
	<style type="text/css">
	#page{
	    background-color: rgb(250, 250, 250);
		margin-top:0px;
		margin-bottom: -20px;
		padding: 25px;	
	}
	
	.shadow{
		-webkit-box-shadow: 0px 1px 10px 2px rgba(0, 0, 0, 0.1);
		-moz-box-shadow: 0px 1px 10px 2px rgba(0, 0, 0, 0.1);
		box-shadow: 0px 1px 10px 2px rgba(0, 0, 0, 0.1);
	}
	
	.contactform-maingrid{
		background-color: #fff;
		margin-top: 10px;
		padding: 10px 25px 25px 25px;
		transform: rotate(4deg);
		border-left: 3px dotted #d8d8d8;
	}
	.contactform-leftmain{
		height: 386px;
		background-color: #fff;
		background-image: url(https://idealhomeinturkey.com/integration/assets/img/idealpus.jpg);
		background-repeat: no-repeat;
		background-size: 100% 100%;
		background-position-y: 45px;
		border-right: 3px dotted #d8d8d8;
	}
	
	.contactform-title{
		font-size: 24px;
		margin-bottom: 25px;
		font-weight: bold;
	}
	
	.contactpage-addressinfo{
		position: relative;
		padding: 15px;
		margin-top: 15px;
		width: 100%;
		padding-left: 80px;
		background-color: #fafafa;
	}
	
	#contact-map{
		margin-top:25px;
		padding:0;
	}
	
	.intl-tel-input .country-list{
		width:340px!important;
	}
	
	</style>
</head>
<body>
<?php require_once('header.php'); ?>
<section id="page-title" data-page-title="<?php echo $MAIN_URL; ?>/assets/img/contact<?php echo rand(1,2); ?>.jpg">
    <h1 class="page-title-text"><?php echo $Lang['iletisimsayfasi_contactus']; ?></h1>
</section>
<section id="page">
    <div class="container">
        <div class="row">
			<div class="col-md-9 shadow contactform-leftmain">
			
				<div class="col-md-12 contactform-another" style="padding-left: 0;">
				
					<div class="contactpage-addressinfo shadow">
						<h4 style="font-size: 16px; font-weight: bold; width: 220px; display: inline-block;"><?php echo $Lang['iletisimsayfasi_adres']; ?></h4>
						<span style="font-size: 14px; text-align: left; display: inline-block;">
							<i class="fa fa-map-marker" style="font-size: 60px; left: 25px; top: 15px; color: rgb(230, 27, 38); display: block; position: absolute;"></i>
							Caglayan Mahallesi Park Sokak Park Is Merkezi No:10 Kat:4 D:412 34403 Kagithane, Istanbul Turkey
							</span>
					</div>

					<div class="contactpage-addressinfo shadow">
						<h4 style="font-size: 16px; font-weight: bold; display: inline-block;"><?php echo $Lang['iletisimsayfasi_workinghours']; ?></h4>
						<span style="font-size: 14px; text-align: left; width: 100%;    display: inline-block;">Weekdays: 09.00 AM - 18.00 PM <?php if(!$detect->isMobile()){ echo '--'; } ?> Weekends: 09.00 AM -  15.00 PM
							<i class="fa fa-clock-o" style="font-size: 60px; left: 15px; top: 15px; color: rgb(230, 27, 38); display: block; position: absolute;"></i>
						</span>
					</div>

					<div class="contactpage-addressinfo shadow">
						<h4 style="font-size: 16px; font-weight: bold; display: inline-block;"><?php echo $Lang['iletisimsayfasi_contactinformation']; ?></h4>
						<span style="font-size: 14px; text-align: left; "><br />Phone : <a href="tel:+90 212 988 1350">+90 212 988 13 50</a><?php if(!$detect->isMobile()){ ?> <br /> <?php } ?> E-Mail : <a href="mailto:info@idealhomeinturkey.com">info@idealhomeinturkey.com</a>
							<i class="fa fa-phone" style="font-size: 60px; left: 15px; top: 15px; color: rgb(230, 27, 38); display: block; position: absolute;"></i>
						</span>
					</div>
					
				</div>
				
            </div>
			<div class="col-md-3 shadow contactform-maingrid">
				<h4 class="contactform-title"><?php echo $Lang['iletisimsayfasi_sendusamessage']; ?></h4>				
				<!-- Contact Form -->
					<form class="contact-form" method="post" action="javascript:void(0);" id="contact_form_contact">
							<div class="contactpage-input">
								<input type="text" name="fullname" id="fullname" placeholder="<?php echo $Lang['form_adsoyad']; ?>" class="form-control">
							</div>
							<div class="contactpage-input">
								<input type="text" class="form-control" name="email" id="email" placeholder="<?php echo $Lang['form_eposta']; ?>">
							</div>
							<div class="contactpage-input">
									<input id="phone_number_contact" class="form-control" type="tel" />
							</div>
							<div class="contactpage-input">
								<textarea rows="4" class="form-control required" id="message" name="message" placeholder="<?php echo $Lang['form_mesaj']; ?>"></textarea>
							</div>
							<input type="hidden" id="formname" name="formname" value="Contact Form" />
							<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
							<button type="submit" onclick="sendForm('contact_form_contact','phone_number_contact');" id="submitted" class="btn contactpage-button"><?php echo $Lang['form_gonder']; ?></button>
					</form>
				<!-- Contact Form -->
				
            </div>
			<div class="col-md-12" id="contact-map">
			<iframe
			style="width:100%;border:0"
			height="300"
			frameborder="0"
			src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAeB_gxckxN1Np70JPXA9sP5xjxYYJhr94&q=Ideal+Home+in+Turkey&zoom=11" allowfullscreen>
			</iframe></div>
        </div>
		
			
    </div>
</section>
<?php require_once('footer.php'); ?>
<script type="text/javascript">
    var telInput = $("#phone_number_contact"),
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
		utilsScript: "https://www.idealhomeinturkey.com/assets/js/utils.js",
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

</script>
</body>
</html>