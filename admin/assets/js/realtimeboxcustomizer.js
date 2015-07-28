// Real Time Box Customizer


var templateFields = {};

function getValues(){
    templateFields['heading_text'] = jQuery('#heading_text').val();
    templateFields['heading_font_familiy'] = jQuery('#font_families_heading').val();
    templateFields['heading_font_size'] = jQuery('#heading_font_size').val();
    templateFields['heading_line_height'] = jQuery('#heading_line_height').val();
    templateFields['heading_color'] = jQuery('#heading_color').val();
    templateFields['heading_align'] = jQuery('.heading_align:checked').val();
    templateFields['heading_bg_color'] = jQuery('#heading_bg_color').val();

    templateFields['content_text'] = jQuery('#content_text').val();
    templateFields['content_font_familiy'] = jQuery('#font_families_content').val();
    templateFields['content_font_size'] = jQuery('#content_font_size').val();
    templateFields['content_line_height'] = jQuery('#content_line_height').val();
    templateFields['content_align'] = jQuery('.content_align:checked').val();
    templateFields['content_color'] = jQuery('#content_color').val();

    templateFields['button_text'] = jQuery('#button_text').val();
    templateFields['button_link'] = jQuery('#button_link').val();
    templateFields['button_target_blank'] = (jQuery('#button_target_blank').is(':checked')) ? 'true' : 'false' ;
    templateFields['button_type'] = jQuery('.button_type:checked').val();
    templateFields['button_text_font_familiy'] = jQuery('#font_families_button').val();
    templateFields['button_text_font_size'] = jQuery('#button_text_font_size').val();
    templateFields['button_border_radius'] = jQuery('#button_border_radius').val();
    templateFields['button_align'] = jQuery('.button_align:checked').val();
    templateFields['button_width'] = jQuery('#button_width').val();
    templateFields['button_bg_color'] = jQuery('#button_bg_color').val();
    templateFields['button_text_color'] = jQuery('#button_text_color').val();
    
    templateFields['box_container_width'] = jQuery('#box_container_width').val();
    templateFields['box_container_height'] = jQuery('#box_container_height').val();
    templateFields['box_container_margin_top'] = jQuery('#box_container_top').val();
    templateFields['box_container_margin_bottom'] = jQuery('#box_container_bottom').val();
    templateFields['box_container_margin_left'] = jQuery('#box_container_left').val();
    templateFields['box_container_margin_right'] = jQuery('#box_container_right').val();
    templateFields['box_container_border_width'] = jQuery('#box_container_border_width').val();
    templateFields['box_container_bg_color'] = jQuery('#box_container_bg_color').val();
    templateFields['box_container_border_color'] = jQuery('#box_container_border_color').val();
    
    templateFields['custom_css'] = jQuery('#custom_css').val();
    
    if(jQuery('#font_families_input').val()){
        templateFields['input_text_size'] = jQuery('#input_text_size').val();
        templateFields['input_text_color'] = jQuery('#input_text_color').val();
        templateFields['input_font_family'] = jQuery('#font_families_input').val();
        templateFields['input_width'] = jQuery('#input_width').val();
        templateFields['input_name_placeholder'] = jQuery('#input_name_placeholder').val();
        templateFields['input_email_placeholder'] = jQuery('#input_email_placeholder').val();
        if(jQuery('#input_remove_name_field').is(':checked'))
            templateFields['input_remove_name_field'] = 1;
        else
            templateFields['input_remove_name_field'] = 0;
    }
    
    if(jQuery('#image_url').val()){
        templateFields['image_url'] = jQuery('#image_url').val();
        templateFields['image_width'] = jQuery('#image_width').val();
        templateFields['image_height'] = jQuery('#image_height').val();
        templateFields['image_align'] = jQuery('.image_align:checked').val();
    }
    
    if(jQuery('#video_id').val()){
        templateFields['video_site'] = jQuery('.video_site:checked').val();
        templateFields['video_id'] = jQuery('#video_id').val();
        templateFields['video_width'] = jQuery('#video_width').val();
        templateFields['video_height'] = jQuery('#video_height').val();
        templateFields['video_align'] = jQuery('.video_align:checked').val();
    }
    
}

var otherCSS;

