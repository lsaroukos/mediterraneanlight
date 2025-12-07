<?php 

namespace MedLight;
use MedLight\Utils\TranslationUtils as TRNS;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Theme {
  
    public function __construct() {
        $this->init();
        $this->setup();
    }


    /**
     * initialize classes
     */
    public function init(){

        $this->register_rest_api();
        return [
            new Src\Frontend,
            new Src\Shortcodes,
            new Src\WC,
            new Src\Polylang,
            new Src\Settings,
        ];
      //  $this->register_admin_pages();
    }
    


    /** 
     * registers necessary plugin hooks
    */
    public function setup(){
        add_action('admin_enqueue_scripts', [$this,'admin_scripts_enqueue'] );
        add_action('wp_enqueue_scripts', [$this,'scripts_enqueue'] );
        add_action('init', [$this, 'register_blocks']);
    }


    /**
     * load front end scripts
     */
    public function admin_scripts_enqueue(){
        wp_enqueue_script("admin-assets", MEDLIGHT_URI . '/assets/dist/admin.js', [], MEDLIGHT_VERSION, true);
        wp_enqueue_style("admin-style", MEDLIGHT_URI . '/assets/dist/admin.css', [], MEDLIGHT_VERSION);
        wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Jost:wght@400;700&family=Inter:wght@400;700&display=swap', false );
    }

    /**
     * load front end scripts
    */
    public function scripts_enqueue(){
        wp_enqueue_script("index-assets", MEDLIGHT_URI . '/assets/dist/index.js', [], MEDLIGHT_VERSION, true);
        wp_enqueue_script("static", MEDLIGHT_URI . '/assets/static/js/static.js', [], MEDLIGHT_VERSION, true);
        wp_enqueue_style("theme-style", MEDLIGHT_URI . '/style.css', [], MEDLIGHT_VERSION);
        wp_enqueue_style("index-style", MEDLIGHT_URI . '/assets/dist/index.css', [], MEDLIGHT_VERSION);
        wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Jost:wght@400;700&family=Inter:wght@400;700&display=swap', false );
    }


    /**
     * Registers REST endpoints
     */
    public function register_rest_api(){
        return [
            new \MedLight\Rest\CoreAPI,
            new \MedLight\Rest\TranslationsAPI,
            new \MedLight\Rest\WCAPI,
            new \MedLight\Rest\PostsAPI,
        ];
    }
 
    /**
     * initializes Custom Gutenburg Blocks
     */
    public function register_blocks(){
        return [
            new Blocks\LanguageContent(),
            new Blocks\LanguageToggler(),
            new Blocks\SearchToggler(),
            new Blocks\MegamenuElement(),
            new Blocks\MobileMenu(),
            new Blocks\MobileMenuNav(),
            new Blocks\MobileMenuNavItem(),
            new Blocks\FeaturedProducts(),
            new Blocks\LatestPostsSlider(),
            new Blocks\RelatedPostsSlider(),
            new Blocks\ShopLayoutToggler(),
            new Blocks\ProductItem(),
            new Blocks\Wrapper(),
//            new Blocks\WcAddToCartVariationOptionImg(),
        ];
    }

    /**
     * register's admin pages
     */
    public function register_admin_pages(){
        return [
    //        new Pages\SettingsPage()
    //        new Pages\SettingsPage()
        ];
    }
}
