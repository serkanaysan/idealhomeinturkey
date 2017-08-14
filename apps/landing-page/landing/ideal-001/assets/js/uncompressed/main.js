$("document").ready(function () {
     
	if ($(window).width() < 479) {
		//$(".logo img:eq(0)").attr('src', 'assets/imgs/mobile-logo.png');
	}
	else{
		//$(".logo img:eq(0)").attr('src', 'assets/imgs/logo.png');
	}
	
	$("#contact_form").validationEngine('attach');
	//$.featherlight('#mapbox');
				
	var telInput = $("#myphonen"),
	  errorMsg = $("#error-msg"),
	  validMsg = $("#valid-msg");

	// initialise plugin
	telInput.intlTelInput({
		utilsScript: "assets/js/utils.js",
		autoHideDialCode: true,
		autoPlaceholder: true,
		customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
		  return "e.g. " + selectedCountryPlaceholder;
		},
		separateDialCode: false,
		nationalMode: false,
		/*geoIpLookup: function (callback) {
			$.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
				var countryCode = (resp && resp.country) ? resp.country : "";
				callback(countryCode);
			});
		},*/
		//initialCountry: "auto",
	});

	var reset = function() {
	  telInput.removeClass("phone-error");
	  errorMsg.addClass("hide");
	  validMsg.addClass("hide");
	};

	// on blur: validate
	telInput.blur(function() {
	  reset();
	  if ($.trim(telInput.val())) {
		if (telInput.intlTelInput("isValidNumber")) {
		  validMsg.removeClass("hide");
		} else {
		  swal("شكل الهاتف غير صالح", " ", "error");
		  telInput.addClass("phone-error");
		  errorMsg.removeClass("hide");
		}
	  }
	});

	// on keyup / change flag: reset
	telInput.on("keyup change", reset);
	
	setTimeout(
		function() 
		{
			$(".slogan").animate({left: '40px'}, "slow");
		}, 2000);
	
	setTimeout(
		function() 
		{
			$(".slogannew").animate({left: '95px'}, "slow");
		}, 2500);
	
});