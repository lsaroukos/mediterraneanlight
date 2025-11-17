<?php 
/**
 * template file for rendering timeline block on frontend dynamically
 */

$style = implode(";",array_filter([
    $attributes['maxWidth']!=-1 ? "max-width:".$attributes['maxWidth'] : null,
]));

?>
<div class="wrapper-block" style="<?php echo $style; ?>" >
    <div class="wrapper">
        <?php echo $content; ?>
    </div>
</div>


