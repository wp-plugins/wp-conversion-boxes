<?php

static $wpcb_template_type = 'call-to-action';

// Defaults

if($wpcb_default_fields == '' or $wpcb_default_fields['defaults'] == 'defaults'){
    $wpcb_default_fields = array(
            'box_container_bg_color' => '#4B4B4B',
            'box_container_width' => '',
            'box_container_height' => '',
            'box_container_border_width' => '',
            'box_container_border_color' => '',
            'box_container_margin_top' => '',
            'box_container_margin_bottom' => '',
            'box_container_margin_left' => '',
            'box_container_margin_right' => '',


            'heading_text'  => 'Start Measuring What Matters',
            'heading_font_familiy'  => 'Arial',
            'heading_font_size' => '22px',
            'heading_line_height'   => '30px',
            'heading_color' => '#fff',
            'heading_bg_color' => '',
            'heading_align' => 'left',

            'content_text' => 'Stop tracking what is easy and start tracking what matters. Download this guide to improve your business metrics.',
            'content_font_familiy'  => 'Arial',
            'content_font_size' => '16px',
            'content_line_height' => '24px',
            'content_color' => '#fff',
            'content_align' => 'left',

            'button_text' => 'Download Now',
            'button_type' => 'wpcb_button_flat',
            'button_text_font_familiy'  => 'Arial',
            'button_text_font_size' => '16px',
            'button_text_color' => '#fff',
            'button_bg_color' => '#00d5ed',
            'button_border_radius' => '0px',
            'button_align' => 'left',
            'button_link' => '',
            'button_width' => '',
            'button_target_blank' => true,

            'image_url' => plugins_url( 'imgs/ebook-cover.png' , __FILE__ ),
            'image_width' => '150px',
            'image_height' => '200px',
            'image_align' => 'left',
        
            'input_text_size' => '16px',
            'input_text_color' => '#999',
            'input_font_family' => 'Arial',
            'input_name_placeholder' => 'Name here...',
            'input_email_placeholder' => 'Email address here...',
            'input_width' => '150px',
            'input_mailer_id' => '',
            'input_campaign_name' => ''
    );
}        

$wpcb_default_fields['use_heading'] = true;
$wpcb_default_fields['use_content'] = true;
$wpcb_default_fields['use_image'] = true;
$wpcb_default_fields['use_input'] = true;

     
?>


