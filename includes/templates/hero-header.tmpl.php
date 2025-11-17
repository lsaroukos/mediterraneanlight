<?php 
/**
 * header template
 */
?>

<section id="hero" style="background-image:url('<?php echo get_the_post_thumbnail_url()!='' ? get_the_post_thumbnail_url(get_the_ID(),'full') : get_template_directory_uri()."/img/hero.jpg"; ?>')">
    <div class="bg-overlay"></div>
    <div class="wrapper">
        <h1><?php _e("Building Dreams on the Aegean") ?></h1>
        <p><?php _e("Crafting exceptional vacation houses and luxury villas in Kalymnos, Greece. From architectural vision to turnkey reality."); ?></p>
        <div class="action-buttons">
            <a class="btn btn-action btn-primary" href="<?php \MedLight\Utils\TranslationUtils::get_post_translation( get_theme_mod('contact-page') ) ?>" ><?php _e("Start Your Project") ?></a>
            <a class="btn" href="<?php MedLight\Utils\TranslationUtils::get_post_translation( get_theme_mod('projects-page') ) ?>" ><?php _e("View Our Work") ?></a>
        </div>
    </div>
</section>
