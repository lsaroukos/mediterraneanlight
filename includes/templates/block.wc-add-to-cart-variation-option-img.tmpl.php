<?php 
/**
 * template to render wc-add-to-cart-variation-option-img block
 * 
 */

$pid = get_the_ID(); // current product id

?>

<div id=<?php echo $block_id; ?>>
    <?php print_r(  $block->context['postId']  ); ?>
</div>