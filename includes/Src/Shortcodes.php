<?php
/**
 * Register Shortcodes
 */

namespace medlight\Src;


if( !defined('\medlight\Src\Shortcodes') ){

class Shortcodes{

    /**
     * 
     */
    public function __construct(){
        add_shortcode('year', [$this,'ml_current_year']);   // shortcode [year]
    }


    /**
     * Shortcode: [year]
     **/ 
    function ml_current_year(){
        return date('Y');
    }
}
}