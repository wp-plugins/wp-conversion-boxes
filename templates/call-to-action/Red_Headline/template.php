<?php

static $wpcb_template_type = 'call-to-action';

// Defaults

if($wpcb_default_fields == '' or $wpcb_default_fields['defaults'] == 'defaults'){
    $wpcb_default_fields = array(
            'box_container_bg_color' => '#fff',
            'box_container_width' => '100%',
            'box_container_height' => '',
            'box_container_border_width' => '',
            'box_container_border_size' => '',
            'box_container_margin_top' => '',
            'box_container_margin_bottom' => '',
            'box_container_margin_left' => '',
            'box_container_margin_right' => '',


            'heading_text'  => 'Highly Effective Headline!',
            'heading_font_familiy'  => 'Shadows Into Light',
            'heading_font_size' => '24px',
            'heading_line_height'   => '40px',
            'heading_color' => '#fff',
            'heading_bg_color' => '#8f0a0b',
            'heading_align' => 'center',

            'content_text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'content_font_familiy'  => 'Georgia',
            'content_font_size' => '16px',
            'content_line_height' => '24px',
            'content_color' => '#333',
            'content_align' => 'center',

            'button_text' => 'Check Out!',
            'button_type' => 'wpcb_button_gradient',
            'button_text_font_familiy'  => 'Georgia',
            'button_text_font_size' => '16px',
            'button_text_color' => '#fff',
            'button_bg_color' => '#0c8442',
            'button_border_radius' => '5px',
            'button_align' => 'center',
            'button_link' => '',
            'button_target_blank' => true,

            'image_url' => '',
            'image_width' => '',
            'image_height' => '',
            'image_align' => ''
    );
}        

// Important to mention this:

$wpcb_default_fields['use_heading'] = true;
$wpcb_default_fields['use_content'] = true;
$wpcb_default_fields['use_image'] = true;

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
        
    }
    
    .wpcb_box_heading{
        background-color: <?= $wpcb_default_fields['heading_bg_color']; ?>;
    }
    
    .wpcb_box_heading_text{
        font-family:    '<?= $wpcb_default_fields['heading_font_familiy']; ?>', serif;
        font-size:      <?= $wpcb_default_fields['heading_font_size']; ?>;
        line-height:    <?= $wpcb_default_fields['heading_line_height']; ?>;
        color:          <?= $wpcb_default_fields['heading_color']; ?>;
        text-align:     <?= $wpcb_default_fields['heading_align']; ?>;
        text-transform: uppercase;
        font-weight: 900;
        margin: 0;
    }
    
    .wpcb_box_content{
        font-family:    <?= $wpcb_default_fields['content_font_familiy']; ?>;
        font-size:      <?= $wpcb_default_fields['content_font_size']; ?>;
        line-height:    <?= $wpcb_default_fields['content_line_height']; ?>;
        color:          <?= $wpcb_default_fields['content_color']; ?>;
        text-align:     <?= $wpcb_default_fields['content_align']; ?>;
        padding: 1em;
    }
    
    .wpcb_box_button_div{
        text-align: <?= $wpcb_default_fields['button_align']; ?>;
        padding: 0em 1em 1em 1em;
    }
    
    .wpcb_box_button{
        font-family:    <?= $wpcb_default_fields['button_text_font_familiy']; ?>;
        font-size:      <?= $wpcb_default_fields['button_text_font_size']; ?>;
        color:          <?= $wpcb_default_fields['button_text_color']; ?>;
        background-color: <?= $wpcb_default_fields['button_bg_color']; ?>;
        border-radius: <?= $wpcb_default_fields['button_border_radius']; ?>;
        padding: 10px 15px;
    }
    
    <?= $wpcb_default_fields['button_type_css']; ?>
    
</style>
<div class="<?= $wpcb_default_fields['box_make_sticky']; ?>_offset"></div>
<div class="wpcb_template_main <?= $wpcb_default_fields['box_fade_in']." ".$wpcb_default_fields['box_make_sticky']; ?>" data-fadetime="<?= $wpcb_default_fields['box_fade_in_time']; ?>">
    <div class="wpcb_box_heading">
        <div class="wpcb_box_heading_text"><?= $wpcb_default_fields['heading_text']; ?></div>
    </div>
    <div class="wpcb_box_content">
        <?= $wpcb_default_fields['content_text']; ?>
    </div>    
    <div class="wpcb_box_button_div">
        <a href="<?= $wpcb_default_fields['button_link']; ?>" target="<?php echo ($wpcb_default_fields['button_target_blank'] == true) ? '_blank' : ''; ?>" id="wpcb_box_button_<?= $box_id; ?>" class="wpcb_box_button <?= $wpcb_default_fields['button_type']; ?>"><?= $wpcb_default_fields['button_text']; ?></a>
    </div>
    
</div>