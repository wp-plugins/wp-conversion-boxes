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
    
    // If Slide-in checked
    
    if (jQuery('#box_slide_in').is(':checked')) {
        jQuery('#box_slide_in_from').removeAttr("disabled");
        jQuery('#box_slide_in_speed').removeAttr("disabled");
    } else {
        jQuery('#box_slide_in_from').attr("disabled", true);
        jQuery('#box_slide_in_speed').attr("disabled", true);
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
                
                validateFieldsOnDocumentReady();
                
                 $('#wpcb_stats_list_table').dataTable();
                
                ///////////////////////////////////////////////////////////////
                
                // Daterange picker
        
                $('#wpcb_date_range').daterangepicker(
                    {
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                            'Last 7 Days': [moment().subtract('days', 6), moment()],
                            'Last 30 Days': [moment().subtract('days', 29), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                        },
                        startDate: moment().subtract('days', 29),
                        endDate: moment()
                    },
                    function(start, end) {
                        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    }
                );
                
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
                
            });
            
            // Activate and save the license key
            
            $(document).on('click','#wpcb_activate_license', function(){
                
                $(this).attr('disabled','disabled').val(wpcbAdmin.activatingLicense);
                
                $('.error').remove();
                $("<div class='wpcb_loading'></div>").insertAfter("#wpcb_activate_license");
                
                var wpcb_license_key = $('#wpcb_license_key').val();
                if(wpcb_license_key == ''){
                    alert('Please enter a license key.');
                    $(this).removeAttr('disabled').val(wpcbAdmin.activate);
                    $('.wpcb_loading').remove();
                    return false;
                }
                
                var data = {
                    action: 'activate_and_save_license',
                    wpcb_license_key: wpcb_license_key
                };

                $.post(ajaxurl, data, function(response) {
                    if(response == 1){
                        $('.error').remove();
                        $('.wpcb_loading').remove();
                        $("#wpcb_activate_license").val(wpcbAdmin.activatedSuccessfully);
                        $("<div class='updated'><p>"+wpcbAdmin.activatedSuccessfullyMsg+"</p></div>").insertAfter("#wpcb_activate_license");
                        setTimeout(function(){ location.reload(); },2000);
                    }
                    else if(response == 'response_error'){
                        $('#wpcb_activate_license').removeAttr('disabled').val(wpcbAdmin.activate);
                        $('.error').remove();
                        $('.wpcb_loading').remove();
                        $("<div class='error'><p>"+wpcbAdmin.licenseResponseError+"</p></div>").insertAfter("#wpcb_activate_license");
                        setTimeout(function(){ location.reload(); },2000);
                    }
                    else{
                        $('#wpcb_activate_license').removeAttr('disabled').val(wpcbAdmin.activate);
                        $('.error').remove();
                        $('.wpcb_loading').remove();
                        $("<div class='error'><p>"+wpcbAdmin.invalidLicense+"</p></div>").insertAfter("#wpcb_activate_license");
                        setTimeout(function(){ location.reload(); },2000);
                    }

                });
                    
            });
            
            
            // Deactivate and delete the license key
            
            $(document).on('click','#wpcb_deactivate_license', function(){
                
                $(this).attr('disabled','disabled').val(wpcbAdmin.deactivatingLicense);
                
                $('.error').remove();
                $("<div class='wpcb_loading'></div>").insertAfter("#wpcb_deactivate_license");
                
                var data = {
                    action: 'deactivate_and_delete_license',
                };

                $.post(ajaxurl, data, function(response) {
                    if(response == 1){
                        $('.error').remove();
                        $('.wpcb_loading').remove();
                        $("#wpcb_deactivate_license").val(wpcbAdmin.deactivatedSuccessfully);
                        $("<div class='updated'><p>"+wpcbAdmin.deactivatedSuccessfullyMsg+"</p></div>").insertAfter("#wpcb_deactivate_license");
                        setTimeout(function(){ location.reload(); },1000);
                    }
                    else if(response == 'response_error'){
                        $('#wpcb_deactivate_license').removeAttr('disabled').val(wpcbAdmin.deactivate);
                        $('.error').remove();
                        $('.wpcb_loading').remove();
                        $("<div class='error'><p>"+wpcbAdmin.licenseResponseError+"</p></div>").insertAfter("#wpcb_deactivate_license");
                        setTimeout(function(){ location.reload(); },1000);
                    }
                    else{
                        $('#wpcb_deactivate_license').removeAttr('disabled').val(wpcbAdmin.deactivate);
                        $('.error').remove();
                        $('.wpcb_loading').remove();
                        $("<div class='error'><p>"+wpcbAdmin.errorDeletingLicense+"</p></div>").insertAfter("#wpcb_deactivate_license");
                        setTimeout(function(){ location.reload(); },1000);
                    }

                });
                    
            });
            
            
            //Box type selector. Show/hide box types.
            
            $(document).on('click','.wpcb_box_type_radio', function(){
                    var wpcb_box_type = $(this).val();
                    wpcbShowHideBoxTypes(wpcb_box_type);
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
                        if(response && response != 0){                            
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
            
            // Update the box type and template
            
            $(document).on('change','.wpcb_template_dropdown', function(){
                wpcbShowPreviewImageOnLoad($('.wpcb_box_type_radio:checked').val());
            });
            
            $(document).on('click','#update-box-template', function(){
                
                $(this).attr('disabled','disabled').val(wpcbAdmin.updatingWait);
                
                var dropdown_id = $(".wpcb_box_type_radio:checked").val();
                
                var box_id = parseInt($(this).attr('box_id'));
                
                var data = {
                    action: 'update_box_template',
                    box_type: $(".wpcb_box_type_radio:checked").val(),
                    box_template: $("#wpcb_template_dropdown_"+dropdown_id+" option:selected").val(),
                    is_custom_template: $("#wpcb_template_dropdown_"+dropdown_id+" option:selected").data('iscustom'),
                    box_id: box_id
                };

                $.post(ajaxurl, data, function(response) {
                    var response = response.substr(response.length - 1);
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
                
                $(this).attr('disabled','disabled').val(wpcbAdmin.updatingWait);
                var doPopup = $(this).data('dopopup');
                
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
                
                if(jQuery('#box_slide_in').is(':checked')){
                    var box_slide_in = 1;
                    var box_slide_in_from = jQuery('#box_slide_in_from').find(':selected').val();
                    var box_slide_in_speed = jQuery('#box_slide_in_speed').find(':selected').val();
                }
                else{
                    var box_slide_in = 0;
                    var box_slide_in_from = 0;
                    var box_slide_in_speed = 0;
                }

                window.settingsData = {
                    action: 'update_box_settings',
                    box_name: box_name,
                    box_fade_in: box_fade_in,
                    box_fade_in_time: box_fade_in_time,
                    box_make_sticky: box_make_sticky,
                    box_slide_in: box_slide_in,
                    box_slide_in_from: box_slide_in_from,
                    box_slide_in_speed: box_slide_in_speed,
                    box_processing_head : $('#box_processing_head').val(),
                    box_taking_too_long : $('#box_taking_too_long').val(),
                    box_success_head : $('#box_success_head').val(),
                    box_success_desc : $('#box_success_desc').val(),
                    box_error_head : $('#box_error_head').val(),
                    box_error_desc : $('#box_error_desc').val(),
                    box_id: parseInt(jQuery('#update-box-settings').attr('box_id'))
                };

                $.post(ajaxurl, settingsData, function(response) {
                    if(response > 0){
                        $('#update-box-settings').removeAttr('disabled').val(wpcbAdmin.update);
                        if(doPopup != "no"){
                            $('.wpcb_after_finish').lightbox_me({
                                centered: true
                            });
                        }
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
                    
                    // Fade In/Out
                    
                    $(document).on('click','#box_fade_in', function(){
                        if ($(this).is(':checked')) {
                            $('#box_fade_in_time').removeAttr("disabled");
                        } else {
                            $('#box_fade_in_time').attr("disabled", true);
                        }
                    });
                    
                    // Slide In
                    
                    $(document).on('click','#box_slide_in', function(){
                        if ($(this).is(':checked')) {
                            $('#box_slide_in_from').removeAttr("disabled");
                            $('#box_slide_in_speed').removeAttr("disabled");
                        } else {
                            $('#box_slide_in_from').attr("disabled", true);
                            $('#box_slide_in_speed').attr("disabled", true);
                        }
                    });
            
            // Delete Conversion Box from database
            
            $(document).on('click','.wpcb_delete', function(e){
                
                var $wpcbThisClass = $('.wpcb-list-item-'+$(this).attr('wpcb_id'));
                
                if($wpcbThisClass.next().hasClass('wpcb-list-test-variant')){
                    var wpcbIsTest = true;
                    $wpcbThisClass.next().css("opacity",0.5);
                    $wpcbThisClass.css("opacity",0.5);
                    var wpcbDelMsg = wpcbAdmin.sureDeleteMain;
                    
                }else{
                    var wpcbIsTest = false;
                    $wpcbThisClass.css("opacity",0.5);
                    var wpcbDelMsg = wpcbAdmin.sureDeleteVariant;
                }
                
                if(confirm(wpcbDelMsg)){
                    var data_del = {
                        action: 'delete_it',
                        wpcb_id: $(this).attr('wpcb_id')
                    };
                    $.post(ajaxurl, data_del, function(response) {
                        if(response){
                            if(wpcbIsTest){
                                $wpcbThisClass.fadeOut(300, function() { $(this).remove(); });
                                $wpcbThisClass.next().fadeOut(300, function() { $(this).remove(); });
                            }
                            else{
                                $wpcbThisClass.fadeOut(300, function() { $(this).remove(); });
                            }
                            $wpcbThisClass.fadeOut(300, function() { $(this).remove(); });
                        }
                        else{
                            alert(wpcbAdmin.errorDelete);
                        }
                    
                    });
                }
                else{
                    if(wpcbIsTest){
                        $wpcbThisClass.next().css("opacity",1);
                        $wpcbThisClass.css("opacity",1);
                    }
                    else{
                        $wpcbThisClass.css("opacity",1);
                    }
                }
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
            
            // Update Global Settings Page
                
                // General Settings
            
                $(document).on('click','#update-global-settings', function(){

                    $(this).attr('disabled','disabled').val(wpcbAdmin.updatingWait);
                    
                    if($('#wpcb_ga_tracking').is(':checked')){
                        var wpcb_ga_tracking = 1;
                    }
                    else{
                        var wpcb_ga_tracking = 0;
                    }
                    
                    var wpcb_boxes_list_default = $('#wpcb_boxes_list_default').find(':selected').val();
                    var wpcb_boxes_list_posts = $('#wpcb_boxes_list_posts').find(':selected').val();
                    var wpcb_boxes_list_pages = $('#wpcb_boxes_list_pages').find(':selected').val();
                    var wpcb_all_cats_and_box_ids = "";
                    
                    $('select[id^="wpcb_boxes_list_cat_"]').each(function( key, v ) {
                        var currentCatID = v.id.match(/\d+/);
                        var currentCatBoxID = v.value;
                        wpcb_all_cats_and_box_ids = wpcb_all_cats_and_box_ids+","+currentCatID+"-"+currentCatBoxID;
                    });
                    
                    if($('#enable_credit_link').is(':checked')){
                        var enable_credit_link = 1;
                    }
                    else{
                        var enable_credit_link = 0;
                    }
                    
                    var data = {
                        action: 'update_global_settings',
                        wpcb_ga_tracking: wpcb_ga_tracking,
                        wpcb_boxes_list_default: wpcb_boxes_list_default,
                        wpcb_boxes_list_posts: wpcb_boxes_list_posts,
                        wpcb_boxes_list_pages: wpcb_boxes_list_pages,
                        wpcb_all_cats_and_box_ids: wpcb_all_cats_and_box_ids,
                        enable_credit_link: enable_credit_link
                    };

                    $.post(ajaxurl, data, function(response) {
                        if(response > 0){
                            $('#update-global-settings').removeAttr('disabled').val(wpcbAdmin.update);
                            $("<div class='updated'><p>"+wpcbAdmin.updatedSuccessfully+"</p></div>").insertAfter("#update-global-settings").fadeOut(5000, function(){$(this).remove();});
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
                    
                    event.preventDefault();
                
                });
                
                // Custom HTML Form
                
                $(document).on('click','#wpcb_save_custom_list', function(){
                    
                    var custom_list_form_html = $('#custom_list_form_html').val();
                    var custom_list_name = $('#custom_list_name').val();
                    
                    if(custom_list_form_html !== '' || custom_list_name !== ''){
                    
                        var data = {
                            action: 'process_and_save_custom_html_form',
                            custom_list_form_html: custom_list_form_html,
                            custom_list_name: custom_list_name
                        };
                        $.post(ajaxurl, data, function(response) {
                            response = response.substr(response.length - 1);
                            
                            if(response == 1){
                                location.reload();
                            }
                            else
                            {
                                alert(wpcbAdmin.errorProcessingCode);
                            }
                        });
                        
                    }
                    else{
                        alert(wpcbAdmin.pleaseEnterDetails);
                    }
                });
                
                $(document).on('click','#wpcb_delete_custom_list', function(){
                    var custom_list_id = $(this).data('custom-list-id');
                    if(confirm(wpcbAdmin.deleteCustomList)){
                        var data = {
                            action: 'delete_custom_list',
                            custom_list_id: custom_list_id
                        };
                        $.post(ajaxurl, data, function(response) {
                            alert(response);
                            if(response == 1){
                                location.reload();
                            }
                            else
                            {
                                alert(wpcbAdmin.errorDeletingCustom);
                            }
                        });
                    }
                });
                
                $(document).on('click','.wpcb_publish_now', function(){
                    
                    var boxId = parseInt($(this).data('boxid'));
                    var global_placement = $('#wpcb_gloabal_placement:checked').val();
                    var wpcb_post_ids = $("#wpcb_post_ids").val();
                    var old_selected_ids = $("#wpcb_post_ids").data('old-ids');
                    
                    var wpcb_all_cats_and_box_ids = "";
                    $('input[name="post_category\\[\\]"]').each(function( key, v ) {
                        var currentCatID = v.id.match(/\d+/);
                        if($(this).is(':checked'))
                            var currentCatBoxID = boxId;
                        else
                            var currentCatBoxID = '';
                        wpcb_all_cats_and_box_ids = wpcb_all_cats_and_box_ids+","+currentCatID+"-"+currentCatBoxID;
                    });
                    
                    $('#wpcb_after_finish_body').html("<div class='wpcb_loading' style='margin: 150px 0px 0px 315px;'></div>");
                    
                    var data = {
                        action: 'publish_the_box',
                        global_placement: global_placement,
                        wpcb_all_cats_and_box_ids: wpcb_all_cats_and_box_ids,
                        wpcb_post_ids: wpcb_post_ids,
                        old_selected_ids: old_selected_ids,
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
                        else
                        {
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
                
                
                $(document).on('click','.wpcb_dont_show_renew', function(){
                    var dontShow = $(this).data('dontshow');
                    $(this).parent().parent().hide();
                    var data = {
                        action: 'dont_show_renew_msg',
                        dont_show: dontShow
                    };
                    $.post(ajaxurl, data, function(response) {
                        
                    });
                });
                
        });

}(jQuery));