<?php
switch ($wpcb_default_fields['heading_align']) {
    case 'left': $heading_align_left = 'checked';
        break;
    case 'center': $heading_align_center = 'checked';
        break;
    case 'right': $heading_align_right = 'checked';
        break;
}

switch ($wpcb_default_fields['content_align']) {
    case 'left': $content_align_left = 'checked';
        break;
    case 'center': $content_align_center = 'checked';
        break;
    case 'right': $content_align_right = 'checked';
        break;
}

switch ($wpcb_default_fields['button_align']) {
    case 'left': $button_align_left = 'checked';
        break;
    case 'center': $button_align_center = 'checked';
        break;
    case 'right': $button_align_right = 'checked';
        break;
}

switch ($wpcb_default_fields['button_type']) {
    case 'wpcb_button_gradient': $button_type_gradient = 'checked';
        break;
    case 'wpcb_button_3d': $button_type_3d = 'checked';
        break;
    case 'wpcb_button_flat': $button_type_flat = 'checked';
        break;
}

switch ($wpcb_default_fields['image_align']) {
    case 'left': $image_align_left = 'checked';
        break;
    case 'center': $image_align_center = 'checked';
        break;
    case 'right': $image_align_right = 'checked';
        break;
}

switch ($wpcb_default_fields['video_site']) {
    case 'youtube': $video_youtube = 'checked';
        break;
    case 'vimeo': $video_vimeo = 'checked';
        break;
}

switch ($wpcb_default_fields['video_align']) {
    case 'left': $video_align_left = 'checked';
        break;
    case 'center': $video_align_center = 'checked';
        break;
    case 'right': $video_align_right = 'checked';
        break;
}

?>

<?php if($wpcb_default_fields['use_heading'] == true){ ?>

    <h2>Heading Settings</h2>

    <table class="form-table">
            <tbody>
                    <tr>
                            <th scope="row">
                                    <label for="">Heading text</label>
                            </th>
                            <td>
                                    <input type="text" name="heading_text" id="heading_text" value="<?= $wpcb_default_fields['heading_text'] ?>" class="wpcb_fullwidth" >
                            </td>
                    </tr>
                    <tr>
                            <th scope="row">
                                    <label for="">Font family</label>
                            </th>
                            <td>
                                    <?php $this->font_families_dropdown_list($wpcb_default_fields['heading_font_familiy'], 'heading'); ?>
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Font size
                                    </label>
                            </th>
                            <td>
                                <label><input type="text" name="heading_font_size" id="heading_font_size" value="<?= $wpcb_default_fields['heading_font_size'] ?>" class="wpcb_width50px"> (in px or em)</label>
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Line Height
                                    </label>
                            </th>
                            <td>
                                    <label><input type="text" name="heading_line_height" id="heading_line_height" value="<?= $wpcb_default_fields['heading_line_height'] ?>" class="wpcb_width50px"> (in px or em)</label>
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Text Color
                                    </label>
                            </th>
                            <td>
                                    <input type="text" name="heading_color" id="heading_color" value="<?= $wpcb_default_fields['heading_color'] ?>"  data-default-color="<?= $wpcb_default_fields['heading_color'] ?>">
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Background Color
                                    </label>
                            </th>
                            <td>
                                    <input type="text" name="heading_bg_color" id="heading_bg_color" value="<?= $wpcb_default_fields['heading_bg_color'] ?>" data-default-color="<?= $wpcb_default_fields['heading_bg_color'] ?>">
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Text Align
                                    </label>
                            </th>
                            <td>
                                    <label><input type='radio' name="heading_align" id="heading_align_l" class="heading_align" value="left" <?= $heading_align_left ?>>Left</label>
                                    <label><input type='radio' name="heading_align" id="heading_align_c" class="heading_align" value="center" <?= $heading_align_center ?>>Center</label>
                                    <label><input type='radio' name="heading_align" id="heading_align_r" class="heading_align" value="right" <?= $heading_align_right ?>>Right</label>
                            </td>
                    </tr>
            </tbody>
    </table>

<?php } ?>    
    
