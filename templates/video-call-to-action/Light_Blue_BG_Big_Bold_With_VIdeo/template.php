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

            'content_text' => 'Watch the video below and click on the learn more button below to know about our products and services in detail..',
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
            'button_target_blank' => true,

            'video_site' => 'vimeo',
            'video_id' => 'enteridhere',
            'video_width' => '480px',
            'video_height' => '300px',
            'video_align' => 'center'
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
        -webkit-box-shadow: inset 0px 0px 200px -58px rgba(0,0,0,0.75);
        -moz-box-shadow: inset 0px 0px 200px -58px rgba(0,0,0,0.75);
        box-shadow: inset 0px 0px 200px -58px rgba(0,0,0,0.75);        
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
        text-shadow: 0px 3px 4px rgba(0, 0, 0, 0.25);
        font-weight: 900;
        margin: 0;
    }
    
    .wpcb_box_media_container{
        display: inline-block;
        width: <?= $wpcb_default_fields['video_width']; ?>;
        height: <?= $wpcb_default_fields['video_height']; ?>;
    }
    
    .wpcb_box_content_container{
        
    }    
    
    .wpcb_box_video{
        border: 10px solid #fff;
        width: 100%;
        height: 100%;
        padding: 0px;
        margin: 0px;
        box-shadow: none;
            
    }
    
    .wpcb_box_media_center{
        display: block;
        margin: 0 auto 20px auto;
        padding: 0 0 20px 0;
    }
    
    .wpcb_box_media_right{
        float: right;
        padding: 0px 0px 0px 30px;
    }
    
    .wpcb_box_media_left{
        float: left;
        padding: 0px 30px 0px 0px;
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

<div class="<?= $wpcb_settings_data['box_make_sticky']; ?>_offset"></div>
<div class="wpcb_template_main <?= $wpcb_settings_data['box_fade_in']." ".$wpcb_settings_data['box_make_sticky']; ?>" data-fadetime="<?= $wpcb_settings_data['box_fade_in_time']; ?>">
    <div class="wpcb_box_all_content_container">
        <div class="wpcb_box_content_container">    
            <div class="wpcb_box_heading">
                <div class="wpcb_box_heading_text"><?= $wpcb_default_fields['heading_text']; ?></div>
            </div>
            <div class="wpcb_box_content">
                <?= $wpcb_default_fields['content_text']; ?>
            </div>    
            <div class="wpcb_box_media_container wpcb_box_media_<?= $wpcb_default_fields['video_align']; ?>">    
                <iframe class="wpcb_box_video" src="<?= $video_url; ?>" ></iframe>
            </div>
            <div class="wpcb_box_button_div">
                <a href="<?= $wpcb_default_fields['button_link']; ?>" target="<?php echo ($wpcb_default_fields['button_target_blank'] != 'false') ? '_blank' : ''; ?>" id="wpcb_box_button_<?= $box_id; ?>" class="wpcb_box_button <?= $wpcb_default_fields['button_type']; ?>"><?= $wpcb_default_fields['button_text']; ?></a>
            </div>
        </div>    
        <div style="clear: both;"></div>
    </div>
</div>