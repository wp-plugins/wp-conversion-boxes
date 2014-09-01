<?php

static $wpcb_template_type = 'email-optin';

// Defaults

if($wpcb_default_fields == '' or $wpcb_default_fields['defaults'] == 'defaults'){
    $wpcb_default_fields = array(
            'box_container_bg_color' => '#fff',
            'box_container_width' => '',
            'box_container_height' => '',
            'box_container_border_width' => '',
            'box_container_border_color' => '',
            'box_container_margin_top' => '',
            'box_container_margin_bottom' => '',
            'box_container_margin_left' => '',
            'box_container_margin_right' => '',


            'heading_text'  => 'Download The Plugin From WordPress.org',
            'heading_font_familiy'  => 'Raleway',
            'heading_font_size' => '22px',
            'heading_line_height'   => '30px',
            'heading_color' => '#333',
            'heading_bg_color' => '',
            'heading_align' => 'left',

            'content_text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'content_font_familiy'  => 'Raleway',
            'content_font_size' => '16px',
            'content_line_height' => '24px',
            'content_color' => '#333',
            'content_align' => 'left',

            'button_text' => 'Download Now',
            'button_type' => 'wpcb_button_flat',
            'button_text_font_familiy'  => 'Tahoma',
            'button_text_font_size' => '16px',
            'button_text_color' => '#fff',
            'button_bg_color' => '#64c1ff',
            'button_border_radius' => '0px',
            'button_align' => 'left',
            'button_link' => '',
            'button_target_blank' => true,

            'image_url' => plugins_url( 'imgs/macbook-frame.png' , __FILE__ ),
            'image_width' => '300px',
            'image_height' => '200px',
            'image_align' => 'right',
        
            'input_text_size' => '16px',
            'input_text_color' => '#999',
            'input_font_family' => 'Arial',
            'input_name_placeholder' => 'Name here...',
            'input_email_placeholder' => 'Email address here...',
            'input_width' => '35%',
            'input_mailer_id' => '',
            'input_campaign_name' => ''
    );
}        

$wpcb_default_fields['use_heading'] = true;
$wpcb_default_fields['use_content'] = true;
$wpcb_default_fields['use_image'] = true;
$wpcb_default_fields['use_input'] = true;

/* 
 * Code it in the premium version
 * Possible custom field types : text, textarea, color, upload
 * $wpcb_custom_fields = array(
 *      'name_of_field' => array('default_value','custom_field_type')
 * );
 * 
 */     
?>


