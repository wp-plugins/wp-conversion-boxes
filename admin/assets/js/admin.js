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
                
                // Stats Page Related
                
                $( "#datefrom" ).datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 3,
                    onClose: function( selectedDate ) {
                      $( "#dateto" ).datepicker( "option", "minDate", selectedDate );
                    }
                });
                $( "#dateto" ).datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 3,
                    onClose: function( selectedDate ) {
                      $( "#datefrom" ).datepicker( "option", "maxDate", selectedDate );
                    }
                });
                
                ///////////////////////////////////////////////////////////////
                
                // Load more sidebar

                // The section added to the bottom of the "wpcb_more_less" div
                $(".wpcb_more_less").append('<span class="wpcb_continued">…</span>');

                $(".wpcb_adjust").toggle(function() {
                        $(this).parents("div:first").find(".wpcb_more_block").css('height', 'auto').css('overflow', 'visible');
                        $(this).parents("div:first").find("span.wpcb_continued").css('display', 'none');
                        $(this).text('Less ^');
                        }, function() {
                            $(this).parents("div:first").find(".wpcb_more_block").css('height', '100px').css('overflow', 'hidden');
                            $(this).parents("div:first").find("span.wpcb_continued").css('display', 'block');
                            $(this).text('More...');
                        });
                
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
                            $('#update-box-template').removeAttr('disabled').val('Create Box and Proceed');
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
                        $('#update-box-template').removeAttr('disabled').val('Update');
                        $("<div class='updated'><p>Updated successfully. You may now customize the box from Customize Box tab.</p></div>").insertAfter(".wpcb_nav_buttons_step_1").fadeOut(7000, function(){$(this).remove();});
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
                    box_fade_in: box_fade_in,
                    box_fade_in_time: box_fade_in_time,
                    box_make_sticky: box_make_sticky,
                    box_id: parseInt(jQuery('#update-box-settings').attr('box_id'))
                };

                $.post(ajaxurl, settingsData, function(response) {
                    if(response > 0){
                        $('#update-box-settings').removeAttr('disabled').val('Update');
                        $("<div class='updated'><p>Updated successfully.</p></div>").insertAfter(".wpcb_nav_buttons_step_3").fadeOut(5000, function(){$(this).remove();});
                    }
                    else
                    {
                        $('#update-box-settings').removeAttr('disabled').val('Update');
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
                if(confirm('Free version of WP Conversion Boxes shows only 10 top performing posts and pages.\n\n Do you want to upgrade to Pro to get this feature?')){
                    window.open('http://wpconversionboxes.com', '_blank');
                }
            });
            
            // Update Global Settings Page
            
            $(document).on('click','#update-global-settings', function(){

                var wpcb_boxes_list_default = $('#wpcb_boxes_list_default').find(':selected').val();
                var wpcb_boxes_list_posts = $('#wpcb_boxes_list_posts').find(':selected').val();
                var wpcb_boxes_list_pages = $('#wpcb_boxes_list_pages').find(':selected').val();
                
                var data = {
                    action: 'update_global_settings',
                    wpcb_boxes_list_default: wpcb_boxes_list_default,
                    wpcb_boxes_list_posts: wpcb_boxes_list_posts,
                    wpcb_boxes_list_pages: wpcb_boxes_list_pages
                };

                $.post(ajaxurl, data, function(response) {
                    if(response > 0){
                        $("<div class='updated'><p>Updated successfully.</p></div>").insertAfter("#update-global-settings").fadeOut(5000, function(){$(this).remove();});
                    }
                    else
                    {
                        $("<div class='error'><p>There was an error updating the database. Please try again later.</p></div>").insertAfter("#update-global-settings").fadeOut(5000, function(){$(this).remove();});
                    }

                });
            });  

        });

}(jQuery));