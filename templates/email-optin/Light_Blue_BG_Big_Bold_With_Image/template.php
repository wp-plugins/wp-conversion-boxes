<?php

static $wpcb_template_type = 'call-to-action';

// Defaults

if($wpcb_default_fields == '' or $wpcb_default_fields['defaults'] == 'defaults'){
    $wpcb_default_fields = array(
            'box_container_bg_color' => '#D7E7ED',
            'box_container_width' => '',
            'box_container_height' => '',
            'box_container_border_width' => '',
            'box_container_border_size' => '',
            'box_container_margin_top' => '',
            'box_container_margin_bottom' => '',
            'box_container_margin_left' => '',
            'box_container_margin_right' => '',


            'heading_text'  => 'Get Started With Our Product Today.',
            'heading_font_familiy'  => 'Arial',
            'heading_font_size' => '32px',
            'heading_line_height'   => '38px',
            'heading_color' => '#456773',
            'heading_bg_color' => '',
            'heading_align' => 'center',

            'content_text' => 'Enter your details below and click on Download Now button to download your copy of my premium theme for your WordPress website now!',
            'content_font_familiy'  => 'Arial',
            'content_font_size' => '20px',
            'content_line_height' => '24px',
            'content_color' => '#4b707c',
            'content_align' => 'center',

            'button_text' => 'Download Now',
            'button_type' => 'wpcb_button_gradient',
            'button_text_font_familiy'  => 'Arial',
            'button_text_font_size' => '16px',
            'button_text_color' => '#fff',
            'button_bg_color' => '#51b6f4',
            'button_border_radius' => '30px',
            'button_align' => 'center',
            'button_link' => 'http://wpconversionboxes.com',
            'button_target_blank' => true,

            'image_url' => plugins_url( 'imgs/responsive.png' , __FILE__ ),
            'image_width' => '375px',
            'image_height' => '300px',
            'image_align' => 'left',
        
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
        -webkit-box-shadow: inset 0px 0px 200px -58px rgba(0,0,0,0.75);
        -moz-box-shadow: inset 0px 0px 200px -58px rgba(0,0,0,0.75);
        box-shadow: inset 0px 0px 200px -58px rgba(0,0,0,0.75);     
        
    }
    
    .wpcb_box_heading{
        background-color: <?php echo $wpcb_default_fields['heading_bg_color']; ?>;
        padding-top: 0px;
    }
    
    .wpcb_box_heading_text{
        font-family:    '<?php echo $wpcb_default_fields['heading_font_familiy']; ?>', serif;
        font-size:      <?php echo $wpcb_default_fields['heading_font_size']; ?>;
        line-height:    <?php echo $wpcb_default_fields['heading_line_height']; ?>;
        color:          <?php echo $wpcb_default_fields['heading_color']; ?>;
        text-align:     <?php echo $wpcb_default_fields['heading_align']; ?>;
        text-shadow: 0px 3px 4px rgba(0, 0, 0, 0.25);
        font-weight: 900;
        margin: 0;
    }
    
    .wpcb_box_media_container{
        display: inline-block;
        width: <?php echo $wpcb_default_fields['image_width']; ?>;
        height: <?php echo $wpcb_default_fields['image_height']; ?>;
    }
    
    .wpcb_box_content_container{
        
    }    
    
    .wpcb_template_main input.wpcb_input_fields{
        width: <?php echo $wpcb_default_fields['input_width']; ?>;
        line-height: 36px;
        margin-bottom: 5px;
        border: 1px #ddd solid;
        vertical-align: middle;
        font-size:  <?php echo $wpcb_default_fields['input_text_size']; ?>;
        color:      <?php echo $wpcb_default_fields['input_text_color']; ?>;
        font-size:  <?php echo $wpcb_default_fields['input_font_family']; ?>;
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
        padding: 20px 0 0px 0;
    }
    
    .wpcb_box_media_right{
        float: right;
        padding: 20px 0px 0px 30px;
    }
    
    .wpcb_box_media_left{
        float: left;
        padding: 20px 30px 0px 0px;
    }
    
    .wpcb_box_content{
        font-family:    <?php echo $wpcb_default_fields['content_font_familiy']; ?>;
        font-size:      <?php echo $wpcb_default_fields['content_font_size']; ?>;
        line-height:    <?php echo $wpcb_default_fields['content_line_height']; ?>;
        color:          <?php echo $wpcb_default_fields['content_color']; ?>;
        text-align:     <?php echo $wpcb_default_fields['content_align']; ?>;
        padding: 20px 0px;
    }
    
    .wpcb_box_button_div{
        text-align: <?php echo $wpcb_default_fields['button_align']; ?>;
    }
    
    .wpcb_template_main .wpcb_box_button_div .wpcb_box_button{
        font-family:    <?php echo $wpcb_default_fields['button_text_font_familiy']; ?>;
        font-size:      <?php echo $wpcb_default_fields['button_text_font_size']; ?>;
        color:          <?php echo $wpcb_default_fields['button_text_color']; ?>;
        background-color: <?php echo $wpcb_default_fields['button_bg_color']; ?>;
        border-radius: <?php echo $wpcb_default_fields['button_border_radius']; ?>;
        padding: 10px 15px;
    }
    
    <?php echo $wpcb_default_fields['button_type_css']; ?>
    
</style>

<div class="<?php echo $wpcb_settings_data['box_make_sticky']; ?>_offset"></div>
<div class="wpcb_template_main <?php echo $wpcb_settings_data['box_fade_in']." ".$wpcb_settings_data['box_make_sticky']; ?>" data-fadetime="<?php echo $wpcb_settings_data['box_fade_in_time']; ?>">
    <div class="wpcb_box_all_content_container">
        <div class="wpcb_box_content_container">    
            <div class="wpcb_box_heading">
                <div class="wpcb_box_heading_text"><?php echo $wpcb_default_fields['heading_text']; ?></div>
            </div>
            <div class="wpcb_box_media_container wpcb_box_media_<?php echo $wpcb_default_fields['image_align']; ?>">    
                <img class="wpcb_box_image" src="<?php echo $wpcb_default_fields['image_url']; ?>" />
            </div>            
            <div class="wpcb_box_content">     
                <?php echo $wpcb_default_fields['content_text']; ?>
            </div>                   
            <div class="wpcb_box_button_div">
                <input class="wpcb_input_fields" id="wpcb_name" value="" placeholder="<?php echo $wpcb_default_fields['input_name_placeholder']; ?>" />
                <input class="wpcb_input_fields" id="wpcb_email" value="" placeholder="<?php echo $wpcb_default_fields['input_email_placeholder']; ?>" />
                <button id="wpcb_box_button_<?php echo $box_id; ?>" class="wpcb_box_button <?php echo $wpcb_default_fields['button_type']; ?>"><?php echo $wpcb_default_fields['button_text']; ?></button>
                <div class="wpcb_mailer_data" data-mailer-id="<?php echo $wpcb_default_fields['input_mailer_id']; ?>" data-campaign-name="<?php echo $wpcb_default_fields['input_campaign_name']; ?>"></div>
            </div>
        </div>    
        <div style="clear: both;"></div>
    </div>
</div>