function applyChanges(){
    jQuery('.wpcb_box_heading_text').html(templateFields['heading_text']);
    jQuery('.wpcb_box_heading_text').css('font-family',templateFields['heading_font_familiy']);
    jQuery('.wpcb_box_heading_text').css('color',templateFields['heading_color']);
    jQuery('.wpcb_box_heading_text').css('font-size',templateFields['heading_font_size']);
    jQuery('.wpcb_box_heading_text').css('line-height',templateFields['heading_line_height']);
    jQuery('.wpcb_box_heading_text').css('text-align',templateFields['heading_align']);
    jQuery('.wpcb_box_heading').css('background-color',templateFields['heading_bg_color']);
    
    jQuery('.wpcb_box_content').html(templateFields['content_text']);
    jQuery('.wpcb_box_content').css('font-family',templateFields['content_font_familiy']);
    jQuery('.wpcb_box_content').css('font-size',templateFields['content_font_size']);
    jQuery('.wpcb_box_content').css('line-height',templateFields['content_line_height']);
    jQuery('.wpcb_box_content').css('text-align',templateFields['content_align']);
    jQuery('.wpcb_box_content').css('color',templateFields['content_color']);
    
    jQuery('.wpcb_box_button').text(templateFields['button_text']);
    if(!jQuery('#email_service_provider').val()){
        jQuery('.wpcb_box_button').attr('href', templateFields['button_link']);
        if(templateFields['button_target_blank'] != 'false') jQuery('.wpcb_box_button').attr('target', '_blank');
        else jQuery('.wpcb_box_button').attr('target', '')
    }
    jQuery('.wpcb_box_button').removeClass().addClass('wpcb_box_button ' + templateFields['button_type']);
    jQuery('.wpcb_box_button').css('font-family',templateFields['button_text_font_familiy']);
    jQuery('.wpcb_box_button').css('font-size',templateFields['button_text_font_size']);
    jQuery('.wpcb_box_button').css('color',templateFields['button_text_color']);
    jQuery('.wpcb_box_button_div').css('text-align',templateFields['button_align']);
    jQuery('.wpcb_box_button').css('border-radius',templateFields['button_border_radius']);
    jQuery('.wpcb_box_button').css('width',templateFields['button_width']); 
    
    if(jQuery('#font_families_input').val()){
        jQuery('.wpcb_input_fields').css('font-size',templateFields['input_text_size']);
        jQuery('.wpcb_input_fields').css('color',templateFields['input_text_color']);
        jQuery('.wpcb_input_fields').css('font-family',templateFields['input_font_family']);
        jQuery('.wpcb_input_fields').css('width',templateFields['input_width']);
        jQuery('#wpcb_name').attr('placeholder',templateFields['input_name_placeholder']);
        jQuery('#wpcb_email').attr('placeholder',templateFields['input_email_placeholder']);
        
        if(jQuery('#input_remove_name_field').is(':checked')){
            jQuery('.wpcb_input_fields#wpcb_name').hide();
        }
        else{
            jQuery('.wpcb_input_fields#wpcb_name').show();
        }
            
    }
    
    if(jQuery('#image_url').val()){
            jQuery('.wpcb_box_media_container').css('width',templateFields['image_width']);
            jQuery('.wpcb_box_media_container').css('height',templateFields['image_height']);
            jQuery('.wpcb_box_media_container').attr('class', 'wpcb_box_media_container wpcb_box_media_'+templateFields['image_align']);
    }
    
    if(jQuery('.video_site:checked').val()){
            if(templateFields['video_site'] == 'youtube'){
                jQuery('#video_id_label').text('https://www.youtube.com/watch?v=');
                jQuery('.wpcb_box_video').attr('src','//www.youtube.com/embed/'+templateFields['video_id']);
            }else if(templateFields['video_site'] == 'vimeo'){
                jQuery('#video_id_label').text('https://vimeo.com/');
                jQuery('.wpcb_box_video').attr('src','//player.vimeo.com/video/'+templateFields['video_id']+"?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff");                
            }
            jQuery('.wpcb_box_media_container').css('width',templateFields['video_width']);
            jQuery('.wpcb_box_media_container').css('height',templateFields['video_height']);
            jQuery('.wpcb_box_media_container').attr('class', 'wpcb_box_media_container wpcb_box_media_'+templateFields['video_align']);
    }
    
    jQuery('.wpcb_template_main').css('width',templateFields['box_container_width']);
    jQuery('.wpcb_template_main').css('height',templateFields['box_container_height']);
    jQuery('.wpcb_template_main').css('background-color',templateFields['box_container_bg_color']);    
    jQuery('.wpcb_template_main').css('margin-top',templateFields['box_container_margin_top']);
    jQuery('.wpcb_template_main').css('margin-bottom',templateFields['box_container_margin_bottom']);
    jQuery('.wpcb_template_main').css('margin-left',templateFields['box_container_margin_left']);
    jQuery('.wpcb_template_main').css('margin-right',templateFields['box_container_margin_right']);
    jQuery('.wpcb_template_main').css('border-width',templateFields['box_container_border_width']); 
    jQuery('.wpcb_template_main').css('border-color',templateFields['box_container_border_color']);
    
    otherCSS = 'font-family:'+templateFields['button_text_font_familiy']+';font-size:'+templateFields['button_text_font_size']+
                ';color:'+templateFields['button_text_color']+';text-align:'+templateFields['button_align']+';border-radius:'+templateFields['button_border_radius'];
    
    jQuery('.wpcb_template_main').prev('style').html(templateFields['custom_css']);
    
    jQuery('.wpcb_template_main').css({position: 'absolute', top: jQuery('.wpcb_customizer_wrap').height()/2 - jQuery('.wpcb_template_main').height()/2 - 50 , left: jQuery('.wpcb_box_customizer').width()/2 - jQuery('.wpcb_template_main').width()/2 - 20 }) 
}

