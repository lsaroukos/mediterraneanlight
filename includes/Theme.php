<?php 

namespace MedLight;

use MedLight\Src\Template;

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
      /*  $this->register_admin_pages();
        return [
            Src\Settings::class => new Src\Settings,
        ];
        */
    }
    

    /** 
     * registers necessary plugin hooks
    */
    public function setup(){
//        add_action('admin_enqueue_scripts', [$this,'admin_scripts_enqueue'] );
//        add_action('wp_enqueue_scripts', [$this,'scripts_enqueue'] );
        add_action('init', [$this, 'register_blocks']);

    }


    /**
     * load front end scripts
     */
    public function admin_scripts_enqueue(){
        wp_enqueue_script("admin-assets", MEDLIGHT_URI . '/assets/dist/admin.js', [], MEDLIGHT_VERSION, true);
        wp_enqueue_style("admin-style", MEDLIGHT_URI . '/assets/dist/admin.css', [], MEDLIGHT_VERSION);
    }

    /**
     * load front end scripts
    */
    public function scripts_enqueue(){
        wp_enqueue_script("index-assets", MEDLIGHT_URI . '/assets/dist/index.js', [], MEDLIGHT_VERSION, true);
        wp_enqueue_script("static", MEDLIGHT_URI . '/assets/static/js/static.js', [], MEDLIGHT_VERSION, true);
        wp_enqueue_style("index-style", MEDLIGHT_URI . '/assets/dist/index.css', [], MEDLIGHT_VERSION);
    }


    /**
     * Registers REST endpoints
     */
    public function register_rest_api(){
        return [
            new \MedLight\Rest\TranslationsAPI,
        ];
    }
 
    /**
     * initializes Custom Gutenburg Blocks
     */
    public function register_blocks(){
        return [
            new Blocks\LanguageContent(),
            new Blocks\LanguageToggler(),
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
