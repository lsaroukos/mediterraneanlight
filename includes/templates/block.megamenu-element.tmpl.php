<?php
/**
 * template for rednering megamenu element
 */


$link_html = $attributes['link']; // e.g. '\u003ca href=\u0022http://localhost:7000/\u0022 data-type=\u0022page\u0022 data-id=\u00226\u0022\u003eΑρχική\u003c/a\u003e'

// Step 1: Decode escaped Unicode characters
//$link_html = json_decode('"' . $link_html . '"');

// Step 2: Extract the data-id (page ID)
preg_match('/data-id=["\'](\d+)["\']/', $link_html, $matches);
$link_id = isset($matches[1]) ? intval($matches[1]) : 0;


?>

<div id="<?php echo $block_id; ?>" class="megamenu-element">
    <label class="megamenu-label <?php echo ($link_id==get_queried_object_id()) ? "current-page" : ""; ?>"><?php echo $attributes['link']; ?></label>
    <div class="submenu"><?php echo $content; ?></div>
</div>