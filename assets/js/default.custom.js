/* Custom */

	function chooseLanguage(lang_id){

		window.location.replace("?newlang="+lang_id);
		
	}

/* Custom */

$(function(){
	
    $("#writeus .modal-dialog .modal-body .writeus-input .dropdown-menu > li > a").on("click", function(){
        var numbers = $(this).attr("data-number") + " ";
        $("#input-number").val(numbers).focus();
        return false;
    });

    var text = $("#search_city").find('option:selected').text();
    $("#search_city").parent().find('.selectbox-text').html(text);
    $("#search_city").on("change",function(){
        var text = $(this).find('option:selected').text();
        $(this).parent().find('.selectbox-text').html(text);
    });

    var text = $("#search_type").find('option:selected').text();
    $("#search_type").parent().find('.selectbox-text').html(text);
    $("#search_type").on("change",function(){
        var text = $(this).find('option:selected').text();
        $(this).parent().find('.selectbox-text').html(text);
    });

    var text = $("#search_ps").find('option:selected').text();
    $("#search_ps").parent().find('.selectbox-text').html(text);
    $("#search_ps").on("change",function(){
        var text = $(this).find('option:selected').text();
        $(this).parent().find('.selectbox-text').html(text);
    });

    var text = $("#search_pr").find('option:selected').text();
    $("#search_pr").parent().find('.selectbox-text').html(text);
    $("#search_pr").on("change",function(){
        var text = $(this).find('option:selected').text();
        $(this).parent().find('.selectbox-text').html(text);
    });

    $(".btn-mobile-menu").on("click", function(){
        $("#mobile-menu").addClass("active");
    });

    $(".mobile-menu-close").on("click", function(){
        $("#mobile-menu").removeClass("active");
    });
    $.each( $("[data-image]"), function(key, value){
        var backgroundImage = $("[data-image]:eq("+key+")").attr("data-image");
        $("[data-image]:eq("+key+")").css('background-image', 'url("'+backgroundImage+'")');
    });

    $.each( $("[data-page-title]"), function(key, value){
        var backgroundImage = $("[data-page-title]:eq("+key+")").attr("data-page-title");
        $("[data-page-title]:eq("+key+")").css('background-image', 'url("'+backgroundImage+'")');
    });

    $.each( $("[data-slider-image]"), function(key, value){
        var backgroundImage = $("[data-slider-image]:eq("+key+")").attr("data-slider-image");
        $("[data-slider-image]:eq("+key+")").css('background-image', 'url("'+backgroundImage+'")');
        $("[data-slider-image]:eq("+key+")").on("click", function(){
            $("#carousel-single-slider .carousel-indicators li").removeClass("active");
            $("#carousel-single-slider .carousel-indicators li[data-slide-to="+key+"]").addClass("active");
            $("#carousel-single-slider .carousel-inner .item").removeClass("active");
            $("#carousel-single-slider .carousel-inner .item:eq("+key+")").addClass("active");
        });
    });

    $.each( $("[data-thumb-image]"), function(key, value){
        var backgroundImage = $("[data-thumb-image]:eq("+key+")").attr("data-thumb-image");
        $("[data-thumb-image]:eq("+key+")").css('background-image', 'url("'+backgroundImage+'")');
    });

    $("#homepage-tab .nav li a").on("click", function(){
        var homeTabOffset = $("#homepage-tab .tab-content").position().top - 80;
        var body = $("html, body");
        body.stop().animate({scrollTop:homeTabOffset}, '500', 'swing');
    });

});