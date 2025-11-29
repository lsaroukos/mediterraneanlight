<?php 
/**
 * render meta boxes on term add
 */
?>

<div class="form-field">
    <label for="term_image"><?php _e('Image'); ?></label>
    <input type="hidden" id="term_image" name="term_image" value="">
    <div id="term_image_preview" style="margin-top:10px;"></div>
    <button class="button upload_term_image_button"><?php _e('Upload Image'); ?></button>
    <button class="button remove_term_image_button" style="display:none;"><?php _e('Remove Image'); ?></button>
</div>