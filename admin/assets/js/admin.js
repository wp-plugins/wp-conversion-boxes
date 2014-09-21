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
    
}    


(function ( $ ) {
	"use strict";

	$(function () {
            
            //On document ready
            
            $(document).ready(function(){
                
                // Show the selected box type div
                
                wpcbShowHideBoxTypes($('.wpcb_box_type_radio:checked').val());
                
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
                        title: 'Choose Image',
                        button: {
                            text: 'Choose Image'
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
            
            //Box type selector. Show/hide box types.
            
            $(document).on('click','.wpcb_box_type_radio', function(){
                    var wpcb_box_type = $(this).val();
                    wpcbShowHideBoxTypes(wpcb_box_type);
            });
            
            // Create new box.
            
            $(document).on('click','#wpcb_create_box', function(){
                    
                    $(this).attr('disabled','disabled').val('Creating new box... Please wait!');
                    
                    $('#wpcb_error').remove();
                    $("<div class='wpcb_loading'></div>").insertAfter("#wpcb_create_box");
                    
                    var data = {
                        action: 'create_new_box',
                        wpcb_box_name: $("#wpcb_box_name").val()
                    };

                    $.post(ajaxurl, data, function(response) {
                        if(response){                            
                            $("#wpcb_create_box").val("Box Created Successfully. Redirecting...");
                            setTimeout(function(){ window.location = window.location.href+'&step=1&id='+response },2000);
                        }
                        else
                        {
                            $('#wpcb_create_box').removeAttr('disabled').val('Create Box and Proceed');
                            $('#wpcb_error').remove();
                            $('.wpcb_loading').remove();
                            $("<div id='wpcb_error'>There was an error saving to database. Please try again later.</div>").insertAfter("#wpcb_create_box");
                        }

                    });
                    
            });
            
            // Update the box type and template
            
            $(document).on('change','.wpcb_template_dropdown', function(){
                wpcbShowPreviewImageOnLoad($('.wpcb_box_type_radio:checked').val());
            });
            
            $(document).on('click','#update-box-template', function(){
                
                $(this).attr('disabled','disabled').val('Updating... Please wait!');
                
                var dropdown_id = $(".wpcb_box_type_radio:checked").val();
                
                var data = {
                    action: 'update_box_template',
                    box_type: $(".wpcb_box_type_radio:checked").val(),
                    box_template: $("#wpcb_template_dropdown_"+dropdown_id+" option:selected").val(),
                    box_id: parseInt($(this).attr('box_id'))
                };

                $.post(ajaxurl, data, function(response) {
                    if(response > 0){
                        $('#update-box-template').removeAttr('disabled').val('Saved! Redirecting...');
                        var redirect_to = window.location.href;
                        redirect_to = redirect_to.replace("step=1", "step=2&success=1");
                        window.location.href = redirect_to;
                    }
                    else
                    {
                        $('#update-box-template').removeAttr('disabled').val('Update');
                        $("<div class='error'><p>There was an error updating the database. Please try again later.</p></div>").insertAfter(".wpcb_nav_buttons_step_1").fadeOut(5000, function(){$(this).remove();});
                    }

                });
            });
            
            // Box Customization : Updated in realtimeboxcustomizer.js
            
            // Box Settings
            
            $(document).on('click','#update-box-settings', function(){
                
                $(this).attr('disabled','disabled').val('Updating... Please wait!');
                
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

                window.settingsData = {
                    action: 'update_box_settings',
                    box_name: box_name,
                    box_fade_in: box_fade_in,
                    box_fade_in_time: box_fade_in_time,
                    box_make_sticky: box_make_sticky,
                    box_id: parseInt(jQuery('#update-box-settings').attr('box_id'))
                };

                $.post(ajaxurl, settingsData, function(response) {
                    if(response > 0){
                        $('#update-box-settings').removeAttr('disabled').val('Update');
                        $('.wpcb_after_finish').lightbox_me({
                            centered: true
                        });
                        $("<div class='updated'><p>Settings saved successfully.</p></div>").insertAfter(".wpcb_nav_buttons_step_3").fadeOut(5000, function(){$(this).remove();});
                    }
                    else
                    {
                        $('#update-box-settings').removeAttr('disabled').val('Save and Publish!');
                        $("<div class='error'></p>There was an error updating the database. Please try again later.</p></div>").insertAfter(".wpcb_nav_buttons_step_3").fadeOut(5000, function(){$(this).remove();});
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
                if(confirm('Are you sure you want to delete this conversion box?')){
                    var data_del = {
                        action: 'delete_it',
                        wpcb_id: $(this).attr('wpcb_id')
                    };
                    $.post(ajaxurl, data_del, function(response) {
                        if(response > 0){
                            $('.wpcb-list-item-'+response).fadeOut(300, function() { $(this).remove(); });
                        }
                        else{
                            alert('ERROR: Unable to delete the conversion box. Please try again later.');
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
                if(confirm('Are you sure you want to flush all the stats for this conversion box?')){
                    var data_flush = {
                        action: 'flush_stats',
                        wpcb_id: $(this).attr('wpcb_id')
                    };
                    $.post(ajaxurl, data_flush, function(response) {
                        if(response > 0){
                            location.reload();
                        }
                        else{
                            alert('ERROR: Unable to flush the stats. Please try again later.');
                            $('.wpcb-list-item-'+response).css("opacity",1);
                        }
                    
                    });
                }
                else
                    $('.wpcb-list-item-'+$(this).attr('wpcb_id')).css("opacity",1);
                e.preventDefault();
            });
            
            $(document).on('click','.wpcb_load_more_stats', function(){
                if(confirm('Free version of WP Conversion Boxes shows only 7 top performing posts and pages.\n\n Upgrade to Pro to view stats for all links.')){
                    window.open('http://wpconversionboxes.com', '_blank');
                }
            });
            
            // Update Global Settings Page
                
                // General Settings
            
                $(document).on('click','#update-global-settings', function(){

                    $(this).attr('disabled','disabled').val('Updating... Please wait!');

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
                            $('#update-global-settings').removeAttr('disabled').val('Update');
                            $("<div class='updated'><p>Updated successfully.</p></div>").insertAfter("#update-global-settings").fadeOut(5000, function(){$(this).remove();});
                        }
                        else
                        {
                            $('#update-global-settings').removeAttr('disabled').val('Update');
                            $("<div class='error'><p>There was an error updating the database. Please try again later.</p></div>").insertAfter("#update-global-settings").fadeOut(5000, function(){$(this).remove();});
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
            
                $(document).click(function(){
                    if($(this).attr('id') !== 'wpcb-shortcode-select')
                        $(".wpcb-boxes-menu").hide().removeClass('open');
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
                    if(confirm('A/B tests feature is not available in free version.\n\n Please upgrade to Pro to get this feature.')){
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
                            $('#wpcb_after_finish_body').html('<h1 style="margin-top: 150px; text-align: center;"><span class="fa fa-check" style="color: #1fa67a;"></span> Box Published Successfully!</h1>');
                            $('.wpcb_publish_close').each(function(){
                                if($(this).text() != 'Later'){
                                    $(this).attr('onclick',"jQuery(this).parent().trigger('close');location.reload();");
                                }
                                else{
                                    $(this).text('Done');
                                }
                            });
                        }
                        else{
                            $('.wpcb_publish_now').hide();
                            $('#wpcb_after_finish_body').html('<h1 style="margin-top: 140px; text-align: center;"><span class="fa fa-close" style="color: red;"></span> Error Publishing The Box!<br /><br />Reload this page and try again.</h1>');
                            $('.wpcb_publish_close').each(function(){
                                if($(this).text() == 'Later'){
                                    $(this).text('Reload');
                                    $(this).attr('onclick',"jQuery(this).parent().trigger('close');location.reload();")
                                }
                            });
                        }

                    });
                });


        });

}(jQuery));