<?php 
/**
 * render meta boxes on term edit page
 */

$image_id = get_term_meta($term->term_id, 'term_image', true);
$image_url = $image_id ? wp_get_attachment_thumb_url($image_id) : '';
?>
<tr class="form-field">
    <th scope="row"><label for="term_image"><?php _e('Ιmage'); ?></label></th>
    <td>
        <input type="hidden" id="term_image" name="term_image" value="<?php echo esc_attr($image_id); ?>">
        <div id="term_image_preview" style="margin-top:10px;">
            <?php if ($image_url) : ?>
                <img src="<?php echo esc_url($image_url); ?>" style="max-width:80px;">
            <?php endif; ?>
        </div>
        <button class="button upload_term_image_button"><?php _e('Upload Image'); ?>s</button>
        <button class="button remove_term_image_button" style="<?php echo $image_url ? '' : 'display:none;'; ?>"><?php _e('Remove Image'); ?></button>
    </td>
</tr>