// Admin side JS

function wpcbShowHideBoxTypes(wpcb_box_type) {
    switch(wpcb_box_type){
            case '1'  : jQuery('.wpcb_box_div').hide();
                        jQuery('.wpcb_box_type_1').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_box_type_radio:checked').val());
                        break;
            case '2'  : jQuery('.wpcb_box_div').hide();
                        jQuery('.wpcb_box_type_2').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_box_type_radio:checked').val());
                        break;
            case '3'  : jQuery('.wpcb_box_div').hide();
                        jQuery('.wpcb_box_type_3').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_box_type_radio:checked').val());
                        break;      
            case '4'  : jQuery('.wpcb_box_div').hide();
                        jQuery('.wpcb_box_type_4').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_box_type_radio:checked').val());
                        break;
            case '5'  : jQuery('.wpcb_box_div').hide();
                        jQuery('.wpcb_box_type_5').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_box_type_radio:checked').val());
                        break;
            case '6'  : jQuery('.wpcb_box_div').hide();
                        jQuery('.wpcb_box_type_6').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_box_type_radio:checked').val());
                        break;
    }
}

function wpcbShowHidePopupTypes(wpcb_popup_type) {
    switch(wpcb_popup_type){
            case '1'  : jQuery('.wpcb_popup_div').hide();
                        jQuery('.wpcb_popup_type_1').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_popup_type_radio:checked').val());
                        break;
            case '2'  : jQuery('.wpcb_popup_div').hide();
                        jQuery('.wpcb_popup_type_2').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_popup_type_radio:checked').val());
                        break;
            case '3'  : jQuery('.wpcb_popup_div').hide();
                        jQuery('.wpcb_popup_type_3').show();
                        wpcbShowPreviewImageOnLoad(jQuery('.wpcb_popup_type_radio:checked').val());
                        break;
                        
    }
}

function wpcbShowPreviewImageOnLoad(template_id){
    var screenshotUrl = jQuery('#wpcb_template_dropdown_'+template_id).find(':selected').attr("data-screenshot");
    if(jQuery('#wpcb_template_dropdown_'+template_id).find(':selected').val() != '0'){
        jQuery('.wpcb_template_preview_'+template_id).html('<img src="'+screenshotUrl+'" style="max-width: 100%;" />');       
    }
    else{
        jQuery('.wpcb_template_preview_'+template_id).html('');
    }
}

function validateFieldsOnDocumentReady(){
            
    // If fade-in is checked

    if (jQuery('#box_fade_in').is(':checked')) {
        jQuery('#box_fade_in_time').removeAttr("disabled");
    } else {
        jQuery('#box_fade_in_time').attr("disabled", true);
    }
    
}    


