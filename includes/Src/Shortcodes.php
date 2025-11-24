<?php
/**
 * Register Shortcodes
 */

namespace medlight\Src;

use MedLight\Src\Template;

if( !class_exists('\medlight\Src\Shortcodes') ){

class Shortcodes{

    /**
     * 
     */
    public function __construct(){
        add_action('init',[$this,'register_shortcodes']);
    }
    
    public function register_shortcodes(){
        add_shortcode('year', [$this,'current_year']);   // shortcode [year]
        add_shortcode('medlight_social_share', [$this,'social_share_links'] );
    }

    /**
     * Shortcode: [year]
     **/ 
    public function current_year(){
        return date('Y');
    }

    /**
     * Shortcode [social_share]
     */
    public function social_share_links(){
        return (new Template("shortcode.social-share") )->get();
    }
}
}