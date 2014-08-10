// All tracking related JS

function wpcbCreateCookie(name,value,hours) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(hours*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function wpcbReadCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function sticky_relocate() {
    var window_top = jQuery(window).scrollTop();
    var div_top = jQuery('.box_make_sticky_offset').offset().top;
    if (window_top > div_top) {
        jQuery('.box_make_sticky').addClass('wpcb_stick');
    } else {
        jQuery('.box_make_sticky').removeClass('wpcb_stick');
    }
}

function wpcbUpdatedVisitType(newvisittype){
        
        var data = {
            action: 'update_visit_type',
            id: jQuery('.wpcb-tracker').data('id'),
            newvisittype: newvisittype
        };
        
        jQuery.post(trackerDefaultData.ajaxurl, data, function(response) {
            if(response){
                jQuery('.wpcb-tracker').data('visittype', newvisittype);
            }
        });
        
}

(function ( $ ) {
	"use strict";

	$(function () {
                
                $(document).ready(function(){
                    
                    window.boxLoadDone = '';
                    
                    // Check if the box isvisible directly
                    
                    if($('.wpcb_template_main').visible() && window.boxLoadDone != 'done'){
                        var fadeTime = $('.wpcb_fade_in').data('fadetime');
                        $('.wpcb_fade_in').fadeOut(fadeTime * 500).fadeIn(fadeTime * 500);
                        window.boxLoadDone = 'done';
                        wpcbUpdatedVisitType('boxview');
                    }
                    
                    // When box is shown
                    
                    $(window).scroll(function(){
                        
                        if($('.wpcb_template_main').visible() && window.boxLoadDone != 'done'){
                            var fadeTime = $('.wpcb_fade_in').data('fadetime');
                            $('.wpcb_fade_in').fadeOut(fadeTime * 500).fadeIn(fadeTime * 500);
                            window.boxLoadDone = 'done';
                            wpcbUpdatedVisitType('boxview');
                        }
                        
                        sticky_relocate();
                        
                    });
                    
                    
                    
                    $('.wpcb_box_button').click(function(){
                        var href = $(this).attr("href");
                        wpcbUpdatedVisitType('click');
                    });
                    
                });
                
	});

}(jQuery));