(function ( $ ) {
	"use strict";

	$(function () {
            
            //On document ready
            
            $(document).ready(function(){
                
                
        	// Switch toggle
                $('.wpcb_disable_switch').click(function() {
                    
                    var this_switch = $(this);
                    
                    var box_id = this_switch.attr('wpcb_id');
                    var change_status_to = this_switch.attr('change_status_to');
                    
                    this_switch.hide();
                    this_switch.siblings('.wpcb-disable-loading').css('display','inline-block');
                    
                    var data = {
                        action: 'disable_box',
                        box_status: change_status_to,
                        box_id: box_id
                    };

                    $.post(ajaxurl, data, function(response) {
                        var response = response.substr(response.length - 1);
                        if(response == 1){
                            this_switch.show();
                            this_switch.siblings('.wpcb-disable-loading').hide();
                            this_switch.toggleClass('switch_on').toggleClass('switch_off');
                        }
                        else{
                            alert('ERROR: Reload and try again.');
                        }

                    });
                    
                });
                
                
                // Show the selected box type div
                
                wpcbShowHideBoxTypes($('.wpcb_box_type_radio:checked').val());
                
                // Show the selected popup type div
                
                wpcbShowHidePopupTypes($('.wpcb_popup_type_radio:checked').val());
                
                validateFieldsOnDocumentReady();
                
                ///////////////////////////////////////////////////////////////
                
                // Image uploader for uploading images
                
                var custom_uploader;
                
                $('#wpcb_img_upload').click(function(e) {
                    e.preventDefault();
                    
                    //If the uploader object has already been created, reopen the dialog
                    if (custom_uploader) {
                        custom_uploader.open();
                        return;
                    }
                    
                    //Extend the wp.media object
                    custom_uploader = wp.media.frames.file_frame = wp.media({
                        title: wpcbAdmin.choseImage,
                        button: {
                            text: wpcbAdmin.choseImage
                        },
                        multiple: false
                    });

                    //When a file is selected, grab the URL and set it as the text field's value
                    custom_uploader.on('select', function() {
                        var attachment = custom_uploader.state().get('selection').first().toJSON();
                        $('#image_url').val(attachment.url);
                        $('.wpcb_box_image').attr('src', attachment.url);
                    });

                    //Open the uploader dialog
                    custom_uploader.open();

                });
                
                $('#two_step_wpcb_img_upload').click(function(e) {
                    e.preventDefault();
                    
                    //If the uploader object has already been created, reopen the dialog
                    if (custom_uploader) {
                        custom_uploader.open();
                        return;
                    }
                    
                    //Extend the wp.media object
                    custom_uploader = wp.media.frames.file_frame = wp.media({
                        title: wpcbAdmin.choseImage,
                        button: {
                            text: wpcbAdmin.choseImage
                        },
                        multiple: false
                    });

                    //When a file is selected, grab the URL and set it as the text field's value
                    custom_uploader.on('select', function() {
                        var attachment = custom_uploader.state().get('selection').first().toJSON();
                        $('#two_step_image_url').val(attachment.url);
                        var two_step_image_url = $('#two_step_image_url').val();
                        $('#wpcb_two_step_optin_link').html('<img src="'+two_step_image_url+'">');
                        $("#wpcb_shortcode_img_url").text(' image_url="'+two_step_image_url+'"');
                    });

                    //Open the uploader dialog
                    custom_uploader.open();

                });
                
            });
            
            //Box type selector. Show/hide box types.
            
            $(document).on('click','.wpcb_box_type_radio', function(){
                    var wpcb_box_type = $(this).val();
                    wpcbShowHideBoxTypes(wpcb_box_type);
            });
            
            //Popup type selector. Show/hide box types.
            
            $(document).on('click','.wpcb_popup_type_radio', function(){
                    var wpcb_popup_type = $(this).val();
                    wpcbShowHidePopupTypes(wpcb_popup_type);
            });
            
            // Create new box.
            
            $(document).on('click','#wpcb_create_box', function(){
                    
                    $(this).attr('disabled','disabled').val(wpcbAdmin.creatingBox);
                    
                    $('#wpcb_error').remove();
                    $("<div class='wpcb_loading'></div>").insertAfter("#wpcb_create_box");
                    
                    var data = {
                        action: 'create_new_box',
                        wpcb_box_name: $("#wpcb_box_name").val()
                    };

                    $.post(ajaxurl, data, function(response) {
                        if(response){                            
                            $("#wpcb_create_box").val(wpcbAdmin.boxCreated);
                            setTimeout(function(){ window.location = window.location.href+'&step=1&id='+response },2000);
                        }
                        else
                        {
                            $('#wpcb_create_box').removeAttr('disabled').val(wpcbAdmin.createBox);
                            $('#wpcb_error').remove();
                            $('.wpcb_loading').remove();
                            $("<div id='wpcb_error'>"+wpcbAdmin.errorSavingToDB+"</div>").insertAfter("#wpcb_create_box");
                        }

                    });
                    
            });
            
            // Update the box type and template
            
            $(document).on('change','.wpcb_template_dropdown', function(){
                wpcbShowPreviewImageOnLoad($('.wpcb_box_type_radio:checked').val());
            });
            
            $(document).on('click','#update-box-template', function(){
                
                var dropdown_id = $(".wpcb_box_type_radio:checked").val();
                var box_template = $("#wpcb_template_dropdown_"+dropdown_id+" option:selected").val();
                var delete_customizations = '0';
                
                if(box_template == 0){
                    alert('Please select a template first!');
                    return false;
                }
                
                if($(this).attr('wpcb_has_template') != '' && $(this).attr('wpcb_has_template') != box_template){
                    if(!confirm('WARNING!\n\nChanging the template will delete all your previous box customization and text data for this box. We recommend creating a new box if you want to use a new template.\n\nPlease OK to proceed or Cancel to stop this action.')){
                        return false;
                    }
                    else{
                        var delete_customizations = '1';
                    }
                }
                
                $(this).attr('disabled','disabled').val(wpcbAdmin.updatingWait);
                
                var data = {
                    action: 'update_box_template',
                    box_type: $(".wpcb_box_type_radio:checked").val(),
                    box_template: box_template,
                    box_id: parseInt($(this).attr('box_id')),
                    delete_customizations: delete_customizations
                };

                $.post(ajaxurl, data, function(response) {
                    if(response > 0){
                        $('#update-box-template').removeAttr('disabled').val(wpcbAdmin.savedRedirecting);
                        var redirect_to = window.location.href;
                        redirect_to = redirect_to.replace("step=1", "step=2&success=1");
                        window.location.href = redirect_to;
                    }
                    else
                    {
                        $('#update-box-template').removeAttr('disabled').val(wpcbAdmin.saveAndNext);
                        $("<div class='error'><p>"+wpcbAdmin.errorUpdatingDB+"</p></div>").insertAfter(".wpcb_nav_buttons_step_1").fadeOut(5000, function(){$(this).remove();});
                    }

                });
            });
            
            // Box Customization : Updated in realtimeboxcustomizer.js
            
            // Box Settings
            
            $(document).on('click','#update-box-settings', function(){
                
                var box_id = parseInt(jQuery('#update-box-settings').attr('box_id'));
                
                $(this).attr('disabled','disabled').val(wpcbAdmin.updatingWait);
                
                // Box name @since 1.0.3
                
                var box_name = jQuery('#box_name').val();
                
                // Box fade-in setting

                if(jQuery('#box_fade_in').is(':checked')){
                    var box_fade_in = 1;
                    var box_fade_in_time = parseInt(jQuery('#box_fade_in_time').val());
                }
                else{
                    var box_fade_in = 0;
                    var box_fade_in_time = 0;
                }

                // Box stick to top setting

                if(jQuery('#make_sticky').is(':checked')){
                    var box_make_sticky = 1;
                }
                else{
                    var box_make_sticky = 0;
                }
                
                var wpcb_popup_type_radio = $('.wpcb_popup_type_radio:checked').val();
                
                if(typeof wpcb_popup_type_radio !== 'undefined' && wpcb_popup_type_radio !== 0){
                    switch(wpcb_popup_type_radio){
                        case '1' :  var wpcb_popup_option_val = $('#wpcb_popup_duration').val();
                                    break;
                        case '2' :  var wpcb_popup_option_val = 0;
                                    break;
                        case '3' :  var wpcb_popup_option_val = $('#wpcb_popup_scroll_percentage').val();
                                    break;
                    }
                }
                else{
                    var wpcb_popup_type_radio = 0;
                    var wpcb_popup_option_val = 0;
                }

                var wpcb_popup_frequency = $('#wpcb_popup_frequency').val() || '10';

                window.settingsData = {
                    action: 'update_box_settings',
                    box_name: box_name,
                    box_fade_in: box_fade_in,
                    box_fade_in_time: box_fade_in_time,
                    box_make_sticky: box_make_sticky,
                    box_id: box_id,
                    wpcb_popup_type_radio : wpcb_popup_type_radio,
                    wpcb_popup_option_val : wpcb_popup_option_val,
                    wpcb_popup_frequency : wpcb_popup_frequency
                };

                $.post(ajaxurl, settingsData, function(response) {
                    if(response > 0){
                        $('#update-box-settings').removeAttr('disabled').val(wpcbAdmin.update);
                        $('.wpcb_after_finish').lightbox_me({
                            centered: true
                        });
                        $("<div class='updated'><p>"+wpcbAdmin.settingsSaved+"</p></div>").insertAfter(".wpcb_nav_buttons_step_3").fadeOut(5000, function(){$(this).remove();});
                    }
                    else
                    {
                        $('#update-box-settings').removeAttr('disabled').val(wpcbAdmin.saveAndPublish);
                        $("<div class='error'></p>"+wpcbAdmin.errorUpdatingDB+"</p></div>").insertAfter(".wpcb_nav_buttons_step_3").fadeOut(5000, function(){$(this).remove();});
                    }
                });
                
            });
            
                    // Box settings stuff
                    
                    $(document).on('click','#box_fade_in', function(){
                        if ($(this).is(':checked')) {
                            $('#box_fade_in_time').removeAttr("disabled");
                        } else {
                            $('#box_fade_in_time').attr("disabled", true);
                        }
                    });
            
            // Delete Conversion Box from database
            
            $(document).on('click','.wpcb_delete', function(e){
                $('.wpcb-list-item-'+$(this).attr('wpcb_id')).css("opacity",0.5);
                if(confirm(wpcbAdmin.sureDelete)){
                    var data_del = {
                        action: 'delete_it',
                        wpcb_id: $(this).attr('wpcb_id')
                    };
                    $.post(ajaxurl, data_del, function(response) {
                        if(response > 0){
                            $('.wpcb-list-item-'+response).fadeOut(300, function() { $(this).remove(); });
                        }
                        else{
                            alert(wpcbAdmin.errorDelete);
                        }
                    
                    });
                }
                else
                    $('.wpcb-list-item-'+$(this).attr('wpcb_id')).css("opacity",1);
                e.preventDefault();
            });
            
            // Flush stats of a Conversion Box from database
            
            $(document).on('click','.wpcb_flush', function(e){
                $('.wpcb-list-item-'+$(this).attr('wpcb_id')).css("opacity",0.5);
                if(confirm(wpcbAdmin.flushStats)){
                    var data_flush = {
                        action: 'flush_stats',
                        wpcb_id: $(this).attr('wpcb_id')
                    };
                    $.post(ajaxurl, data_flush, function(response) {
                        if(response > 0){
                            location.reload();
                        }
                        else{
                            alert(wpcbAdmin.errorFlush);
                            $('.wpcb-list-item-'+response).css("opacity",1);
                        }
                    
                    });
                }
                else
                    $('.wpcb-list-item-'+$(this).attr('wpcb_id')).css("opacity",1);
                e.preventDefault();
            });
            
            $(document).on('click','.wpcb_load_more_stats', function(){
                if(confirm(wpcbAdmin.moreDataPopup)){
                    window.open('http://wpconversionboxes.com', '_blank');
                }
            });
            
            // Update Global Settings Page
                
                // General Settings
            
                $(document).on('click','#update-global-settings', function(){

                    $(this).attr('disabled','disabled').val(wpcbAdmin.updatingWait);

                    var wpcb_boxes_list_default = $('#wpcb_boxes_list_default').find(':selected').val();
                    var wpcb_boxes_list_posts = $('#wpcb_boxes_list_posts').find(':selected').val();
                    var wpcb_boxes_list_pages = $('#wpcb_boxes_list_pages').find(':selected').val();
                    
                    if($('#enable_credit_link').is(':checked')){
                        var enable_credit_link = 1;
                    }
                    else{
                        var enable_credit_link = 0;
                    }
                    
                    var data = {
                        action: 'update_global_settings',
                        wpcb_boxes_list_default: wpcb_boxes_list_default,
                        wpcb_boxes_list_posts: wpcb_boxes_list_posts,
                        wpcb_boxes_list_pages: wpcb_boxes_list_pages,
                        enable_credit_link: enable_credit_link
                    };

                    $.post(ajaxurl, data, function(response) {
                        if(response > 0){
                            $('#update-global-settings').removeAttr('disabled').val(wpcbAdmin.update);
                            $("<div class='updated'><p>"+wpcbAdmin.updatedSuccesfully+"</p></div>").insertAfter("#update-global-settings").fadeOut(5000, function(){$(this).remove();});
                        }
                        else
                        {
                            $('#update-global-settings').removeAttr('disabled').val(wpcbAdmin.update);
                            $("<div class='error'><p>"+wpcbAdmin.errorUpdatingDB+"</p></div>").insertAfter("#update-global-settings").fadeOut(5000, function(){$(this).remove();});
                        }

                    });
                });  
                
                // Mailer Integration
                
                $(document).on('click','.wpcb_mailer', function(){
                    
                    var mailerId = $(this).data('mailer-id');                    
                    $('.wpcb_mailers_option').fadeIn(300).hide();
                    $('#wpcb_mailer_'+mailerId).show();

                });
                
                $(document).on('click','.wpcb_mailer_cancel', function(){
                    
                    $('.wpcb_mailers_option').hide();                    
                
                });
            
                // Dropdown Menu
                
                $(document).click(function(e){
                    if($(e.target).hasClass('wpcb_disable_switch') || $(e.target).parents('.wpcb_disable_switch').length){   
                        return false;
                    }
                    else{
                        $(".wpcb-boxes-menu").hide().removeClass('open');
                    }   
                });                
                
                $(document).on('click','.wpcb-boxes-menu-toggle', function(e){
                    e.stopPropagation();
                    
                    $('.wpcb-boxes-menu').hide();
                    if($(this).next().hasClass('open'))
                        $(this).next().hide().removeClass('open');
                    else{
                        $('.wpcb-boxes-menu').removeClass('open');
                        $(this).next().show().addClass('open');
                    }
                });
                
                // Oops no A/B test available
                
                $(document).on('click','.wpcb_no_ab_test', function(){
                    if(confirm(wpcbAdmin.abTestConfirm)){
                        window.open('http://wpconversionboxes.com', '_blank');
                    }
                });
                
                // Publish popup in box settings page
                
                $(document).on('click','.wpcb_publish_now', function(){
                    
                    var boxId = parseInt($(this).data('boxid'));
                    var global_placement = $('#wpcb_gloabal_placement:checked').val();
                    
                    $('#wpcb_after_finish_body').html("<div class='wpcb_loading' style='margin: 150px 0px 0px 315px;'></div>");
                    
                    var data = {
                        action: 'publish_the_box',
                        global_placement: global_placement,
                        box_id: boxId
                    };

                    $.post(ajaxurl, data, function(response) {
                        if(response > 0){
                            $('.wpcb_publish_now').hide();
                            $('#wpcb_after_finish_body').html('<h1 style="margin-top: 150px; text-align: center;"><span class="fa fa-check" style="color: #1fa67a;"></span> '+wpcbAdmin.boxPublished+'</h1>');
                            $('.wpcb_publish_close').each(function(){
                                if($(this).text() != wpcbAdmin.later){
                                    $(this).attr('onclick',"jQuery(this).parent().trigger('close');location.reload();");
                                }
                                else{
                                    $(this).text(wpcbAdmin.done);
                                }
                            });
                        }
                        else{
                            $('.wpcb_publish_now').hide();
                            $('#wpcb_after_finish_body').html('<h1 style="margin-top: 140px; text-align: center;"><span class="fa fa-close" style="color: red;"></span> '+wpcbAdmin.errorPublishing+'</h1>');
                            $('.wpcb_publish_close').each(function(){
                                if($(this).text() == wpcbAdmin.later){
                                    $(this).text(wpcbAdmin.reload);
                                    $(this).attr('onclick',"jQuery(this).parent().trigger('close');location.reload();")
                                }
                            });
                        }

                    });
                });

                $(document).on('click','#two-step-toggle', function(){
                    $('#two-step-optin-info').lightbox_me({
                        centered: true
                    });
                });
                
                $(document).on('click','#wpcb-popup-toggle', function(){
                    $('#wpcb-popup-info').lightbox_me({
                        centered: true
                    });
                });
                
                $(document).on('input','#two_step_toptin_link_text', function(){
                    
                    $('.wpcb_shortcode_code').show();
                    $('#wpcb_shortcode_input').hide();
                    
                    if(this.value != ''){
                        $('#wpcb_two_step_optin_link').text(this.value);
                        $("#wpcb_shortcode_text").text(' text="'+this.value+'"');
                    }
                    else{
                        $('#wpcb_two_step_optin_link').text('Click Here');
                        $("#wpcb_shortcode_text").text('');
                    }
                });
                
                $(document).on('change','#two-step-optin-link-style', function(){
                    
                    $('.wpcb_shortcode_code').show();
                    $('#wpcb_shortcode_input').hide();
                    
                    if(this.value == 'image'){
                        $('#two_step_img').show();
                        $('#wpcb_two_step_optin_link').removeClass();
                        $('#wpcb_two_step_optin_link').addClass('wpcb_two_step_optin_link_img');
                        var two_step_image_url = $('#two_step_image_url').val();
                        if(two_step_image_url != ''){
                            $('#wpcb_two_step_optin_link').html('<img src="'+two_step_image_url+'">');
                            $("#wpcb_shortcode_img_url").text(' image_url="'+two_step_image_url+'"');
                        }
                        else{
                            $('#wpcb_two_step_optin_link').html('Please select an image.');
                        }
                        
                        $("#wpcb_shortcode_style").text(' style="image"');
                    }
                    else{
                        $('#wpcb_two_step_optin_link').removeClass();
                        $('#two_step_img').hide();
                        $("#wpcb_shortcode_img_url").text('');
                        var type = this.value.split('_');
                        if(type != ''){
                            $('#wpcb_two_step_optin_link').addClass('wpcb_button_'+type[1]+' wpcb_two_step_optin_link_'+type[0]);
                        }
                        else{
                            $('#wpcb_two_step_optin_link').addClass('wpcb_two_step_optin_link_'+type[0]);
                        }
                        
                        $("#wpcb_shortcode_style").text(' style="'+this.value+'"');
                        var two_step_toptin_link_text = $('#two_step_toptin_link_text').val();
                        if(two_step_toptin_link_text != ''){
                            $('#wpcb_two_step_optin_link').text(two_step_toptin_link_text);
                            $("#wpcb_shortcode_text").text(' text="'+two_step_toptin_link_text+'"');
                        }
                        else{
                            $('#wpcb_two_step_optin_link').text('Click Here');
                            $("#wpcb_shortcode_text").text('');
                        }
                    }
                });
        
                $(document).on('click','.wpcb_shortcode_code', function(){
                    var shortcode = $(this).text();
                    var shortcode_html = $(this).html();
                    $(this).hide();
                    $('#wpcb_shortcode_input').val(shortcode);
                    $('#wpcb_shortcode_input').show();
                });

                $("ul.wpcb_customizer_accordion").accordion({
                    heightStyle: "content",
                    collapsible: true,
                    active: false, 
                });
                
        });

}(jQuery));