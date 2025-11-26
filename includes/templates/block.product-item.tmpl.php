<?php
/**
 * template to render product item featured image container
 */

if ( ! defined( 'ABSPATH' ) ) exit;
global $product;

?>
<?php if ( ! $product ) : ?>
    <p><?php _e("No product found."); ?></p>
<?php else:


    // get thumbnail
    $thumb_id = $product->get_image_id();   // get img id
    $thumb_url = !$thumb_id ? MEDLIGHT_URI."/assets/static/img/no-product-image__medium.png" : \wp_get_attachment_image_url( $thumb_id, 'medium' ); // get img url

    // get second picture if any
    $gallery_img_ids = $product->get_gallery_image_ids();
    
    $alt_img_url = "";
    if( !empty($gallery_img_ids) ){
        foreach( $gallery_img_ids as $img_id ){
            $alt_img_url = \wp_get_attachment_image_url( $img_id, 'medium' );
            if( $alt_img_url!==$thumb_url )
                break;
        }
    }

    ?>

    <div class="product-item">
        <div class="images">

            <img class="featured-product-img" src="<?php echo $thumb_url; ?>" />
            <?php if( $alt_img_url!=="" && $alt_img_url!==$thumb_url ): ?>
                <img class="alt-product-img" src="<?php echo $alt_img_url; ?>" />
            <?php endif; ?>

            <div class="wishlist-button">
                <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
            </div>

            <div class="add-to-cart">
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>

        </div>
        <div class="product-details">
            
        </div>
    </div>


<?php endif; ?>
