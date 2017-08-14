<footer id="footer-section">
<!--
   <div id="footer-section2"></div>
   <a href="#header-section" class="scroll-to" id="to-top"></a>
-->
<div class="container footer-info">
	<div class="row">
	
		<div class="col-md-6">
			<div class="row">
				<div class="col-sm-6 footer-column onscroll-animate fadeIn">
					<div id="text-2" class="widget widget_text home-item home-widget">
						<div class="textwidget">
							<p class="ihit_footer_logo" style="width: 50%; margin: 0 auto; margin-bottom: 10px;">
								<img src="<?php echo $MAIN_URL; ?>/assets/img/footer_logo_white.png" alt="Ideal Home In Turkey White Footer Logo" />
							</p>
							<p><?php echo $Lang['footer_aciklama']; ?></p>
						</div>
					</div>
				</div>
				<div class="col-sm-6 footer-column onscroll-animate fadeIn" data-delay="400">
					<div id="recent-posts-3" class="widget widget_recent_entries home-item home-widget">
						<h4 class="home-widget-title"><?php echo $Lang['footer_linkler']; ?></h4>
						<div class="clear"></div>
						<ul>
							<?php
							$getFooterLinks = $DB->prepare("SELECT * FROM ihit_footerlink WHERE lang_id = :lang_id ORDER BY id ASC");
							$getFooterLinks->execute(array(':lang_id'=>$CurrentLangInfo['id']));

							foreach($getFooterLinks->fetchAll(PDO::FETCH_ASSOC) as $Footer){
							?>
							<li>
								<a href="<?php echo $Footer['url']; ?>"><?php echo $Footer['title']; ?></a>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	
		<div class="col-md-6">
			<div class="row">
				<div class="col-sm-6 footer-column font-normal onscroll-animate fadeIn" data-delay="600">
					<div id="text-3" class="widget widget_text home-item home-widget">
						<h4 class="home-widget-title"><?php echo $Lang['footer_kurumsal']; ?></h4>
						<div class="clear"></div>
						<div class="textwidget">
							<div class="icon-opening-wrapper">
								<div class="icon-opening-container">
									<div class="icon-opening"><i class="fa fa-phone"></i></div>
									<div class="icon-opening-content"><a href="tel:<?php echo str_replace(array(' '),null,getSettings('footer_phone')); ?>"><?php echo getSettings('footer_phone'); ?></a></div>
								</div>
							</div>
							<div class="icon-opening-wrapper">
								<div class="icon-opening-container">
									<div class="icon-opening"><i class="fa fa-envelope"></i></div>
									<div class="icon-opening-content"><a href="mailto:<?php echo getSettings('footer_email'); ?>"><?php echo getSettings('footer_email'); ?></a></div>
								</div>
							</div>
							<div class="icon-opening-wrapper">
								<div class="icon-opening-container">
									<div class="icon-opening"><i class="fa fa-globe"></i></div>
									<div class="icon-opening-content"><?php echo getSettings('footer_address'); ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 footer-column onscroll-animate fadeIn" data-delay="800">
					<div id="wysija-3" class="widget widget_wysija home-item home-widget">
						<h4 class="home-widget-title"><?php echo $Lang['footer_hizliiletisim']; ?></h4>
						<div class="clear"></div>
						<form method="post" action="javascript:void(0);" id="contact_form_footer">
							<input type="text" id="fullname" name="fullname" style="width:100%;margin-bottom:5px;" class="wysija-input" placeholder="<?php echo $Lang['form_adsoyad']; ?>" />
							<input type="tel" id="phone_number_footer" style="width:100%;" class="wysija-input" />
							<input type="email" name="email" id="email" style="width:100%;margin-bottom:5px;" class="wysija-input" placeholder="<?php echo $Lang['form_eposta']; ?>" />
							<textarea name="message" id="message" style="float:right;width:100%;overflow:hidden;height:50px;margin-bottom:5px;" class="wysija-input" placeholder="<?php echo $Lang['form_mesaj']; ?>"></textarea>
							<input type="hidden" id="formname" name="formname" value="Footer Form" />
							<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
							<input type="submit" onclick="sendForm('contact_form_footer','phone_number_footer');" id="submitted" style="height:34px;background-color:#0A63AE;width: 100%;" class="btn btn-success" value="<?php echo $Lang['form_gonder']; ?>" />
						</form>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
   
	<div id="map" style="width:100%;height:300px;">
	 <iframe
	  style="width:100%;border:0"
      height="300"
      frameborder="0"
      src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAeB_gxckxN1Np70JPXA9sP5xjxYYJhr94&q=Ideal+Home+in+Turkey&zoom=11" allowfullscreen>
    </iframe></div>
   
	<div class="site-info">
		<div class="container">
			<div class="row">
			<?php
				
				if($detect->isTablet() OR !$detect->isMobile()){
			
			?>
				<div class="col-md-8 footer-copyright onscroll-animate fadeInLeft" data-animation="fadeInLeft">
					<p><?php echo getSettings('footer_text'); ?></p>
				</div>
				<div class="col-md-4 footer-social text-right onscroll-animate fadeInRight" data-animation="fadeInRight">
					<div class="socials-wrapper">
						<?php if(trim(getSettings('facebook_username'))!=''){ ?><div class="social-round-container social-round-container-facebook"><a target="_blank" href="https://www.facebook.com/<?php echo getSettings('facebook_username'); ?>"><i class="fa fa-facebook"></i></a></div><?php } ?>
						<?php if(trim(getSettings('instagram_username'))!=''){ ?><div class="social-round-container social-round-container-instagram"><a target="_blank" href="https://www.instagram.com/<?php echo getSettings('instagram_username'); ?>"><i class="fa fa-instagram"></i></a></div><?php } ?>
						<?php if(trim(getSettings('twitter_username'))!=''){ ?><div class="social-round-container social-round-container-twitter"><a target="_blank" href="https://www.twitter.com/<?php echo getSettings('twitter_username'); ?>"><i class="fa fa-twitter"></i></a></div><?php } ?>
						<?php if(trim(getSettings('linkedin_username'))!=''){ ?><div class="social-round-container social-round-container-linkedin"><a target="_blank" href="https://www.linkedin.com/<?php echo getSettings('linkedin_username'); ?>"><i class="fa fa-linkedin"></i></a></div><?php } ?>
						<?php if(trim(getSettings('youtube_username'))!=''){ ?><div class="social-round-container social-round-container-youtube"><a target="_blank" href="https://www.youtube.com/<?php echo getSettings('youtube_username'); ?>"><i class="fa fa-youtube"></i></a></div><?php } ?>
						<?php if(trim(getSettings('pinterest_username'))!=''){ ?><div class="social-round-container social-round-container-pinterest"><a target="_blank" href="https://www.pinterest.com/<?php echo getSettings('pinterest_username'); ?>"><i class="fa fa-pinterest"></i></a></div><?php } ?>
						<?php if(trim(getSettings('googleplus_username'))!=''){ ?><div class="social-round-container social-round-container-googleplus"><a target="_blank" href="https://plus.google.com/+<?php echo getSettings('googleplus_username'); ?>"><i class="fa fa-google-plus"></i></a></div><?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				
				<div class="col-md-4 footer-social text-right onscroll-animate fadeInRight" data-animation="fadeInRight">
					<div class="socials-wrapper">
						<?php if(trim(getSettings('facebook_username'))!=''){ ?><div class="social-round-container social-round-container-facebook"><a target="_blank" href="https://www.facebook.com/<?php echo getSettings('facebook_username'); ?>"><i class="fa fa-facebook"></i></a></div><?php } ?>
						<?php if(trim(getSettings('instagram_username'))!=''){ ?><div class="social-round-container social-round-container-instagram"><a target="_blank" href="https://www.instagram.com/<?php echo getSettings('instagram_username'); ?>"><i class="fa fa-instagram"></i></a></div><?php } ?>
						<?php if(trim(getSettings('twitter_username'))!=''){ ?><div class="social-round-container social-round-container-twitter"><a target="_blank" href="https://www.twitter.com/<?php echo getSettings('twitter_username'); ?>"><i class="fa fa-twitter"></i></a></div><?php } ?>
						<?php if(trim(getSettings('linkedin_username'))!=''){ ?><div class="social-round-container social-round-container-linkedin"><a target="_blank" href="https://www.linkedin.com/<?php echo getSettings('linkedin_username'); ?>"><i class="fa fa-linkedin"></i></a></div><?php } ?>
						<?php if(trim(getSettings('youtube_username'))!=''){ ?><div class="social-round-container social-round-container-youtube"><a target="_blank" href="https://www.youtube.com/<?php echo getSettings('youtube_username'); ?>"><i class="fa fa-youtube"></i></a></div><?php } ?>
						<?php if(trim(getSettings('pinterest_username'))!=''){ ?><div class="social-round-container social-round-container-pinterest"><a target="_blank" href="https://www.pinterest.com/<?php echo getSettings('pinterest_username'); ?>"><i class="fa fa-pinterest"></i></a></div><?php } ?>
						<?php if(trim(getSettings('googleplus_username'))!=''){ ?><div class="social-round-container social-round-container-googleplus"><a target="_blank" href="https://plus.google.com/+<?php echo getSettings('googleplus_username'); ?>"><i class="fa fa-google-plus"></i></a></div><?php } ?>
					</div>
				</div>
				<div class="col-md-8 footer-copyright onscroll-animate fadeInLeft" data-animation="fadeInLeft">
					<p><?php echo getSettings('footer_text'); ?></p>
				</div>
				
				<?php } ?>
			</div>
		</div>
	</div>
	
	

	<div class="main-menu-alt">
		<div class="container">
			<div class="menu-button"><i class="fa fa-reorder"></i></div>
			<nav id="bottom-menu" class="menu-container menu-container-slide">
				<div class="underscore-container">
					<ul id="menu-menu-1" class="menu">
						<li class="menu-item"><a href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/sitemap.xml"><?php echo $Lang['footer_siteharitasi']; ?></a></li>
						<li class="menu-item"><a href="<?php echo $MAIN_URL; ?><?php echo $Lang['footer_kosullarvegizlilikbildirimi_url']; ?>"><?php echo $Lang['footer_kosullarvegizlilikbildirimi']; ?></a></li>
					</ul>
				</div>
			</nav>
		</div>
	</div>
</footer>
<!--
<a href="#" class="backtop"><i class="fa fa-chevron-up"></i></a>
<a class="contact-box"><i class="fa fa-envelope-o"></i></a>-->

<div class="contactformwrapper hidden">
	<div id="footer-contact-form">
	<div class="closebutton" style="color: #000; float: right; font-size: 15px;"><a style="color: #000;" onclick="closecfw();">X</a></div>
		<h4><?php echo $Lang['scroolform_title']; ?></h4>
		<p style="color:#000!important;"><?php echo $Lang['scroolform_desc']; ?></p>
		<form method="post" action="javascript:void(0);" id="contact_form_scroll">
			<input type="text" id="fullname" name="fullname" style="width:100%;margin-bottom:5px;" class="wysija-input" placeholder="<?php echo $Lang['form_adsoyad']; ?>" />
			<input type="tel" id="phone_number_scroll" style="width:100%;" class="wysija-input" />
			<input type="email" name="email" id="email" style="width:100%;margin-bottom:5px;" class="wysija-input" placeholder="<?php echo $Lang['form_eposta']; ?>" />
			<textarea name="message" id="message" style="float:right;width:100%;overflow:hidden;height:50px;margin-bottom:5px;" class="wysija-input" placeholder="<?php echo $Lang['form_mesaj']; ?>"></textarea>
			<input type="hidden" id="formname" name="formname" value="Scroll Form" />
			<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
			<input type="submit" onclick="sendForm('contact_form_scroll','phone_number_scroll');" id="submitted" style="width: 100%;background-color:#0A63AE;" class="btn btn-success" value="<?php echo $Lang['form_gonder']; ?>" />
		</form>
	</div>
</div>

<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<!--<link rel="stylesheet" type="text/css" href="<?php echo $MAIN_URL; ?>/assets/css/dd.css" />-->
<style type="text/css">
.ddcommon {position:relative;display:-moz-inline-stack; zoom:1; display:inline-block; *display:inline; cursor:default;}
.ddcommon ul{padding:0;margin:0;}
.ddcommon ul li{list-style-type:none;}
.ddcommon .disabled img, .ddcommon .disabled span, .ddcommon.disabledAll{
opacity: .5; /* standard: ff gt 1.5, opera, safari */
-ms-filter:"alpha(opacity=50)"; /* ie 8 */
filter:alpha(opacity=50); /* ie lt 7 */
-khtml-opacity:.5; /* safari 1.x */
-moz-opacity:.5; /* ff lt 1.5, netscape */
color:#999999;
}
.ddcommon .clear{clear:both}
.ddcommon .shadow{-moz-box-shadow:5px 5px 5px -5px #888888;-webkit-box-shadow:5px 5px 5px -5px #888888;box-shadow: 5px 5px 5px -5px #888888;}
.ddcommon .text{color:#7e7e7e;padding:0; position:absolute; background:#fff; display:block; width:98%; height:98%; left:2px; top:0; border:none;}
.ddOutOfVision{position:relative; display:-moz-inline-stack; display:inline-block; zoom:1; *display:inline;}
.borderRadius .shadow{-moz-box-shadow:5px 5px 5px -5px #888888;-webkit-box-shadow:5px 5px 5px -5px #888888;box-shadow: 5px 5px 5px -5px #888888;}
.borderRadiusBtm .shadow{-moz-box-shadow:-5px -5px 5px -5px #888888;-webkit-box-shadow:-5px -5px 5px -5px #888888;box-shadow: -5px -5px 5px -5px #888888}
.borderRadiusTp .border, .borderRadius .border{-moz-border-radius:0 0 5px 5px ; border-radius:0 0 5px 5px;}
.borderRadiusBtm .border{-moz-border-radius:5px 5px 0 0; border-radius:5px 5px 0 0;}
img.fnone{float:none !important}
.ddcommon .divider{width:0; height:100%; position:absolute;}
.ddcommon .arrow{display:inline-block; position:absolute; top:50%; right:4px;}
.ddcommon .arrow:hover{background-position:0 100%;}
.ddcommon .ddTitle{padding:0; position:relative; display:inline-block; width:100%}
.ddcommon .ddTitle .ddTitleText{display:block;}
.ddcommon .ddTitle .ddTitleText .ddTitleText{padding:0;}
.ddcommon .ddTitle .description{display:block;}
.ddcommon .ddTitle .ddTitleText img{position:relative; vertical-align:middle; float:left}
.ddcommon .ddChild{position:absolute;display:none;width:100%;overflow-y:auto; overflow-x:hidden; zoom:1;}
.ddcommon .ddChild li{clear:both;}
.ddcommon .ddChild li .description{display:block;}
.ddcommon .ddChild li img{border:0 none; position:relative;vertical-align:middle;float:left}
.ddcommon .ddChild li.optgroup{padding:0;}
.ddcommon .ddChild li.optgroup .optgroupTitle{padding:0 5px; font-weight:bold; font-style:italic}
.ddcommon .ddChild li.optgroup ul li{padding:5px 5px 5px 15px}
.ddcommon .noBorderTop{border-top:none 0  !important; padding:0; margin:0;}
.dd{border:1px solid #c3c3c3;}
.dd .divider{border-left:1px solid #c3c3c3; border-right:1px solid #fff;; right:24px;}
.dd .arrow{width:16px;height:16px; margin-top:-8px; background:url(../img/dd_arrow.gif) no-repeat;}
.dd .arrow:hover{background-position:0 100%;}
.dd .ddTitle{color:#000;    background-color: #ffffff;
    border-color: #E0E0E0;}
.dd .ddTitle .ddTitleText{padding:5px 20px 5px 5px;}
.dd .ddTitle .ddTitleText .ddTitleText{padding:0;}
.dd .ddTitle .description{font-size:12px; color:#666}
.dd .ddTitle .ddTitleText img{padding-right:5px;}
.dd .ddChild{border:1px solid #c3c3c3; background-color:#fff; left:-1px;}
.dd .ddChild li{padding:5px; background-color:#fff; border-bottom:1px solid #c3c3c3;}
.dd .ddChild li .description{color:#666;}
.dd .ddChild li .ddlabel{color:#333;}
.dd .ddChild li.hover{background-color:#f2f2f2}
.dd .ddChild li img{padding:0 6px 0 0;}
.dd .ddChild li.optgroup{padding:0;}
.dd .ddChild li.optgroup .optgroupTitle{padding:0 5px; font-weight:bold; font-style:italic}
.dd .ddChild li.optgroup ul li{padding:5px 5px 5px 15px}
.dd .ddChild li.selected{background-color:#fafbfb; color:#000;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo $MAIN_URL; ?>/assets/css/flags.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $MAIN_URL; ?>/assets/css/sweetalert.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $MAIN_URL; ?>/assets/css/intlTelInput.css" />
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/intlTelInput.min.js"></script>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/jquery.dd.min.js"></script>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/sweetalert.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" async></script>

<script type="text/javascript">

function closecfw(){
	
	jQuery('.contactformwrapper').addClass('hidden');
	
}

//$( 'a[href^="http://"]' ).attr( 'target','_blank' );
//$( 'a[href^="https://"]' ).attr( 'target','_blank' );
$( 'a[href^="mailto:"]' ).addClass("nohover");

 jQuery('.share_unit').click(function(event){
        event.stopPropagation();
    });
	    jQuery('.share_list').click(function (event) {
        event.stopPropagation();
        var sharediv = jQuery(this).parent().find('.share_unit');
        sharediv.toggle();
        jQuery(this).toggleClass('share_on');
    });

;(function($){
  
  /**
   * jQuery function to prevent default anchor event and take the href * and the title to make a share pupup
   *
   * @param  {[object]} e           [Mouse event]
   * @param  {[integer]} intWidth   [Popup width defalut 500]
   * @param  {[integer]} intHeight  [Popup height defalut 400]
   * @param  {[boolean]} blnResize  [Is popup resizeabel default true]
   */
  $.fn.customerPopup = function (e, intWidth, intHeight, blnResize) {
    
    // Prevent default anchor event
    e.preventDefault();
    
    // Set values for window
    intWidth = intWidth || '500';
    intHeight = intHeight || '400';
    strResize = (blnResize ? 'yes' : 'no');

    // Set title and open popup with focus on it
    var strTitle = ((typeof this.attr('title') !== 'undefined') ? this.attr('title') : 'Social Share'),
        strParam = 'width=' + intWidth + ',height=' + intHeight + ',resizable=' + strResize,            
        objWindow = window.open(this.attr('href'), strTitle, strParam).focus();
  }
  
  /* ================================================== */
  
	  $(document).ready(function ($) {
		  $('#map').click(function () {
		$('#map iframe').css("pointer-events", "auto");
	});

	$( "#map" ).mouseleave(function() {
	  $('#map iframe').css("pointer-events", "none"); 
	});
	  
    $('.customer.share').on("click", function(e) {
      $(this).customerPopup(e);
    });
  });
    
}(jQuery));

$(document).ready(function() {
	$("#countries").msDropdown();
})

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

function chooseLanguage(lang_id){
	
	window.location.replace("?newlang="+lang_id);
	
}


/*
function initMap() {
  var myLatLng = {lat: <?php echo getSettings('map_lat'); ?>, lng: <?php echo getSettings('map_lng'); ?>};

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 11,
	scrollwheel: false,
    center: myLatLng
  });

  var marker = new google.maps.Marker({
    position: myLatLng,
    map: map,
	icon: '<?php echo $MAIN_URL; ?>/assets/img/mapicon.png'
  });
  
  infowindow = new google.maps.InfoWindow({content:'<?php echo $Lang['footer_haritaaciklama']; ?>'});
  google.maps.event.addListener(marker, 'click', function(){infowindow.open(map,marker);});
  infowindow.open(map,marker);
  
}

*/

// Scroll Pop Phone
var telInput = $("#phone_number_scroll"),
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
			swal("<?php echo $Lang['hatamesajlari_telefonnumarasigecersiz']; ?>", " ", "error");
			telInput.addClass("phone-error");
			errorMsg.removeClass("hide");
		}
	}
});
telInput.on("keyup change", reset);
// Footer Phone
var telInput = $("#phone_number_footer"),
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
			swal("<?php echo $Lang['hatamesajlari_telefonnumarasigecersiz']; ?>", " ", "error");
			telInput.addClass("phone-error");
			errorMsg.removeClass("hide");
		}
	}
});
telInput.on("keyup change", reset);


function sendForm(formid,phoneid) {

	// Form Verileri
	var fullname = $("#"+formid).find('#fullname').val();
	var email = $("#"+formid).find('#email').val();
	var message = $("#"+formid).find('#message').val();
	var formname = $("#"+formid).find('#formname').val();
	var page = $("#"+formid).find('#page').val();
	
	// Telefon
	var isValid = $("#"+phoneid).intlTelInput("isValidNumber");
	var Country = $("#"+phoneid).intlTelInput("getSelectedCountryData").name;
	var dialCode = $("#"+phoneid).intlTelInput("getSelectedCountryData").dialCode;
	var iso2 = $("#"+phoneid).intlTelInput("getSelectedCountryData").iso2;
	var phone = $("#"+phoneid).val();
	
	// Butonu kilitleyelim
	$("#"+formid+" > #submitted").attr("disabled", "disabled");
	
	//alert($('li.formlist > .container > article.sliderPost > .sliderPost__content > #contact_form_slider > #sliderfullname').val('farukset'));
	//alert($('li.formlist > .container > article.sliderPost > .sliderPost__content > #contact_form_slider > #sliderfullname').val());
	
	if(isValid){
		
		$.ajax({
			
			type: 'POST',
			url: 'https://www.idealhomeinturkey.com/sendform',
			data: {fullname: fullname, phone: phone, email: email, message: message, country: Country, dialCode: dialCode, iso2: iso2, formname: formname, page: page},
			success: function(gelen) {
			
				if(gelen == "success"){
				
					swal("<?php echo $Lang['hatamesajlari_basarili']; ?>", "<?php echo $Lang['hatamesajlari_formbasariylagonderildi']; ?>", "success");
					$("#"+formid+" > #submitted").removeAttr("disabled");
					$("#"+formid).trigger('reset');
					
					function gaTracker(id){
					  $.getScript('//www.google-analytics.com/analytics.js'); // jQuery shortcut
					  window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
					  ga('create', id, 'auto');
					  ga('send', 'pageview');
					}
					
					var script = document.createElement('script');
					script.src = '//www.google-analytics.com/ga.js';
					document.getElementsByTagName('head')[0].appendChild(script);
					
					function gaTrack(path, title) {
						ga('set', { page: path, title: title });
						ga('send', 'pageview');
					}
					
					gaTracker('UA-79564150-1');

					ga('send', 'event', 'Ideal Web', formname+' | Form Submitted');
					fbq('track', 'Lead');
					
					$.ajax ({
					  type: 'POST',
					  url: 'https://www.idealhomeinturkey.com/formsuccess',
					  data: '',
					  success: function(data){}
					});
					
				}else{
				
					swal("Warning", gelen, "warning");
					$("#"+formid+" > #submitted").removeAttr("disabled");
				
				}
				
			}
			
		});
		
		$("#"+formid+" > #submitted").removeAttr("disabled");
	
	}else{
		
		swal("<?php echo $Lang['hatamesajlari_hata']; ?>", "<?php echo $Lang['hatamesajlari_telefonnumarasigecersiz']; ?>", "warning");
		$("#"+formid+" > #submitted").removeAttr("disabled");
		
	}
	
}

</script>
<script data-skip-moving="true" async>
        (function(w,d,u,b){
                s=d.createElement('script');r=1*new Date();s.async=1;s.src=u+'?'+r;
                h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn.bitrix24.com/b2216803/crm/site_button/loader_2_5lv6h0.js');
</script>

<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter38039385 = new Ya.Metrika({ id:38039385, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/38039385" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->

<?php if(getSettings('callbackhunter')==1){ ?>
<link rel="stylesheet" href="//cdn.callbackhunter.com/widget2/tracker.css" />
<script type="text/javascript" 
src="//cdn.callbackhunter.com/widget2/tracker.js" charset="UTF-8"></script>
<script type="text/javascript">var hunter_code="b7b0a99d6bb030a960e27170a7ce9544";</script>
<?php } ?>