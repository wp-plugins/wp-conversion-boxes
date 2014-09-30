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

// Updated 1.2.2.1

function sticky_relocate() {
    var window_top = jQuery(window).scrollTop();
    var $boxMakeStickyOffset = jQuery('.box_make_sticky_offset');
    if($boxMakeStickyOffset.length){
        var div_top = $boxMakeStickyOffset.offset().top;
    }
    var $boxMakeSticky = jQuery('.box_make_sticky');
    if (window_top > div_top) {
        $boxMakeSticky.css('width',window.boxwidth);
        $boxMakeSticky.addClass('wpcb_stick');
    } else {
        $boxMakeSticky.removeClass('wpcb_stick');
    }
}

function wpcbUpdatedVisitType(newvisittype){
        
        var $wpcbTracker = jQuery('.wpcb-tracker');
        
        var data = {
            action: 'update_visit_type',
            id: $wpcbTracker.data('id'),
            newvisittype: newvisittype
        };
        
        jQuery.post(trackerDefaultData.ajaxurl, data, function(response) {
            if(response){
                $wpcbTracker.data('visittype', newvisittype);
            }
        });

}

(function ( $ ) {
	"use strict";

	$(function () {
                
                //$(document).ready(function(){
                // Updated 1.2.5.1
                    
                    window.boxLoadDone = '';
                    window.boxwidth = jQuery('.box_make_sticky').outerWidth();
                    
                    // Check if the box isvisible directly
                            
                    var $wpcbTemplateMain = $('.wpcb_template_main');
        
                if($wpcbTemplateMain.length > 0){
        
                    if($wpcbTemplateMain.visible() && window.boxLoadDone != 'done'){
                        wpcbUpdatedVisitType('boxview');
                        var fadeTime = $('.wpcb_fade_in').data('fadetime');
                        $('.wpcb_fade_in').fadeOut(fadeTime * 500).fadeIn(fadeTime * 500);
                        window.boxLoadDone = 'done';
                    }
                    
                    // When box is shown
                    
                    $(window).scroll(function(){
                        
                        if($wpcbTemplateMain.visible() && window.boxLoadDone != 'done'){
                            wpcbUpdatedVisitType('boxview');
                            var fadeTime = $('.wpcb_fade_in').data('fadetime');
                            $('.wpcb_fade_in').fadeOut(fadeTime * 500).fadeIn(fadeTime * 500);
                            window.boxLoadDone = 'done';
                        }
                        
                        sticky_relocate();
                        
                    });
                    
                    $(document).on('click','.wpcb_box_button', function(){
                        var href = $(this).attr("href");
                        if(href === undefined){
                            var wpcbName = $('#wpcb_name').val();
                            var wpcbEmail = $('#wpcb_email').val();
                            var wpcbMailerID = $('.wpcb_mailer_data').data('mailer-id');
                            var wpcbCampaignID = $('.wpcb_mailer_data').data('campaign-name');
                            var wpcbTrakcerID = $('.wpcb-tracker').data('id');
                            
                            if(wpcbName === '' && wpcbEmail === ''){
                                $('#wpcb_name').css('border','2px red solid');
                                $('#wpcb_email').css('border','2px red solid');
                            }
                            else if(wpcbName === ''){
                                $('#wpcb_name').css('border','2px red solid');
                                $('#wpcb_email').attr('style','');
                            }
                            else if(wpcbEmail === ''){
                                $('#wpcb_email').css('border','2px red solid');
                                $('#wpcb_name').attr('style','');
                            }
                            else{
                                $('#wpcb_name').attr('style','');
                                $('#wpcb_email').attr('style','');
                                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                if(regex.test(wpcbEmail)){
                                    var divHeight = $wpcbTemplateMain.height();
                                    $wpcbTemplateMain.html('<script>setTimeout("jQuery(\'.wpcb-processing-body\').show()", 7000);</script><div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">Processing... Please Wait!</div><div class="wpcb-loading"></div><div class="wpcb-processing-body" style="display: none;">It\'s taking longer than usual. Please hang on for a few moments...</div></div>');
                                    var data1 = {
                                        action: 'add_new_contact',
                                        name: wpcbName,
                                        email: wpcbEmail,
                                        mailer_id: wpcbMailerID,
                                        campaign_id: wpcbCampaignID,
                                        tracker_id : wpcbTrakcerID
                                    };
                                    jQuery.post(trackerDefaultData.ajaxurl, data1, function(response) {
                                        response = response.charAt(response.length - 1);
                                        if(response == 1){
                                            $wpcbTemplateMain.html('<div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">Success!</div><div class="wpcb-processing-body">Thanks for subscribing!</div></div>');
                                        }
                                        else{
                                            $wpcbTemplateMain.html('<div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">Error!</div><div class="wpcb-processing-body">There was an error submitting your info.</div></div>');
                                        }
                                    });                                    
                                }
                                else{
                                    $('#wpcb_email').css('border','2px red solid');
                                }
                            }
                        }
                        else{
                            wpcbUpdatedVisitType('click');
                        }
                        
                    });
                }
                    
                //});
                
	});

}(jQuery));