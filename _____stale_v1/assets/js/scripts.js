/*
	idealhomeinturkey.com
	Main Javascript File
*/
jQuery(document).ready(function() {
	
	$('body').on('touchstart.dropdown', '.mobile-menu-sub-list', function (e) { e.stopPropagation(); })
	.on('touchstart.dropdown', '.mobile-menu-sub-list', function (e) { e.stopPropagation(); });
	
	/* -----------------------------------------------------------------------------------------------
     *  Mobile Menu Js
     ------------------------------------------------------------------------------------------------*/
    $('#mobile-menu .collapse').on('show.bs.collapse', function () {
        $(this).parent().addClass("active");
    });

    $('#mobile-menu .collapse').on('hide.bs.collapse', function () {
        $(this).parent().removeClass("active");
    });

    $(".header-bars").on("click", function(){
        $("#mobile-menu")
            .removeClass("hidden")
            .removeClass("animated")
            .removeClass("slideOutLeft")
            .addClass("animated")
            .addClass("slideInLeft");
        $("body").addClass("scroll");
    });

    $(".mobile-menu-close").on("click", function(){
        $("#mobile-menu")
            .removeClass("animated")
            .removeClass("slideInLeft")
            .addClass("animated")
            .addClass("slideOutLeft");
        $("body").removeClass("scroll");
    });

    var width, height;
    width = jQuery(window).width();
    height = jQuery(window).height();

    jQuery(window).scroll(function() {
        "use strict";
        var scroll = jQuery(window).scrollTop();
        if (scroll >= 200) {
            jQuery('.contact-box').addClass('islivecb');
            jQuery('.backtop').addClass('islive');
            jQuery('.next_property').addClass('islive4np');
        } else {
            jQuery('.contact-box').removeClass('islivecb');
            jQuery('.backtop').removeClass('islive');
            jQuery('.next_property').removeClass('islive4np');
            jQuery('.contactformwrapper').addClass('hidden');
        }
    });

    $('#scroll-image').on('click', function() {
        $('html, body').animate({
            scrollTop: 1
        }, 800);
        return false;
    });

    $('.contact-box').click(function(event) {
        event.preventDefault();
        $('.contactformwrapper').toggleClass('hidden');
    });

    jQuery('.lt_tab').find('.lt_tab_content .lt_content').hide();
    jQuery('.lt_tab').find('.lt_tab_content .lt_content:first').show();

    jQuery('.lt_nav li').click(function() {
        jQuery(this).parent().find('li span').removeClass("active");
        jQuery(this).find('span').addClass("active");
        jQuery(this).parent().parent().find('.lt_tab_content .lt_content').hide();

        var indexer = jQuery(this).index();
        jQuery(this).parent().parent().find('.lt_tab_content .lt_content:eq(' + indexer + ')').fadeIn();
    });
	


    jQuery(document).ready(function($) {
		
        "use strict";
        var screen_width, screen_height, map_tab;

        map_tab = 0
        $('#propmaptrigger').click(function() {
            if (map_tab === 0) {
                wpestate_map_shortcode_function();
                map_tab = 1;

            }
        });

        $('.slider_container').each(function(index) {
            var autoscroll_slider = parseInt($(this).find('.shortcode_slider_wrapper').attr('data-auto'));
            var element;
            var slideTimer;

            element = $(this).find(".slider_control_right");

            if (autoscroll_slider !== 0) {
                slideTimer = setInterval(function() {
                    slider_control_right_function(element);
                }, autoscroll_slider);
            }

            $(this).find('.slider_control_right').click(function() {
                clearInterval(slideTimer);
                slider_control_right_function($(this));
                if (autoscroll_slider !== 0) {
                    slideTimer = setInterval(function() {
                        slider_control_right_function(element);
                    }, autoscroll_slider);
                }
            });


            $(this).find('.slider_control_left').click(function() {
                clearInterval(slideTimer);
                slider_control_left_function($(this));
                if (autoscroll_slider !== 0) {
                    slideTimer = setInterval(function() {
                        slider_control_right_function(element);
                    }, autoscroll_slider);
                }
            });


        });




        function slider_control_left_function(element) {
            var step_size, margin_left, new_value, last_element, base_value, parent;
            parent = element.parent();
            step_size = parent.find('.shortcode_slider_list').width();
            margin_left = parseInt(parent.find('.shortcode_slider_list').css('margin-left'), 10);
            new_value = margin_left - 370;
            base_value = -15;
            parent.find('.shortcode_slider_list').css('margin-left', new_value + 'px');
            last_element = parent.find('.shortcode_slider_list li:last-child');
            parent.find('.shortcode_slider_list li:last-child').remove();
            parent.find('.shortcode_slider_list').prepend(last_element);
            restart_js_after_ajax();

            parent.find('.shortcode_slider_list').animate({
                'margin-left': base_value
            }, 400, function() {

            });
        }

        function slider_control_right_function(elemenet) {
            var step_size, margin_left, new_value, first_element, parent;
            parent = elemenet.parent();

            step_size = parent.find('.shortcode_slider_list').width();
            margin_left = parseInt(parent.find('.shortcode_slider_list').css('margin-left'), 10);
            new_value = margin_left - 370;

            parent.find('.shortcode_slider_list').animate({
                'margin-left': new_value
            }, 400, function() {
                first_element = parent.find('.shortcode_slider_list li:nth-child(1)');
                parent.find('.shortcode_slider_list li:nth-child(1)').remove();
                parent.find('.shortcode_slider_list').append(first_element);
                parent.find('.shortcode_slider_list').css('margin-left', -15 + 'px');
                restart_js_after_ajax();
            });
        }


    });

    function restart_js_after_ajax() {
        "use strict";
        wpestate_lazy_load_carousel_property_unit();
        if (typeof enable_half_map_pin_action == 'function') {
            enable_half_map_pin_action();
        }
        var newpage, post_id, post_image, to_add, icon;

        jQuery('.prop-compare:first-of-type').remove();


        jQuery('.pagination_ajax_search a').click(function(event) {
            event.preventDefault();
            newpage = parseInt(jQuery(this).attr('data-future'), 10);
            document.getElementById('scrollhere').scrollIntoView();
            start_filtering_ajax(newpage);
        });

        jQuery('.pagination_ajax a').click(function(event) {
            event.preventDefault();
            newpage = parseInt(jQuery(this).attr('data-future'), 10);
            document.getElementById('scrollhere').scrollIntoView();
            start_filtering(newpage);
        });

        jQuery('.property_listing').click(function() {
            var link;
            link = jQuery(this).attr('data-link');
            window.open(link, '_self');
        });

        jQuery('.share_unit').click(function(event) {
            event.stopPropagation();
        });

        var already_in = [];
        jQuery('.compare-action').unbind('click');
        jQuery('.compare-action').click(function(e) {

            e.preventDefault();
            e.stopPropagation();
            jQuery('.prop-compare').show();

            post_id = jQuery(this).attr('data-pid');


            for (var i = 0; i < already_in.length; i++) {
                if (already_in[i] === post_id) {
                    return;
                }
            }

            already_in.push(post_id);
            post_image = jQuery(this).attr('data-pimage');

            to_add = '<div class="items_compare ajax_compare" style="display:none;"><img src="' + post_image + '" alt="compare_thumb" class="img-responsive"><input type="hidden" value="' + post_id + '" name="selected_id[]" /></div>';
            jQuery('div.items_compare:first-child').css('background', 'red');
            if (parseInt(jQuery('.items_compare').length, 10) > 3) {
                jQuery('.items_compare:first').remove();
            }
            jQuery('#submit_compare').before(to_add);
            jQuery('.items_compare').fadeIn(800);
        });

        jQuery('#submit_compare').unbind('click');
        jQuery('#submit_compare').click(function() {
            jQuery('#form_compare').trigger('submit');
        });

        jQuery('.icon-fav').click(function(event) {
            event.stopPropagation();
            icon = jQuery(this);
            add_remove_favorite(icon);
        });

        jQuery(".share_list, .icon-fav, .compare-action").hover(
            function() {
                jQuery(this).tooltip('show');
            },
            function() {
                jQuery(this).tooltip('hide');
            });

        jQuery('.share_list').click(function(event) {
            event.stopPropagation();
            var sharediv = jQuery(this).parent().find('.share_unit');
            sharediv.toggle();
            jQuery(this).toggleClass('share_on');
        });


    }

    function wpestate_lazy_load_carousel_property_unit() {
        jQuery('.property_unit_carousel img').each(function(event) {
            var new_source = '';
            new_source = jQuery(this).attr('data-lazy-load-src');
            if (typeof(new_source) !== 'undefined' && new_source !== '') {
                jQuery(this).attr('src', new_source);
            }
        });
    }


});