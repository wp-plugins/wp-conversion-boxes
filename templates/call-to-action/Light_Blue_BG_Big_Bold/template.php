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

            'content_text' => 'Click on the learn more button below to know about our products and services in detail.',
            'content_font_familiy'  => 'Arial',
            'content_font_size' => '20px',
            'content_line_height' => '24px',
            'content_color' => '#4b707c',
            'content_align' => 'center',

            'button_text' => 'Learn More',
            'button_type' => 'wpcb_button_gradient',
            'button_text_font_familiy'  => 'Arial',
            'button_text_font_size' => '16px',
            'button_text_color' => '#fff',
            'button_bg_color' => '#51b6f4',
            'button_border_radius' => '30px',
            'button_align' => 'center',
            'button_link' => 'http://wpconversionboxes.com',
            'button_target_blank' => true

    );
}        

$wpcb_default_fields['use_heading'] = true;
$wpcb_default_fields['use_content'] = true;
$wpcb_default_fields['use_image'] = false;

     
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
        -webkit-box-shadow: inset 0px 0px 200px -38px rgba(0,0,0,0.5);
        -moz-box-shadow: inset 0px 0px 200px -38px rgba(0,0,0,0.5);
        box-shadow: inset 0px 0px 200px -38px rgba(0,0,0,0.5);     
        
    }
    
    .wpcb_template_main .wpcb_box_heading{
        background-color: <?php echo $wpcb_default_fields['heading_bg_color']; ?>;
        padding-top: 0px;
    }
    
    .wpcb_template_main .wpcb_box_heading_text{
        font-family:    '<?php echo $wpcb_default_fields['heading_font_familiy']; ?>', serif;
        font-size:      <?php echo $wpcb_default_fields['heading_font_size']; ?>;
        line-height:    <?php echo $wpcb_default_fields['heading_line_height']; ?>;
        color:          <?php echo $wpcb_default_fields['heading_color']; ?>;
        text-align:     <?php echo $wpcb_default_fields['heading_align']; ?>;
        text-shadow: 0px 3px 4px rgba(0, 0, 0, 0.25);
        font-weight: 900;
        margin: 0;
    }
    
    .wpcb_template_main .wpcb_box_content_container{
        
    }    
    
    .wpcb_template_main .wpcb_box_content{
        font-family:    <?php echo $wpcb_default_fields['content_font_familiy']; ?>;
        font-size:      <?php echo $wpcb_default_fields['content_font_size']; ?>;
        line-height:    <?php echo $wpcb_default_fields['content_line_height']; ?>;
        color:          <?php echo $wpcb_default_fields['content_color']; ?>;
        text-align:     <?php echo $wpcb_default_fields['content_align']; ?>;
        padding: 20px 0px;
    }
    
    .wpcb_template_main .wpcb_box_button_div{
        text-align: <?php echo $wpcb_default_fields['button_align']; ?>;
    }
    
    .wpcb_template_main .wpcb_box_button_div a.wpcb_box_button, .wpcb_template_main .wpcb_box_button_div button.wpcb_box_button{
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
            <div class="wpcb_box_content">
                <?php echo $wpcb_default_fields['content_text']; ?>
            </div>    
            <div class="wpcb_box_button_div">
                <a href="<?php echo $wpcb_default_fields['button_link']; ?>" target="<?php echo ($wpcb_default_fields['button_target_blank'] == 'true') ? '_blank' : ''; ?>" id="wpcb_box_button_<?php echo $box_id; ?>" class="wpcb_box_button <?php echo $wpcb_default_fields['button_type']; ?>"><?php echo $wpcb_default_fields['button_text']; ?></a>
            </div>
        </div>    
    </div>
</div>