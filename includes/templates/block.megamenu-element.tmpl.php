<?php
/**
 * template for rednering megamenu element
 */

?>

<div id="<?php echo $block_id; ?>" class="megamenu-element">
    <label class="megamenu-label"><?php echo $attributes['link']; ?></label>
    <div class="submenu"><?php echo $content; ?></div>
</div>