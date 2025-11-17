<?php 
/**
 * template file for rendering timeline item block on frontend dynamically
 * 
 * @param $attributes [
 *      'label' : string,
 *      'title' : string,
 *      'text'  : string
 * ]
 */


?>
<div class="timeline-item frontend" id="<?php echo $block_id; ?>" >
    <div class="timeline-label-container">
        <label><?php echo $attributes["label"]; ?></label>
        <div class="vertical-line"></div>
    </div>
    <div class="timeline-content">
        <h3 class="item-title"> <?php echo $attributes['title']; ?></h3>
        <p class="item-text"><?php echo $attributes['text']; ?></p>
    </div>
</div>


