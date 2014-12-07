<?php

static $wpcb_template_type = 'video-call-to-action';

// Defaults

if($wpcb_default_fields == '' or $wpcb_default_fields['defaults'] == 'defaults'){
    $wpcb_default_fields = array(
            'box_container_bg_color' => '#4B4B4B',
            'box_container_width' => '600px',
            'box_container_height' => '',
            'box_container_border_width' => '',
            'box_container_border_size' => '',
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

            'button_text' => 'Click Here To Download Now',
            'button_type' => 'wpcb_button_flat',
            'button_text_font_familiy'  => 'Arial',
            'button_text_font_size' => '16px',
            'button_text_color' => '#fff',
            'button_bg_color' => '#00d5ed',
            'button_border_radius' => '5px',
            'button_align' => 'left',
            'button_link' => 'http://wpconversionboxes.com',
            'button_width' => '',
            'button_target_blank' => true,

            'video_site' => 'youtube',
            'video_id' => 'enteridhere',
            'video_width' => '300px',
            'video_height' => '250px',
            'video_align' => 'left'
    );
}        

//Important

$wpcb_default_fields['use_heading'] = true;
$wpcb_default_fields['use_content'] = true;
$wpcb_default_fields['use_video'] = true;

switch ($wpcb_default_fields['video_site']) {
    case 'youtube': $video_url = '//www.youtube.com/embed/'.$wpcb_default_fields['video_id'];
                    break;
    case 'vimeo':   $video_url = '//player.vimeo.com/video/'.$wpcb_default_fields['video_id']."?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff";
                    break;
}

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
        width: <?php echo $wpcb_default_fields['video_width']; ?>;
        height: <?php echo $wpcb_default_fields['video_height']; ?>;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_content_container{
        
    }    
    
    .wpcb_template_main_<?php echo $box_id; ?> .wpcb_box_video{
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
        padding: 0 0 20px 0;
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
    
    .wpcb_template_main_<?php echo $box_id; ?>.wpcb_template_main .wpcb_box_button_div .wpcb_box_button{
        font-family:    <?php echo $wpcb_default_fields['button_text_font_familiy']; ?>;
        font-size:      <?php echo $wpcb_default_fields['button_text_font_size']; ?>;
        color:          <?php echo $wpcb_default_fields['button_text_color']; ?>;
        background-color: <?php echo $wpcb_default_fields['button_bg_color']; ?>;
        border-radius: <?php echo $wpcb_default_fields['button_border_radius']; ?>;
        width: <?php echo $wpcb_default_fields['button_width']; ?>;
        padding: 10px 15px;
    }
    
    .wpcb_template_main_<?php echo $box_id; ?> <?php echo $wpcb_default_fields['button_type_css']; ?>
    
    <?php echo (isset($wpcb_default_fields['custom_css'])) ? $wpcb_default_fields['custom_css'] : ""; ?>
    
</style>


<div class="wpcb_template_main wpcb_template_main_<?php echo $box_id; ?> <?php echo $wpcb_settings_data['box_fade_in']." ".$wpcb_settings_data['box_make_sticky']; ?>" data-fadetime="<?php echo $wpcb_settings_data['box_fade_in_time']; ?>" data-boxid="<?php echo $box_id; ?>" data-boxname="<?php echo $box_name; ?>">
    <div class="wpcb_box_all_content_container">
        <div class="wpcb_box_media_container wpcb_box_media_<?php echo $wpcb_default_fields['video_align']; ?>">    
            <iframe class="wpcb_box_video" src="<?php echo $video_url; ?>" ></iframe>
        </div>
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
        <div style="clear: both;"></div>
    </div>
</div>