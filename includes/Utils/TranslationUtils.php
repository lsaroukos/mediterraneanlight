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


    /**
     * @param int
     * @return Array|null
     */
    public static function get_all_post_links( $post_id ){

        if( empty($post_id) ){ return null; }
        
        if ( ! function_exists( 'pll_the_languages' ) ) {
            return null;
        }

        // Get all languages in raw array format
        $languages = pll_the_languages([
            'raw'              => 1,
            'hide_if_empty'    => 0,
            'hide_current'     => 0,
            'display_names_as' => 'name'
        ]);

        $results = [];

        $current_lang = TranslationUtils::get_post_lang( $post_id );

        foreach ( $languages as $lang ) {

            $translated_post_id = pll_get_post( $post_id, $lang['slug'] );  // Get translated post ID

            $link = $translated_post_id ? get_permalink( $translated_post_id )  : null; // Get link for that language version

            $results[] = [
                'slug'      => $lang['slug'],        // e.g. en, fr, de
                'name'      => $lang['name'],        // "English"
                'flag'      => $lang['flag'],        // IMG HTML for flag
                'post_id'   => $translated_post_id,  // null if missing
                'link'      => $link,                // URL or null
                'is_current'=> $current_lang===$lang['slug']
            ];
        }

        return $results;
    }
    

}