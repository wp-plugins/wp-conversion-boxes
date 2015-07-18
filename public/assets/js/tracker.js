/*
* All tracking related JS
* By: Ram Shengale
*/

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
        
                    if($wpcbTemplateMain.visible() && $wpcbTemplateMain.css('display') != 'none' && window.boxLoadDone != 'done'){
                        wpcbUpdatedVisitType('boxview');
                        var fadeTime = $('.wpcb_fade_in').data('fadetime');
                        $('.wpcb_fade_in').fadeOut(fadeTime * 500).fadeIn(fadeTime * 500);
                        window.boxLoadDone = 'done';
                    }
                    
                    // When box is shown
                    
                    $(window).scroll(function(){
                        
                        if($wpcbTemplateMain.visible() && $wpcbTemplateMain.css('display') != 'none' && window.boxLoadDone != 'done'){
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
                                if($('#wpcb_name').css('display') != "none"){
                                    $('#wpcb_name').attr('style','');
                                }
                            }
                            else{
                                if($('#wpcb_name').css('display') != "none"){
                                    $('#wpcb_name').attr('style','');
                                }
                                $('#wpcb_email').attr('style','');
                                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                if(regex.test(wpcbEmail)){
                                    var divHeight = $wpcbTemplateMain.height();
                                    $wpcbTemplateMain.html('<script>setTimeout("jQuery(\'.wpcb-processing-body\').show()", 7000);</script><div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">'+trackerDefaultData.processingHead+'</div><div class="wpcb-loading"></div><div class="wpcb-processing-body" style="display: none;">'+trackerDefaultData.processingBody+'</div></div>');
                                    
                                    if(wpcbMailerID == 11){
                                        var wpcb_feedburner_window = window.open('http://feedburner.google.com/fb/a/mailverify?uri='+wpcbCampaignID+'&email='+wpcbEmail, '_blank', 'scrollbars=yes,width=550,height=520');
                                        var wpcb_feedburner_intervals = setInterval(function(){ 
                                            if(wpcb_feedburner_window.closed){
                                                clearInterval(wpcb_feedburner_intervals);
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
                                                        $wpcbTemplateMain.html('<div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">'+trackerDefaultData.successHead+'</div><div class="wpcb-processing-body">'+trackerDefaultData.successBody+'</div></div>');
                                                    }
                                                });
                                            }
                                        }, 300);   
                                    }
                                    else{
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
                                                $wpcbTemplateMain.html('<div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">'+trackerDefaultData.successHead+'</div><div class="wpcb-processing-body">'+trackerDefaultData.successBody+'</div></div>');
                                            }
                                            else{
                                                $wpcbTemplateMain.html('<div class="wpcb-processing" style="height: '+divHeight+'px;"><div class="wpcb-processing-head">'+trackerDefaultData.errorHead+'</div><div class="wpcb-processing-body">'+trackerDefaultData.errorBody+'</div></div>');
                                            }
                                        });
                                    }
                                    
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
                
                $(document).on('click','a[id*="wpcb_two_step_optin_link_"]', function(){
                    var theid = $(this).attr('id');
                    var id = theid.replace( /^\D+/g, ''); 
                    $('.wpcb_template_main_'+id).lightbox_me({
                        centered: true
                    });
                    wpcbUpdatedVisitType('boxview');
                });
                    
                //});
                
	});

}(jQuery));

/*
* $ lightbox_me
* By: Buck Wilson
* Version : 2.4
*/
!function(e){e.fn.lightbox_me=function(o){return this.each(function(){function i(){d[0].style;s.destroyOnClose?d.add(c).remove():d.add(c).hide(),s.parentLightbox&&s.parentLightbox.fadeIn(200),s.preventScroll&&e("body").css("overflow",""),r.remove(),d.undelegate(s.closeSelector,"click"),d.unbind("close",i),d.unbind("repositon",l),e(window).unbind("resize",t),e(window).unbind("resize",l),e(window).unbind("scroll",l),e(window).unbind("keyup.lightbox_me"),s.onClose()}function n(e){(27==e.keyCode||27==e.DOM_VK_ESCAPE&&0==e.which)&&s.closeEsc&&i()}function t(){e(window).height()<e(document).height()?(c.css({height:e(document).height()+"px"}),r.css({height:e(document).height()+"px"})):c.css({height:"100%"})}function l(){d[0].style;if(d.css({left:"50%",marginLeft:d.outerWidth()/2*-1,zIndex:s.zIndex+3}),d.height()+80>=e(window).height()&&"absolute"!=d.css("position")){var o=e(document).scrollTop()+40;d.css({position:"absolute",top:o+"px",marginTop:0})}else d.height()+80<e(window).height()&&(s.centered?d.css({position:"fixed",top:"50%",marginTop:d.outerHeight()/2*-1}):d.css({position:"fixed"}).css(s.modalCSS),s.preventScroll&&e("body").css("overflow","hidden"))}var s=e.extend({},e.fn.lightbox_me.defaults,o),c=e(),d=e(this),r=e('<iframe id="foo" style="z-index: '+(s.zIndex+1)+';border: none; margin: 0; padding: 0; position: absolute; width: 100%; height: 100%; top: 0; left: 0; filter: mask();"/>');if(s.showOverlay){var a=e(".js_lb_overlay:visible");c=e(a.length>0?'<div class="lb_overlay_clear js_lb_overlay"/>':'<div class="'+s.classPrefix+'_overlay js_lb_overlay"/>')}d.before(c),s.showOverlay&&(t(),c.css({position:"absolute",width:"100%",top:0,left:0,right:0,bottom:0,zIndex:s.zIndex+2,display:"none"}),c.hasClass("lb_overlay_clear")||c.css(s.overlayCSS)),s.showOverlay?c.fadeIn(s.overlaySpeed,function(){l(),d[s.appearEffect](s.lightboxSpeed,function(){t(),l(),s.onLoad()})}):(l(),d[s.appearEffect](s.lightboxSpeed,function(){s.onLoad()})),s.parentLightbox&&s.parentLightbox.fadeOut(200),e(window).resize(t).resize(l).scroll(l),e(window).bind("keyup.lightbox_me",n),s.closeClick&&c.click(function(e){i(),e.preventDefault}),d.delegate(s.closeSelector,"click",function(e){i(),e.preventDefault()}),d.bind("close",i),d.bind("reposition",l)})},e.fn.lightbox_me.defaults={appearEffect:"fadeIn",appearEase:"",overlaySpeed:250,lightboxSpeed:300,closeSelector:".close",closeClick:!0,closeEsc:!0,destroyOnClose:!1,showOverlay:!0,parentLightbox:!1,preventScroll:!1,onLoad:function(){},onClose:function(){},classPrefix:"lb",zIndex:999,centered:!1,modalCSS:{top:"40px"},overlayCSS:{background:"black",opacity:.7}}}(jQuery);