<style>
    
    .wpcb_template_main{
        
        background-color: <?= $wpcb_default_fields['box_container_bg_color']; ?>;
        width: <?= $wpcb_default_fields['box_container_width']; ?>;
        height: <?= $wpcb_default_fields['box_container_height']; ?>;
        border-width: <?= $wpcb_default_fields['box_container_border_width']; ?>;
        border-color: <?= $wpcb_default_fields['box_container_border_color']; ?>;
        margin-top: <?= $wpcb_default_fields['box_container_margin_top']; ?>;
        margin-bottom: <?= $wpcb_default_fields['box_container_margin_bottom']; ?>;
        margin-left: <?= $wpcb_default_fields['box_container_margin_left']; ?>;
        margin-right: <?= $wpcb_default_fields['box_container_margin_right']; ?>;
        padding: 5px;
        
    }
    
    .wpcb_box_heading{
        background-color: <?= $wpcb_default_fields['heading_bg_color']; ?>;
        padding-top: 0px;
    }
    
    .wpcb_box_heading_text{
        font-family:    '<?= $wpcb_default_fields['heading_font_familiy']; ?>', serif;
        font-size:      <?= $wpcb_default_fields['heading_font_size']; ?>;
        line-height:    <?= $wpcb_default_fields['heading_line_height']; ?>;
        color:          <?= $wpcb_default_fields['heading_color']; ?>;
        text-align:     <?= $wpcb_default_fields['heading_align']; ?>;
        font-weight: 900;
        margin: 0;
    }
    
    .wpcb_box_media_container{
        position: relative;
        display: inline-block;
        width: <?= $wpcb_default_fields['image_width']; ?>;
        height: <?= $wpcb_default_fields['image_height']; ?>;
    }
    
    .wpcb_box_content_container{
        
    }    
    
    .wpcb_box_image{
        width: 100%;
        height: 100%;
        padding: 0px;
        margin: 0px;
        border: none;
        box-shadow: none;
    }
    
    .wpcb_box_media_center{
        display: block;
        margin: 0 auto;
        padding: 0px 0px 20px 0px;
    }
    
    .wpcb_box_media_right{
        float: right;
        padding: 0px 0px 20px 30px;

    }
    
    .wpcb_box_media_left{
        float: left;
        padding: 0px 30px 20px 0px;

    }
    
    .wpcb_box_content{
        font-family:    <?= $wpcb_default_fields['content_font_familiy']; ?>;
        font-size:      <?= $wpcb_default_fields['content_font_size']; ?>;
        line-height:    <?= $wpcb_default_fields['content_line_height']; ?>;
        color:          <?= $wpcb_default_fields['content_color']; ?>;
        text-align:     <?= $wpcb_default_fields['content_align']; ?>;
        padding: 20px 0px;
    }
    
    .wpcb_input_fields{
        width: <?= $wpcb_default_fields['input_width']; ?>;
        line-height: 36px;
        margin: 0px;
        border: 1px #ddd solid;
        vertical-align: middle;
        font-size:  <?= $wpcb_default_fields['input_text_size']; ?>;
        color:      <?= $wpcb_default_fields['input_text_color']; ?>;
        font-size:  <?= $wpcb_default_fields['input_font_family']; ?>;
    }
    
    .wpcb_box_button_div{
        text-align: <?= $wpcb_default_fields['button_align']; ?>;
        padding: 0px;
    }
    
    .wpcb_box_button{
        font-family:    <?= $wpcb_default_fields['button_text_font_familiy']; ?>;
        font-size:      <?= $wpcb_default_fields['button_text_font_size']; ?>;
        color:          <?= $wpcb_default_fields['button_text_color']; ?>;
        background-color: <?= $wpcb_default_fields['button_bg_color']; ?>;
        border-radius: <?= $wpcb_default_fields['button_border_radius']; ?>;
        padding: 10px 15px;
        width: 25%;
    }
    
    .wpcb_overlay {
        left: 0; top: 0;
        margin: auto;
        margin: -15px -50px;
        position: absolute;
        opacity:1;
    }
    .wpcb_box_media_outer{
        
    }
    <?= $wpcb_default_fields['button_type_css']; ?>
    
</style>

<div class="<?= $wpcb_settings_data['box_make_sticky']; ?>_offset"></div>
<div class="wpcb_template_main <?= $wpcb_settings_data['box_fade_in']." ".$wpcb_settings_data['box_make_sticky']; ?>" data-fadetime="<?= $wpcb_settings_data['box_fade_in_time']; ?>">
    <div class="wpcb_box_all_content_container">
        <div class="wpcb_box_media_container wpcb_box_media_<?= $wpcb_default_fields['image_align']; ?>">    
            <img class="wpcb_box_image" src="<?= $wpcb_default_fields['image_url']; ?>" />
        </div>
        <div class="wpcb_box_content_container">    
            <div class="wpcb_box_heading">
                <div class="wpcb_box_heading_text"><?= $wpcb_default_fields['heading_text']; ?></div>
            </div>
            <div class="wpcb_box_content">
                <?= $wpcb_default_fields['content_text']; ?>
            </div>
            <div class="wpcb_box_button_div">
                <input class="wpcb_input_fields" id="wpcb_name" value="" placeholder="<?= $wpcb_default_fields['input_name_placeholder']; ?>" />
                <input class="wpcb_input_fields" id="wpcb_email" value="" placeholder="<?= $wpcb_default_fields['input_email_placeholder']; ?>" />
                <button id="wpcb_box_button_<?= $box_id; ?>" class="wpcb_box_button <?= $wpcb_default_fields['button_type']; ?>"><?= $wpcb_default_fields['button_text']; ?></button>
                <div class="wpcb_mailer_data" data-mailer-id="<?= $wpcb_default_fields['input_mailer_id']; ?>" data-campaign-name="<?= $wpcb_default_fields['input_campaign_name']; ?>"></div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>