<?php if($wpcb_default_fields['use_content'] == true){ ?>

    <h2>Content Settings</h2>

    <table class="form-table">
            <tbody>
                    <tr>
                            <th scope="row"><label for="">
                                    Content
                                    </label>
                            </th>
                            <td>
                                    <textarea name="content_text" id="content_text"><?= $wpcb_default_fields['content_text'] ?></textarea>
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Font family
                                    </label>
                            </th>
                            <td>
                                    <?php $this->font_families_dropdown_list($wpcb_default_fields['content_font_familiy'], 'content'); ?>
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Font size
                                    </label>
                            </th>
                            <td>
                                    <label><input type="text" name="content_font_size" id="content_font_size" value="<?= $wpcb_default_fields['content_font_size'] ?>" class="wpcb_width50px"> (in px or em)</label>
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Line Height
                                    </label>
                            </th>
                            <td>
                                    <label><input type="text" name="content_line_height" id="content_line_height" value="<?= $wpcb_default_fields['content_line_height'] ?>" class="wpcb_width50px"> (in px or em)</label>
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Text Color
                                    </label>
                            </th>
                            <td>
                                    <input type="text" name="content_color" id="content_color" value="<?= $wpcb_default_fields['content_color'] ?>" data-default-color="<?= $wpcb_default_fields['content_color'] ?>">
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Text Align
                                    </label>
                            </th>
                            <td>
                                    <label><input type='radio' name="content_align" id="content_align_l" class="content_align" value="left" <?= $content_align_left ?>>Left</label>
                                    <label><input type='radio' name="content_align" id="content_align_c" class="content_align" value="center" <?= $content_align_center ?>>Center</label>
                                    <label><input type='radio' name="content_align" id="content_align_r" class="content_align" value="right" <?= $content_align_right ?>>Right</label>
                            </td>
                    </tr>
            </tbody>
    </table>
    
<?php } ?>    

