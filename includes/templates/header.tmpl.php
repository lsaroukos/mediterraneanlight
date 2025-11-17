<?php 
/**
 * header template
 */
?>

<section id="page-title-section" style="background-image:url('<?php echo get_the_post_thumbnail_url()!='' && !is_shop() ? get_the_post_thumbnail_url(get_the_ID(),'large') : get_template_directory_uri()."/img/page-header.jpg"; ?>')">
    <div class="bg-overlay"></div>
    <div class="wrapper">
        <?php
            if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
            }
        ?>
    </div>
</section>
