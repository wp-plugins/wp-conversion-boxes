<?php

static $wpcb_template_type = 'video-email-optin';

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

            'content_text' => 'Stop tracking what is easy and start tracking what matters. Watch the video now and download your copy of our eBook guide now. Just fill in your details below and click on "Click Here To Download Now".',
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
            'button_border_radius' => '0px',
            'button_align' => 'left',
            'button_link' => 'http://wpconversionboxes.com',
            'button_target_blank' => true,

            'video_site' => 'youtube',
            'video_id' => 'enteridhere',
            'video_width' => '300px',
            'video_height' => '250px',
            'video_align' => 'left',
        
            'input_text_size' => '16px',
            'input_text_color' => '#999',
            'input_font_family' => 'Arial',
            'input_name_placeholder' => 'Name here...',
            'input_email_placeholder' => 'Email address here...',
            'input_width' => '170px',
            'input_mailer_id' => '',
            'input_campaign_name' => ''
    );
}        

//Important

$wpcb_default_fields['use_heading'] = true;
$wpcb_default_fields['use_content'] = true;
$wpcb_default_fields['use_video'] = true;
$wpcb_default_fields['use_input'] = true;

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
        -webkit-box-shadow: inset 0px 0px 24px 1px rgba(0,0,0,0.75);
        -moz-box-shadow: inset 0px 0px 24px 1px rgba(0,0,0,0.75);
        box-shadow: inset 0px 0px 24px 1px rgba(0,0,0,0.75);
        
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
    
    .wpcb_box_media_container{
        display: inline-block;
        width: <?= $wpcb_default_fields['video_width']; ?>;
        height: <?= $wpcb_default_fields['video_height']; ?>;
    }
    
    .wpcb_box_content_container{
        
    }    
    
    .wpcb_input_fields{
        width: <?= $wpcb_default_fields['input_width']; ?>;
        line-height: 36px;
        margin-right: 5px;
        border: 1px #ddd solid;
        vertical-align: middle;
        font-size:  <?= $wpcb_default_fields['input_text_size']; ?>;
        color:      <?= $wpcb_default_fields['input_text_color']; ?>;
        font-size:  <?= $wpcb_default_fields['input_font_family']; ?>;
    }
    
    .wpcb_box_video{
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
        padding: 0 0 20px 0;
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
    
    .wpcb_box_button_div{
        text-align: <?= $wpcb_default_fields['button_align']; ?>;
        padding: 0px 0 20px 0;
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
        <div class="wpcb_box_media_container wpcb_box_media_<?= $wpcb_default_fields['video_align']; ?>">    
            <iframe class="wpcb_box_video" src="<?= $video_url; ?>" ></iframe>
        </div>
        <div class="wpcb_box_content_container">    
            <div class="wpcb_box_heading">
                <div class="wpcb_box_heading_text"><?= $wpcb_default_fields['heading_text']; ?></div>
            </div>            
            <div class="wpcb_box_content">
                <?= $wpcb_default_fields['content_text']; ?>
            </div>    
            <div class="wpcb_box_button_div">
                <div style="clear: both;"></div>
                <input class="wpcb_input_fields" id="wpcb_name" value="" placeholder="<?= $wpcb_default_fields['input_name_placeholder']; ?>" />
                <input class="wpcb_input_fields" id="wpcb_email" value="" placeholder="<?= $wpcb_default_fields['input_email_placeholder']; ?>" />
                <button id="wpcb_box_button_<?= $box_id; ?>" class="wpcb_box_button <?= $wpcb_default_fields['button_type']; ?>"><?= $wpcb_default_fields['button_text']; ?></button>
                <div class="wpcb_mailer_data" data-mailer-id="<?= $wpcb_default_fields['input_mailer_id']; ?>" data-campaign-name="<?= $wpcb_default_fields['input_campaign_name']; ?>"></div>
            </div>
        </div>    
        <div style="clear: both;"></div>
    </div>
</div>