<h2>Button Settings</h2>    

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="">
				Button text
				</label>
			</th>
			<td>
				<input type="text" name="button_text" id="button_text" value="<?= $wpcb_default_fields['button_text'] ?>">
			</td>
		</tr>
                <?php if($wpcb_default_fields['use_optin'] != true) { ?>
                    <tr>
                            <th scope="row"><label for="">
                                    Button link (http://)
                                    </label>
                            </th>
                            <td>
                                    <input type="text" name="button_link" id="button_link" class="wpcb_fullwidth" placeholder="http://" value="<?= $wpcb_default_fields['button_link'] ?>">
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Open link in new tab
                                    </label>
                            </th>
                            <td>
                                    <label for="button_target_blank"><input type="checkbox" name="button_target_blank" id="button_target_blank" value="1" <?php echo ($wpcb_default_fields['button_target_blank'] != 'false') ? 'checked' : ''; ?>/>Open in new tab</label>
                            </td>
                    </tr>
                <?php } ?>
		<tr>
			<th scope="row"><label for="">
				Button font family
				</label>
			</th>
			<td>
				<?php $this->font_families_dropdown_list($wpcb_default_fields['button_text_font_familiy'], 'button'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Button font size
				</label>
			</th>
			<td>
                                <label><input type="text" name="button_text_font_size" id="button_text_font_size" value="<?= $wpcb_default_fields['button_text_font_size'] ?>" class="wpcb_width50px"> (in px or em)</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Button Text Color
				</label>
			</th>
			<td>
				<input type="text" name="button_text_color" id="button_text_color" value="<?= $wpcb_default_fields['button_text_color'] ?>" data-default-color="<?= $wpcb_default_fields['button_text_color'] ?>">
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Button Background Color
				</label>
			</th>
			<td>
				<input type="text" name="button_bg_color" id="button_bg_color" value="<?= $wpcb_default_fields['button_bg_color'] ?>"  data-default-color="<?= $wpcb_default_fields['button_bg_color'] ?>">
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Button Type
				</label>
			</th>
			<td>
				<label><input type='radio' name="button_type" id="button_type_1" class="button_type" value="wpcb_button_gradient" <?= $button_type_gradient ?>>Gradient</label>
				<label><input type='radio' name="button_type" id="button_type_2" class="button_type" value="wpcb_button_3d" <?= $button_type_3d ?>>3D</label>
				<label><input type='radio' name="button_type" id="button_type_3" class="button_type" value="wpcb_button_flat" <?= $button_type_flat ?>>Flat</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Button Border Radius
				</label>
			</th>
			<td>
				<input type="text" name="button_border_radius" id="button_border_radius" value="<?= $wpcb_default_fields['button_border_radius'] ?>" class="wpcb_width50px">
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Button Align
				</label>
			</th>
			<td>
				<label><input type='radio' name="button_align" id="button_align_l" class="button_align" value="left" <?= $button_align_left ?>>Left</label>
				<label><input type='radio' name="button_align" id="button_align_c" class="button_align" value="center" <?= $button_align_center ?>>Center</label>
				<label><input type='radio' name="button_align" id="button_align_r" class="button_align" value="right" <?= $button_align_right ?>>Right</label>
			</td>
		</tr>
	</tbody>
</table>

<?php if($wpcb_default_fields['use_image'] == true){ ?>

    <h2>Image Settings</h2>    

    <table class="form-table">
            <tbody>
                    <tr>
                            <th scope="row">
                                <label for="">
                                    Image URL
                                </label>
                            </th>
                            <td>
                                <input id="image_url" type="text" name="image_url" placeholder="http://" value="<?= $wpcb_default_fields['image_url'];?>" /> 
                                <input id="wpcb_img_upload" align="right" class="button" type="button" value="Upload Image" />
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Image Width
                                    </label>
                            </th>
                            <td>
                                    <label><input type="text" name="image_width" id="image_width" value="<?= $wpcb_default_fields['image_width'] ?>" class="wpcb_width50px"> (in px or %)</label>
                            </td>
                    </tr>                    
                    <tr>
                            <th scope="row"><label for="">
                                    Image Height
                                    </label>
                            </th>
                            <td>
                                    <label><input type="text" name="image_height" id="image_height" value="<?= $wpcb_default_fields['image_height'] ?>" class="wpcb_width50px"> (in px or %)</label>
                            </td>
                    </tr>                    
                    <tr>
                            <th scope="row"><label for="">
                                    Image Position
                                    </label>
                            </th>
                            <td>
                                    <label><input type='radio' name="image_align" id="image_align_l" class="image_align" value="left" <?= $image_align_left ?>>Left</label>
                                    <label><input type='radio' name="image_align" id="image_align_c" class="image_align" value="center" <?= $image_align_center ?>>Center</label>
                                    <label><input type='radio' name="image_align" id="image_align_r" class="image_align" value="right" <?= $image_align_right ?>>Right</label>
                            </td>
                    </tr>                    
            </tbody>
    </table>
        

<?php } ?>
   
<?php if($wpcb_default_fields['use_video'] == true){ ?>

    <h2>Video Settings</h2>    

    <table class="form-table">
            <tbody>
                    <tr>
                            <th scope="row">
                                <label for="">
                                    Video Hosted At
                                </label>
                            </th>
                            <td>
                                <label><input type='radio' name="video_site" id="video_site_youtube" class="video_site" value="youtube" <?= $video_youtube ?>>Youtube</label>
                                <label><input type='radio' name="video_site" id="video_site_vimeo" class="video_site" value="vimeo" <?= $video_vimeo ?>>Vimeo</label>
                            </td>
                    </tr>                
                    <tr>
                            <th scope="row">
                                <label for="">
                                    Video URL
                                </label>
                            </th>
                            <td>
                                <label for="video_id" id="video_id_label"></label><input id="video_id" type="text" name="video_id" placeholder="Video id..." value="<?= $wpcb_default_fields['video_id'] ?>" />
                            </td>
                    </tr>
                    <tr>
                            <th scope="row"><label for="">
                                    Video Width
                                    </label>
                            </th>
                            <td>
                                    <label><input type="text" name="video_width" id="video_width" value="<?= $wpcb_default_fields['video_width'] ?>" class="wpcb_width50px"> (in px or %)</label>
                            </td>
                    </tr>                    
                    <tr>
                            <th scope="row"><label for="">
                                    Video Height
                                    </label>
                            </th>
                            <td>
                                    <label><input type="text" name="video_height" id="video_height" value="<?= $wpcb_default_fields['video_height'] ?>" class="wpcb_width50px"> (in px or %)</label>
                            </td>
                    </tr>                    
                    <tr>
                            <th scope="row"><label for="">
                                    Video Position
                                    </label>
                            </th>
                            <td>
                                    <label><input type='radio' name="video_align" id="video_align_l" class="video_align" value="left" <?= $video_align_left ?>>Left</label>
                                    <label><input type='radio' name="video_align" id="video_align_c" class="video_align" value="center" <?= $video_align_center ?>>Center</label>
                                    <label><input type='radio' name="video_align" id="video_align_r" class="video_align" value="right" <?= $video_align_right ?>>Right</label>
                            </td>
                    </tr>                    
            </tbody>
    </table>
        

<?php } ?>
    
    
<?php if($wpcb_default_fields['use_optin'] == true){ ?>
    
    <h2>Optin Form Settings</h2>    

    <table class="form-table">
            <tbody>
                    <tr>
                            <th scope="row">
                                <label for="">
                                    Select Your Email Service Provider
                                </label>
                            </th>
                            <td>
                                <?= $this->get_email_service_providers_list($wpcb_default_fields['email_service_provider']); ?>
                                <div >
                            </td>
                    </tr>                                
            </tbody>
    </table>    
    
<?php } ?>    

<h2>Box Container Settings</h2>    

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="">
				Box Size
				</label>
			</th>
			<td>
				<label for="box_container_width"><input type="text" name="box_container_width" id="box_container_width" value="<?= $wpcb_default_fields['box_container_width'] ?>" class="wpcb_width50px">Width (in px or %)</label>
				<label for="box_container_height"><input type="text" name="box_container_height" id="box_container_height" value="<?= $wpcb_default_fields['box_container_height'] ?>" class="wpcb_width50px">Height (only in px)</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Box Background Color
				</label>
			</th>
			<td>
				<input type="text" name="box_container_bg_color" id="box_container_bg_color" value="<?= $wpcb_default_fields['box_container_bg_color'] ?>" data-default-color="<?= $wpcb_default_fields['box_container_bg_color'] ?>">
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Spacing around the box container
				</label>
			</th>
			<td>
				<label for="box_container_top"><input type="text" name="box_container_top" id="box_container_top" value="" class="wpcb_width50px"> Top (in px)</label>
				<label for="box_container_bottom"><input type="text" name="box_container_bottom" id="box_container_bottom" value="" class="wpcb_width50px"> Bottom (in px)</label><br />
				<label for="box_container_left"><input type="text" name="box_container_left" id="box_container_left" value="" class="wpcb_width50px"> Left (in px)</label>
				<label for="box_container_right"><input type="text" name="box_container_right" id="box_container_right" value="" class="wpcb_width50px"> Right (in px)</label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="">
				Box container border
				</label>
			</th>
			<td>
                            <input type="text" name="box_container_border_color" id="box_container_border_color" value="" class="wpcb_width50px"><label for="box_container_border_color">Border Color</label><br />
				<label for="box_container_border_width"><input type="text" name="box_container_border_width" id="box_container_border_width" value="" class="wpcb_width50px"> Border Size (in px)</label>
			</td>
		</tr>
	</tbody>
</table>