<style>
    
    .wpcb_template_main_<?php echo $box_id; ?>{
        
        background-color: <?php echo $wpcb_default_fields['box_container_bg_color']; ?>;
        width: <?php echo $wpcb_default_fields['box_container_width']; ?>;
        height: <?php echo $wpcb_default_fields['box_container_height']; ?>;
        border-width: <?php echo $wpcb_default_fields['box_container_border_width']; ?>;
        border-color: <?php echo $wpcb_default_fields['box_container_border_color']; ?>;
        margin-top: <?php echo $wpcb_default_fields['box_container_margin_top']; ?>;
        margin-bottom: <?php echo $wpcb_default_fields['box_container_margin_bottom']; ?>;
        margin-left: <?php echo $wpcb_default_fields['box_container_margin_left']; ?>;
        margin-right: <?php echo $wpcb_default_fields['box_container_margin_right']; ?>;
        padding: 20px;
        -webkit-box-shadow: inset 0px 0px 24px 1px rgba(0,0,0,0.75);
        -moz-box-shadow: inset 0px 0px 24px 1px rgba(0,0,0,0.75);
        box-shadow: inset 0px 0px 24px 1px rgba(0,0,0,0.75);
        
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_heading{
        background-color: <?php echo $wpcb_default_fields['heading_bg_color']; ?>;
        padding-top: 0px;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_heading_text{
        font-family:    '<?php echo $wpcb_default_fields['heading_font_familiy']; ?>', serif;
        font-size:      <?php echo $wpcb_default_fields['heading_font_size']; ?>;
        line-height:    <?php echo $wpcb_default_fields['heading_line_height']; ?>;
        color:          <?php echo $wpcb_default_fields['heading_color']; ?>;
        text-align:     <?php echo $wpcb_default_fields['heading_align']; ?>;
        text-transform: uppercase;
        font-weight: 900;
        margin: 0;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_media_container{
        display: inline-block;
        width: <?php echo $wpcb_default_fields['image_width']; ?>;
        height: <?php echo $wpcb_default_fields['image_height']; ?>;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_content_container{
        
    }    
    
    .wpcb_template_main_<?php echo $box_id; ?> input.wpcb_input_fields{
        width: <?php echo $wpcb_default_fields['input_width']; ?>;
        line-height: 36px;
        margin-right: 1px;
        border: 1px #ddd solid;
        vertical-align: middle;
        font-size:  <?php echo $wpcb_default_fields['input_text_size']; ?>;
        color:      <?php echo $wpcb_default_fields['input_text_color']; ?>;
        font-size:  <?php echo $wpcb_default_fields['input_font_family']; ?>;
        border-radius: 0px;
        padding: 0px 8px;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_image{
        width: 100%;
        height: 100%;
        padding: 0px;
        margin: 0px;
        border: none;
        box-shadow: none;
            
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_media_center{
        display: block;
        margin: 0 auto;
        padding: 0px;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_media_right{
        float: right;
        padding: 0px 0px 0px 30px;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_media_left{
        float: left;
        padding: 0px 30px 0px 0px;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_content{
        font-family:    <?php echo $wpcb_default_fields['content_font_familiy']; ?>;
        font-size:      <?php echo $wpcb_default_fields['content_font_size']; ?>;
        line-height:    <?php echo $wpcb_default_fields['content_line_height']; ?>;
        color:          <?php echo $wpcb_default_fields['content_color']; ?>;
        text-align:     <?php echo $wpcb_default_fields['content_align']; ?>;
        padding: 20px 0px;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_button_div{
        text-align: <?php echo $wpcb_default_fields['button_align']; ?>;
        padding: 0 0 20px 0;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_button_div a.wpcb_box_button, .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_button_div button.wpcb_box_button{
        font-family:    <?php echo $wpcb_default_fields['button_text_font_familiy']; ?>;
        font-size:      <?php echo $wpcb_default_fields['button_text_font_size']; ?>;
        color:          <?php echo $wpcb_default_fields['button_text_color']; ?>;
        background-color: <?php echo $wpcb_default_fields['button_bg_color']; ?>;
        border-radius: <?php echo $wpcb_default_fields['button_border_radius']; ?>;
        width: <?php echo $wpcb_default_fields['button_width']; ?>;
        padding: 10px 15px;
    }
    
    <?php echo $wpcb_default_fields['button_type_css']; ?>
    
    <?php echo (isset($wpcb_default_fields['custom_css'])) ? $wpcb_default_fields['custom_css'] : ""; ?>
    
</style>


<div class="wpcb_template_main wpcb_template_main_<?php echo $box_id; ?> <?php echo $wpcb_settings_data['box_fade_in']." ".$wpcb_settings_data['box_make_sticky']; ?>" data-fadetime="<?php echo $wpcb_settings_data['box_fade_in_time']; ?>">
    <div class="wpcb_box_all_content_container">
        <div class="wpcb_box_media_container wpcb_box_media_<?php echo $wpcb_default_fields['image_align']; ?>">    
            <img class="wpcb_box_image" src="<?php echo $wpcb_default_fields['image_url']; ?>" />
        </div>
        <div class="wpcb_box_content_container">    
            <div class="wpcb_box_heading">
                <div class="wpcb_box_heading_text"><?php echo $wpcb_default_fields['heading_text']; ?></div>
            </div>            
            <div class="wpcb_box_content">
                <?php echo $wpcb_default_fields['content_text']; ?>
            </div>    
            <div class="wpcb_box_button_div">
                <?php if(isset($wpcb_default_fields['input_remove_name_field']) && $wpcb_default_fields['input_remove_name_field'] == 1) : ?>
                        <input class="wpcb_input_fields" id="wpcb_name" value="Friend" style="display: none;" placeholder="<?php echo $wpcb_default_fields['input_name_placeholder']; ?>" />
                <?php else : ?>
                        <input class="wpcb_input_fields" id="wpcb_name" value="" placeholder="<?php echo $wpcb_default_fields['input_name_placeholder']; ?>" />
                <?php endif; ?>
                <input class="wpcb_input_fields" id="wpcb_email" value="" placeholder="<?php echo $wpcb_default_fields['input_email_placeholder']; ?>" />
                <button id="wpcb_box_button_<?php echo $box_id; ?>" class="wpcb_box_button <?php echo $wpcb_default_fields['button_type']; ?>"><?php echo $wpcb_default_fields['button_text']; ?></button>
                <div class="wpcb_mailer_data" data-mailer-id="<?php echo $wpcb_default_fields['input_mailer_id']; ?>" data-campaign-name="<?php echo $wpcb_default_fields['input_campaign_name']; ?>"></div>
            </div>
        </div>    
        <div style="clear: both;"></div>
    </div>
</div>