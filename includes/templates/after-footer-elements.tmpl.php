<?php 
/**
 * elemets to be rendered before closing body tag
 */
use MedLight\Utils\TranslationUtils as TRNS;
?>
<div id="medlight-core"></div>
<input hidden name="medlight_post_id" value="<?php echo get_the_ID(); ?>" />
<input hidden name="_lang" value="<?php echo TRNS::get_current_language(); ?>" />