<?php

static $wpcb_template_type = 'call-to-action';

// Defaults

if($wpcb_default_fields == '' or $wpcb_default_fields['defaults'] == 'defaults'){
    $wpcb_default_fields = array(
            'box_container_bg_color' => '#fff',
            'box_container_width' => '300px',
            'box_container_height' => '',
            'box_container_border_width' => '',
            'box_container_border_color' => '',
            'box_container_margin_top' => '',
            'box_container_margin_bottom' => '',
            'box_container_margin_left' => '',
            'box_container_margin_right' => '',


            'heading_text'  => '$1449.99 - Real Unicorn Horn',
            'heading_font_familiy'  => 'Poiret One',
            'heading_font_size' => '22px',
            'heading_line_height'   => '30px',
            'heading_color' => '#333',
            'heading_bg_color' => '',
            'heading_align' => 'left',

            'content_text' => 'A unicorn horn, also known as an alicorn, is a legendary object whose reality may have been accepted in Western Europe throughout the Middle Ages. Buy now for just $1449.99!',
            'content_font_familiy'  => 'Tahoma',
            'content_font_size' => '16px',
            'content_line_height' => '24px',
            'content_color' => '#333',
            'content_align' => 'left',

            'button_text' => 'Add to cart',
            'button_type' => 'wpcb_button_flat',
            'button_text_font_familiy'  => 'Tahoma',
            'button_text_font_size' => '16px',
            'button_text_color' => '#fff',
            'button_bg_color' => '#ed7b00',
            'button_border_radius' => '5px',
            'button_align' => 'left',
            'button_link' => '',
            'button_target_blank' => true,

    );
}        

$wpcb_default_fields['use_heading'] = true;
$wpcb_default_fields['use_content'] = true;
$wpcb_default_fields['use_image'] = false;


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
        padding: 20px;
        -webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
        -moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
        box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
        
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
        text-transform: uppercase;
        font-weight: 900;
        margin: 0;
    }
    
    .wpcb_box_content_container{
        
    }    
    
    .wpcb_box_content{
        font-family:    <?= $wpcb_default_fields['content_font_familiy']; ?>;
        font-size:      <?= $wpcb_default_fields['content_font_size']; ?>;
        line-height:    <?= $wpcb_default_fields['content_line_height']; ?>;
        color:          <?= $wpcb_default_fields['content_color']; ?>;
        text-align:     <?= $wpcb_default_fields['content_align']; ?>;
        padding: 20px 0px;
    }
    
    .wpcb_box_button_div{
        text-align: <?= $wpcb_default_fields['button_align']; ?>;
        padding: 0 0 20px 0;
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
    <div class="wpcb_box_all_content_container">
        <div class="wpcb_box_content_container">    
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
        <div style="clear: both;"></div>
    </div>
</div>