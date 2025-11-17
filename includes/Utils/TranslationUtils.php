<?php
/**
 * helper functions for handling translations and itegrate with polylang
 */

namespace MedLight\Utils;


class TranslationUtils{

    /**
     * gets a post id and returns its corresponding post id 
     * @param int $pid : post id
     * @param string $language : language slug
     * 
     * @return int $pid
     */
    public static function get_post_translation( $pid=0, $language=null ){
    
        if( empty($pid) )   return false;   
        
        if( function_exists('\pll_e') ){    // check if polylang is activated
            $language = $language ?? pll_current_language('slug');  // get current set language 
            $pid = pll_get_post($pid, $language);   // get pid tarnslation
        }
        
        return $pid;
    }

    /**
     * @return string
     */
    public static function get_current_language(){
        if( !function_exists('pll_current_language') )
            return "en";
        return \pll_current_language('slug');
    }

    /**
     * get post language slug
     * @param int $post_id
     * @return string
     */
    public static function get_post_lang( $post_id ){
        if( function_exists('pll_get_post_language') )
            return \pll_get_post_language( $post_id, 'slug' );
        return "en";
    }
    

}