function ltrim(str,c){
	while(str.charAt(0) === c){
		str = str.substr(1);	
	}
	return str;
}
function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}
function clamp(min,max,x){
	return Math.min(Math.max(x,min),max);
}
function hslToRgb(h, s, l){
    var r, g, b;

    if(s == 0){
        r = g = b = l; // achromatic
    }else{
        function hue2rgb(p, q, t){
            if(t < 0) t += 1;
            if(t > 1) t -= 1;
            if(t < 1/6) return p + (q - p) * 6 * t;
            if(t < 1/2) return q;
            if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        }

        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        var p = 2 * l - q;
        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);
    }

    return {r:clamp(0,255,Math.round(r * 255)), g: clamp(0,255,Math.round(g * 255)), b: clamp(0,255,Math.round(b * 255))};
}
function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}
function hexToRgb(hex) {
    var bigint = parseInt( ltrim(hex,'#') , 16);
    return { r : (bigint >> 16) & 255, g : (bigint >> 8) & 255, b : bigint & 255}
}
function rgbToHsl(r, g, b){
    r /= 255, g /= 255, b /= 255;
    var max = Math.max(r, g, b), min = Math.min(r, g, b);
    var h, s, l = (max + min) / 2;

    if(max == min){
        h = s = 0; // achromatic
    }else{
        var d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch(max){
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }

    return {h:h, s:s, l:l};
}
function pick_colors(hexStr){
	var mainRgb = hexToRgb(hexStr);
	var mainHsl = rgbToHsl(mainRgb.r,mainRgb.g,mainRgb.b);
	
	/*add stuff to this to return it*/
	var retVal = {};
	retVal.main = hexStr;

	return retVal;
}

function changeColor(hex){
    
        jQuery('.wpcb_box_button').attr('style','');
    
        var main_color = hexToRgb(hex);
        var main_hsl = rgbToHsl(main_color.r,main_color.g,main_color.b);

        /*gradient*/
        var color2_rgb = hslToRgb(main_hsl.h,main_hsl.s*1.1,main_hsl.l*0.85);
        var color2_hex = rgbToHex(color2_rgb.r,color2_rgb.g,color2_rgb.b);

        var color3_rgb = hslToRgb(main_hsl.h,main_hsl.s*1.2,main_hsl.l*0.55);
        var color3_hex = rgbToHex(color2_rgb.r,color2_rgb.g,color2_rgb.b);

        /*3d*/
        var color4_rgb = hslToRgb(main_hsl.h,main_hsl.s*1.1,main_hsl.l*0.7);
        var color4_hex = rgbToHex(color2_rgb.r,color2_rgb.g,color2_rgb.b);
        
        if(jQuery('.wpcb_box_button').hasClass('wpcb_button_flat')){
            
            var theFinalCSS = 'background:'+hex+';';
            jQuery('.wpcb_box_button').attr('style',theFinalCSS + otherCSS);
            templateFields['button_type_css'] = '.wpcb_button_flat{' + theFinalCSS + '}';
            
        }
        else if(jQuery('.wpcb_box_button').hasClass('wpcb_button_gradient')){
         
            var theFinalCSS = 'background-image : -moz-linear-gradient(top, '+hex+', '+color2_hex+');background-image : -ms-linear-gradient(top, '+hex+', '+color2_hex+');background-image : -webkit-gradient(linear, '+hex+', '+color2_hex+');background-image : -webkit-linear-gradient(top, '+hex+', '+color2_hex+');background-image : -o-linear-gradient(top, '+hex+', '+color2_hex+');background-image : linear-gradient(top, '+hex+', '+color2_hex+');filter : progid:DXImageTransform.Microsoft.gradient(startColorstr="'+hex+'", endColorstr="'+color2_hex+'", GradientType=0);border-color : +'+hex + ' ' + color2_hex + ' ' + color3_hex+';background-color : '+hex+';';
            jQuery('.wpcb_box_button').attr('style',theFinalCSS + otherCSS);
            templateFields['button_type_css'] = '.wpcb_button_gradient{' + theFinalCSS + '}';
        
        }
        else if(jQuery('.wpcb_box_button').hasClass('wpcb_button_3d')){
        
            var theFinalCSS = 'background:'+hex+';border-bottom-color:'+color4_hex+';-webkit-box-shadow:inset 0 -5px '+color4_hex+';box-shadow:inset 0 -5px '+color4_hex+';';
            jQuery('.wpcb_box_button').attr('style',theFinalCSS + otherCSS);
            templateFields['button_type_css'] = '.wpcb_button_3d{' + theFinalCSS + '}';
        
    }

}

function wpcbValidateForm(){
    if(jQuery('#input_campaign_name').length > 0){
        var inputCampaignObject = jQuery('#input_campaign_name').data('ddslick');
        
        var inputCampaignName = inputCampaignObject.selectedData.value;
        var inputCampaignMailer = inputCampaignObject.selectedData.description;
        
        switch(inputCampaignMailer){
            case 'GetResponse' :    var mailerId = 1;
                                    break;
            case 'MailChimp' :  var mailerId = 2;
                                break;
            case 'Aweber' : var mailerId = 3;
                            break;
            case 'MailPoet' :   var mailerId = 9;
                                break;
            case 'Feedburner' :   var mailerId = 11;
                                break;
        }
        
        if(inputCampaignName === undefined){
            alert('Please select a campaign/list first.');
            return false;
        }
        else{
            templateFields['input_campaign_name'] = inputCampaignName;
            templateFields['input_mailer_id'] = mailerId;
            return true;
        }
    }
    else if(jQuery('#input_campaign_name').val() == undefined){
        return true;
    }
    else{
        alert('Please select a campaign/list first.');
        return false;
    }
}

(function ( $ ) {
    "use strict";

    $(function () {

        $(document).ready(function(){
            
            //Adds style tags for Custom CSS
            jQuery('.wpcb_template_main').before('<style></style>');
            
            $('#input_campaign_name').ddslick({
                width: 250,
                selectText: "Select a campaing/list."
            });
            
            templateFields['button_bg_color'] = jQuery('#button_bg_color').val();
            templateFields['button_bg_color'] = (templateFields['button_bg_color'] != null) ? templateFields['button_bg_color'] : '#fff';
            changeColor(templateFields['button_bg_color']);
            getValues();    
            applyChanges();
            
            $(document).on('input',":input", function(){
                getValues();    
                applyChanges();
            });

            $(document).on('change',"#button_target_blank", function(){
                getValues();
                applyChanges();
            });
            
            $('#input_remove_name_field').change(function(){
                getValues();
                applyChanges();
            });
            
            $("input[type='radio']").change(function(){
                switch($(this).attr('class')){
                    case 'heading_align':   templateFields['heading_align'] = jQuery('.heading_align:checked').val();
                                            applyChanges();
                                            break;
                    case 'content_align':   templateFields['content_align'] = jQuery('.content_align:checked').val();
                                            applyChanges();
                                            break;
                    case 'button_align':    templateFields['button_align'] = jQuery('.button_align:checked').val();
                                            applyChanges();
                                            break;
                    case 'button_type':     templateFields['button_type'] = jQuery('.button_type:checked').val();
                                            applyChanges();
                                            changeColor(jQuery('#button_bg_color').val());
                                            break;
                    case 'image_align':     templateFields['image_align'] = jQuery('.image_align:checked').val();
                                            applyChanges();
                                            break;         
                    case 'video_site':      templateFields['video_site'] = jQuery('.video_site:checked').val();
                                            applyChanges();
                                            break;                                            
                    case 'video_align':     templateFields['video_align'] = jQuery('.video_align:checked').val();
                                            applyChanges();
                                            break;          
                }
            });
             
            $('#heading_color').wpColorPicker({
                change: function(event,ui) {
                    templateFields['heading_color'] = ui.color.toString();
                    applyChanges();
                }
            });
            $('#heading_bg_color').wpColorPicker({
                change: function(event,ui) {
                    templateFields['heading_bg_color'] = ui.color.toString();
                    applyChanges();
                }
            });
            $('#content_color').wpColorPicker({
                change: function(event,ui) {
                    templateFields['content_color'] = ui.color.toString();
                    applyChanges();
                }
            });
            $('#input_text_color').wpColorPicker({
                change: function(event,ui) {
                    templateFields['input_text_color'] = ui.color.toString();
                    applyChanges();
                }
            });
            $('#button_text_color').wpColorPicker({
                change: function(event,ui) {
                    templateFields['button_text_color'] = ui.color.toString();
                    applyChanges();
                }
            });
            $('#button_bg_color').wpColorPicker({
                change: function(event,ui) {
                    templateFields['button_bg_color'] = ui.color.toString();
                    changeColor(templateFields['button_bg_color']);
                    //applyChanges();
                }
            });
            $('#box_container_bg_color').wpColorPicker({
                change: function(event,ui) {
                    templateFields['box_container_bg_color'] = ui.color.toString();
                    applyChanges();
                }
            });
            $('#box_container_border_color').wpColorPicker({
                change: function(event,ui) {
                    templateFields['box_container_border_color'] = ui.color.toString();
                    applyChanges();
                }
            });            

            // Restore to default.

            $(document).on('click','#restore-to-default', function(){
                
                if(confirm(wpcbRTBC.resetDataConfirmation)){
                    
                    $(this).text(wpcbRTBC.resttingBtn).attr('disabled','disabled');
                    
                    var data = {
                        action: 'restore_to_default',
                        box_id: parseInt($(this).attr('box_id'))
                    };

                    $.post(ajaxurl, data, function(response) {

                        if(response > 0){
                            location.reload(true);
                        }
                        else
                        {
                            $("<div class='error'><p>"+wpcbRTBC.resetError+"</p></div>").insertAfter(".wpcb_nav_buttons_step_2").fadeOut(5000, function(){$(this).remove();});
                        }

                    });
                }    
            });

            // Saving/Updating the box customization to db
            
            $(document).on('click','#update-box-customizations', function(){
                
                getValues(); // Get all values again before saving
                
                if(wpcbValidateForm()){
                
                    $(this).attr('disabled','disabled').val(wpcbRTBC.updatingBtn);

                    var all_customizations = templateFields;

                    var data = {
                        action: 'update_box_customizations',
                        all_customizations: all_customizations,
                        box_id: parseInt($(this).attr('box_id'))
                    };

                    $.post(ajaxurl, data, function(response) {

                        if(response > 0){
                            $('#update-box-customizations').removeAttr('disabled').val(wpcbRTBC.updateSaved);
                            var redirect_to = window.location.href;
                            redirect_to = redirect_to.replace("&success=1", "");
                            redirect_to = redirect_to.replace("step=2", "step=3&success=1");
                            window.location.href = redirect_to;
                        }
                        else
                        {
                            $('#update-box-customizations').removeAttr('disabled').val(wpcbRTBC.saveAndNext);
                            $("<div class='error'><p>"+wpcbRTBC.updateError+"</p></div>").insertAfter(".wpcb_nav_buttons_step_2").fadeOut(5000, function(){$(this).remove();});
                        }

                    });
                
                }
                
            });
            
            // Box Preview Stick to Top
            
            window.boxwidth = $('.wpcb_stick_this').width();
            var $wpcbStickThis = $('.wpcb_stick_this');
            $(window).scroll(function(){
                if($('.wpcb_box_preview_stick').is(':checked')){
                    var window_top = $(window).scrollTop();
                    var $wpcbStickThisOffset = $('.wpcb_stick_this_offset');
                    if($wpcbStickThisOffset.length){
                        var div_top = $wpcbStickThisOffset.offset().top;
                    }
                    if (window_top > div_top) {
                        $wpcbStickThis.css('width',window.boxwidth);
                        $wpcbStickThis.addClass('wpcb_stick');
                    } else {
                        $wpcbStickThis.removeClass('wpcb_stick');
                    }
                }
                else{
                    $wpcbStickThis.removeClass('wpcb_stick');
                }
            });
        });


    });

}(jQuery));
