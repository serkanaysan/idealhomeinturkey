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
if(!isset($CurrentLangInfo['id'])){ $CurrentLangInfo['id'] = $DefaultLangInfo['id']; }

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
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Contact Us | Ideal Home in Turkey</title>
	<meta name="description" content="Ideal Home in Turkey’s Contact Page. You can find Ideal Home in Turkey’s Telephone Number, Address, Mail Address, Working Hours and Able To Fill Out Contact Form." />
	<meta property="og:title" content="Contact Us | Ideal Home in Turkey" />
	<meta property="og:description" content="Ideal Home in Turkey’s Contact Page. You can find Ideal Home in Turkey’s Telephone Number, Address, Mail Address, Working Hours and Able To Fill Out Contact Form." />
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
	<link href="<?php echo $MAIN_URL; ?>/assets/css/contact.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $MAIN_URL; ?>/assets/css/fix.css" rel="stylesheet" type="text/css">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Raleway:500,600,700|Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
	<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<style type="text/css">
	
		.property_listing h4 a{
		
			color:#0A63AE!important;
			font-size:20px;
			margin-top:20px;
		
		}
		
		@media (max-width: 992px){
			.site-main{
				margin-top: 100px!important;
			}
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

<div id="content-wrapper" class="site-content-wrapper site-pages">

    <div id="content" class="site-content layout-boxed" style="background-image: url(https://www.idealhomeinturkey.com/uploads/contact_page_istanbul.jpg); background-repeat: no-repeat; background-position: center center; background-size: cover;">

        <div class="container">

            <div class="row">

                <div class="col-xs-12 site-main-content" style="margin-top:20px;margin-bottom:20px;height: 502px;">

                    <main id="main" class="site-main">

                        <div class="row zero-horizontal-margin column-container">

                            <div class="col-md-6 col-left-side contact-details" style="font-family: sans-serif;background-color: rgba(255, 255, 255, 0.9);height:100%">
                                <div class="address-wrapper">
                                    <h3 class="list-title"><?php echo $Lang['iletisimsayfasi_adres']; ?></h3>
                                    <address><i class="fa fa-map-marker"></i>Caglayan Mahallesi Park Sokak Park Is Merkezi No:10 Kat:4 D:412 34403 Kagithane, Istanbul TURKEY</address>
                                </div>


                                <div class="row" style="margin-right: -44px!important;">
                                    <div class="col-sm-6 col-xs-12 col-md-6">
                                        <h3 class="list-title"><?php echo $Lang['iletisim_iletisimbilgileri']; ?></h3>
                                        <ul class="contacts-list">
                                            <li class="phone">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="contacts-icon-container" width="24" height="24" viewBox="0 0 24 24">
                                                    <path class="contacts-icon" fill-rule="evenodd" clip-rule="evenodd" fill="#0a63ae" d="M1.027 4.846l-.018.37.01.181c.068 9.565 7.003 17.42 15.919 18.48.338.075 1.253.129 1.614.129.359 0 .744-.021 1.313-.318.328-.172.448-.688.308-1.016-.227-.528-.531-.578-.87-.625-.435-.061-.905 0-1.521 0-1.859 0-3.486-.835-4.386-1.192l.002.003-.076-.034c-.387-.156-.696-.304-.924-.422-3.702-1.765-6.653-4.943-8.186-8.896-.258-.568-1.13-2.731-1.152-6.009h.003l-.022-.223c0-1.727 1.343-3.128 2.999-3.128 1.658 0 3.001 1.401 3.001 3.128 0 1.56-1.096 2.841-2.526 3.079l.001.014c-.513.046-.914.488-.914 1.033 0 .281.251 1.028.251 1.028.015 0 .131.188.119.188-.194-.539 1.669 5.201 7.021 7.849-.001.011.636.309.636.309.47.3 1.083.145 1.37-.347.09-.151.133-.32.14-.488.356-1.306 1.495-2.271 2.863-2.271 1.652 0 2.991 1.398 2.991 3.12 0 .346-.066.671-.164.981-.3.594-.412 1.21.077 1.699.769.769 1.442-.144 1.442-.144.408-.755.643-1.625.643-2.554 0-2.884-2.24-5.222-5.007-5.222-1.947 0-3.633 1.164-4.46 2.858-2.536-1.342-4.556-3.59-5.656-6.344 1.848-.769 3.154-2.647 3.154-4.849 0-2.884-2.241-5.222-5.007-5.222-2.41 0-4.422 1.777-4.897 4.144l-.091.711z" />
                                                </svg><span><a href="tel:90 212 988 13 50">+90 212 988 13 50</a></span>
                                            </li>
                                            <li class="email">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="contacts-icon-container" width="14px" height="14px" viewBox="0 0 14 14">
                                                    <g class="contacts-icon" fill="#0a63ae">
                                                        <path d="M7 9l-1.732-1.516-4.952 4.245c.18.167.423.271.691.271h11.986c.267 0 .509-.104.688-.271l-4.949-4.245-1.732 1.516zM13.684 2.271c-.18-.168-.422-.271-.691-.271h-11.986c-.267 0-.509.104-.689.273l6.682 5.727 6.684-5.729zM0 2.878v8.308l4.833-4.107zM9.167 7.079l4.833 4.107v-8.311z" />
                                                    </g>
                                                </svg><a href="mailto:info@idealhomeinturkey.com">info@idealhomeinturkey.com</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6 col-xs-12 col-md-6">
                                        <h3 class="list-title"><?php echo $Lang['iletisim_calismasaatleri']; ?></h3>
                                        <ul class="contacts-list">
                                            <li class="icon-clock">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="contacts-icon-container" width="24" height="24" viewBox="0 0 24 24">
                                                    <path class="contacts-icon" fill-rule="evenodd" clip-rule="evenodd" fill="#0DBAE8" d="M12 23.999c-6.627 0-12-5.371-12-11.999 0-6.627 5.373-12 12-12 6.628 0 11.999 5.372 11.999 12s-5.371 11.999-11.999 11.999zm0-21.999c-5.522 0-10 4.478-10 10s4.477 10 10 10 10-4.478 10-10-4.478-10-10-10zm3.567 14.757l-4.275-3.995c-.224-.225-.326-.527-.301-.82l.008-4.942c0-.552.448-1 1.001-1s1.001.448 1.001 1v4.594l3.756 3.974c.33.328.33.86 0 1.189-.329.33-.861.33-1.19 0z" />
                                                </svg><?php echo $Lang['iletisim_haftaici']; ?> : 09:00 - 18:30</li>
                                            <li class="icon-clock"><svg xmlns="http://www.w3.org/2000/svg" class="contacts-icon-container" width="24" height="24" viewBox="0 0 24 24">
                                                    <path class="contacts-icon" fill-rule="evenodd" clip-rule="evenodd" fill="#0DBAE8" d="M12 23.999c-6.627 0-12-5.371-12-11.999 0-6.627 5.373-12 12-12 6.628 0 11.999 5.372 11.999 12s-5.371 11.999-11.999 11.999zm0-21.999c-5.522 0-10 4.478-10 10s4.477 10 10 10 10-4.478 10-10-4.478-10-10-10zm3.567 14.757l-4.275-3.995c-.224-.225-.326-.527-.301-.82l.008-4.942c0-.552.448-1 1.001-1s1.001.448 1.001 1v4.594l3.756 3.974c.33.328.33.86 0 1.189-.329.33-.861.33-1.19 0z" />
                                                </svg><?php echo $Lang['iletisim_haftasonu']; ?> : 09:00 - 18:30</li>
                                        </ul>
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-5 col-right-side contact-form-wrapper" style="margin-left:40px;height:100%;background-color: rgba(23, 28, 38, 0.55);">

                                <section id="contact-form-section">

                                    <h3 class="contact-form-heading"><?php echo $Lang['iletisim_bizemesajgonderin']; ?></h3>
                                    <form class="contact-form" method="post" action="javascript:void(0);" id="contact_form_contact">

                                        <p>
                                            <input id="fullname" class="required" name="fullname" type="text" placeholder="<?php echo $Lang['form_adsoyad']; ?>" />
                                        </p>

                                        <p>
                                            <input id="email" class="email required" name="email" type="text" placeholder="<?php echo $Lang['form_eposta']; ?>" />
                                        </p>

                                        <p>
                                            <input id="phone_number_contact" type="tel" />
                                        </p>

                                        <p>
                                            <textarea id="message" class="required" name="message" placeholder="<?php echo $Lang['form_mesaj']; ?>"></textarea>
                                        </p>
										<input type="hidden" id="formname" name="formname" value="Contact Form" />
										<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
										
                                        <div class="clearfix">
                                            <input type="submit" onclick="sendForm('contact_form_contact','phone_number_contact');" id="submitted" class="btn" style="background-color:#0a63ae;color:#fff;width:100%;" value="<?php echo $Lang['form_gonder']; ?>">
                                        </div>

                                    </form>

                                </section>

                            </div>

                        </div>

                    </main>
                    <!-- .site-main -->

                </div>
                <!-- .site-main-content -->

            </div>
            <!-- .row -->

        </div>
        <!-- .container -->

    </div>
    <!-- .site-content -->

</div>
<!-- .site-content-wrapper -->

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

</script>
</body>
</html>