$("document").ready(function() {
    if ($(window).width() < 479) {} else {}
    $("#contact_form").validationEngine('attach');
	
    var telInput = $("#myphonen"),
        errorMsg = $("#error-msg"),
        validMsg = $("#valid-msg");
    telInput.intlTelInput({
        utilsScript: "assets/js/utils.js",
        autoHideDialCode: true,
        autoPlaceholder: true,
        customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
            return "e.g. " + selectedCountryPlaceholder;
        },
        separateDialCode: false,
        nationalMode: false,
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
                swal("شكل الهاتف غير صالح", " ", "error");
                telInput.addClass("phone-error");
                errorMsg.removeClass("hide");
            }
        }
    });
    telInput.on("keyup change", reset);
    setTimeout(function() {
        $(".sloganust").animate({
            left: '40px'
        }, "slow");
    }, 3500);
    setTimeout(function() {
        $(".slogan").animate({
            left: '40px'
        }, "slow");
    }, 4000);
    setTimeout(function() {
        $(".slogannew").animate({
            left: '95px'
        }, "slow");
    }, 4700);
});