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

function wpcbUpdatedVisitType(newvisittype, wpcbBoxId){
        
        var $wpcbTracker = jQuery('.wpcb-tracker');
        
        $wpcbTracker.each(function(){
            if(jQuery(this).data('boxid') == wpcbBoxId){
                var trackerId = jQuery(this).data('id');
                var data = {
                    action: 'update_visit_type',
                    id: trackerId,
                    newvisittype: newvisittype
                };
                jQuery.post(wpcbLocalizedData.ajaxurl, data, function(response) {
                    if(response){
                        $wpcbTracker.data('visittype', newvisittype);
                    }
                });
                return false;
            }
        });
        
}

(function ( $ ) {
	"use strict";

	$(function () {
                // Updated 1.2.5.1
                    
                window.boxLoadDone = new Array();
                window.boxwidth = jQuery('.box_make_sticky').outerWidth();

                // Check if the box isvisible directly

                var $wpcbTemplateMain = $('.wpcb_template_main');
        
                if($wpcbTemplateMain.length > 0){
                    
                    $wpcbTemplateMain.each(function(){
                        var wpcbBoxId = $(this).data('boxid')
                        if($(this).visible() && jQuery.inArray(wpcbBoxId, boxLoadDone) == -1){
                            if($(this).siblings('.wpcb_box_slide_in').length > 0){
                                $(this).hide();
                                $(this).show('slide', {direction: $(this).siblings('.wpcb_box_slide_in').data('from')}, $(this).siblings('.wpcb_box_slide_in').data('speed'));
                            }
                            var fadeTime = $(this).data('fadetime');
                            $(this).fadeOut(fadeTime * 500).fadeIn(fadeTime * 500);
                            if(wpcbLocalizedData.gaTracking == 0){
                                wpcbUpdatedVisitType('boxview',wpcbBoxId);
                            }
                            else{
                                var wpcbBoxName = $(this).data('boxname');
                                ga('send', 'event', wpcbBoxName, 'Boxviews', window.location.pathname );
                            }
                            boxLoadDone.push(wpcbBoxId);
                        }
                    });

                    // When box is shown

                    $(window).scroll(function(){

                        $wpcbTemplateMain.each(function(){
                            var wpcbBoxId = $(this).data('boxid')
                            if($(this).visible() && jQuery.inArray(wpcbBoxId, boxLoadDone) == -1){
                                if($(this).siblings('.wpcb_box_slide_in').length > 0){
                                    $(this).hide();
                                    $(this).show('slide', {direction: $(this).siblings('.wpcb_box_slide_in').data('from')}, $(this).siblings('.wpcb_box_slide_in').data('speed'));
                                }
                                var fadeTime = $(this).data('fadetime');
                                $(this).fadeOut(fadeTime * 500).fadeIn(fadeTime * 500);
                                if(wpcbLocalizedData.gaTracking == 0){
                                    wpcbUpdatedVisitType('boxview',wpcbBoxId);
                                }
                                else{
                                    var wpcbBoxName = $(this).data('boxname');
                                    ga('send', 'event', wpcbBoxName, 'Boxviews', window.location.pathname );
                                }
                                boxLoadDone.push(wpcbBoxId);
                            }
                        });

                        sticky_relocate();

                    });

                    $(document).on('click','.wpcb_box_button', function(){
                        var wpcbBoxId = this.id.match(/\d+/);
                        var wpcbBoxName = $(this).closest('.wpcb_template_main').data('boxname');
                        var href = $(this).attr("href");
                        if(href === undefined){
                            var wpcbName = $(this).closest('.wpcb_template_main').find('#wpcb_name').val();
                            var wpcbEmail = $(this).closest('.wpcb_template_main').find('#wpcb_email').val();
                            var redirect_url = $(this).closest('.wpcb_template_main').find('.wpcb_mailer_data').data('redirect-url');
                            var wpcbMailerID = $(this).closest('.wpcb_template_main').find('.wpcb_mailer_data').data('mailer-id');
                            var wpcbCampaignID = $(this).closest('.wpcb_template_main').find('.wpcb_mailer_data').data('campaign-name');
                            var wpcbTrakcerID = $(this).closest('.wpcb_template_main').siblings('.wpcb-tracker').data('id');
                            var boxProcessingHead = $(this).closest('.wpcb_template_main').siblings('.wpcb-after-optin-messages').data('box-processing-head');
                            var boxTakingTooLong = $(this).closest('.wpcb_template_main').siblings('.wpcb-after-optin-messages').data('box-taking-too-long');
                            var boxSuccessHead = $(this).closest('.wpcb_template_main').siblings('.wpcb-after-optin-messages').data('box-success-head');
                            var boxSuccessDesc = $(this).closest('.wpcb_template_main').siblings('.wpcb-after-optin-messages').data('box-success-desc');
                            var boxErrorHead = $(this).closest('.wpcb_template_main').siblings('.wpcb-after-optin-messages').data('box-error-head');
                            var boxErrorDesc = $(this).closest('.wpcb_template_main').siblings('.wpcb-after-optin-messages').data('box-error-desc');

                            if(wpcbName === '' && wpcbEmail === ''){
                                $(this).closest('.wpcb_template_main').find('#wpcb_name').css('border','2px red solid');
                                $(this).closest('.wpcb_template_main').find('#wpcb_email').css('border','2px red solid');
                            }
                            else if(wpcbName === ''){
                                $(this).closest('.wpcb_template_main').find('#wpcb_name').css('border','2px red solid');
                                $(this).closest('.wpcb_template_main').find('#wpcb_email').attr('style','');
                            }
                            else if(wpcbEmail === ''){
                                $(this).closest('.wpcb_template_main').find('#wpcb_email').css('border','2px red solid');
                                $(this).closest('.wpcb_template_main').find('#wpcb_name').attr('style','');
                            }
                            else{
                                $(this).closest('.wpcb_template_main').find('#wpcb_name').attr('style','');
                                $(this).closest('.wpcb_template_main').find('#wpcb_email').attr('style','');
                                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                if(regex.test(wpcbEmail)){
                                    var divHeight = $('.wpcb_template_main_'+wpcbBoxId).height();
                                    $('.wpcb_template_main_'+wpcbBoxId).html('<script>setTimeout("jQuery(\'.wpcb-processing-body\').show()", 7000);</script><div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">'+boxProcessingHead+'</div><div class="wpcb-loading"></div><div class="wpcb-processing-body" style="display: none;"><p>'+boxTakingTooLong+'</p></div></div>');
                                    var data1 = {
                                        action: 'add_new_contact',
                                        name: wpcbName,
                                        email: wpcbEmail,
                                        mailer_id: wpcbMailerID,
                                        campaign_id: wpcbCampaignID,
                                        tracker_id : wpcbTrakcerID
                                    };
                                    jQuery.post(wpcbLocalizedData.ajaxurl, data1, function(theresponse) {
                                        var response = theresponse.substr(theresponse.length - 1);
                                        if(response == 1){
                                            $('.wpcb_template_main_'+wpcbBoxId).html('<div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">'+boxSuccessHead+'</div><div class="wpcb-processing-body">'+boxSuccessDesc+'</div></div>');
                                            if(wpcbLocalizedData.gaTracking != 0){
                                                ga('send', 'event', wpcbBoxName, 'Optins', window.location.pathname );
                                            }
                                            if(redirect_url)
                                                window.location.href = redirect_url;
                                        }
                                        else{
                                            $('.wpcb_template_main_'+wpcbBoxId).html('<div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">'+boxErrorHead+'</div><div class="wpcb-processing-body">'+boxErrorDesc+'</div></div>');
                                        }
                                    });                                    
                                }
                                else{
                                    $(this).closest('.wpcb_template_main').find('#wpcb_email').css('border','2px red solid');
                                }
                            }
                        }
                        else{
                            if(wpcbLocalizedData.gaTracking == 0){
                                wpcbUpdatedVisitType('click',wpcbBoxId);
                            }
                            else{
                                ga('send', 'event', wpcbBoxName, 'Clicks', window.location.pathname );
                            }
                        }

                    });
                }
                
	});

}(jQuery));