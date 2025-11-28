<?php
/**
 * helper functions for handling translations and itegrate with polylang
 */

namespace MedLight\Utils;


class TranslationUtils{

    const DEFAULT_LANG = "en";

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
     * @param int object_id
     * @param string $object_type post|term
     * @param string $lang
     * 
     * @return int|null
     */
    public static function get_translation_id( $object_id, $object_type, $lang ){
        
        if( empty($object_id) || !function_exists('\pll_e') || empty($object_type) ) return null;   
        
        $lang = $lang ? : static::get_current_language();  // get current set language 
        switch( $object_type ){
            case "post":
                return \pll_get_post($object_id, $lang);
            case "term":
                return \pll_get_term($object_id, $lang);
            default:
                return $object_id;
        }
        
    }

    /**
     * @return string
     */
    public static function get_current_language(){
        if( !function_exists('pll_current_language') )
            return static::DEFAULT_LANG;
        return \pll_current_language('slug');
    }

    /**
     * @param int $object_id
     * @param string $object_type
     * 
     * @return string|null
     */
    public static function get_lang( $object_id, $object_type ){
        if( empty($object_id) || empty($object_type) )
            return null;

        switch( $object_type ){
            case "post":
                return function_exists('pll_get_post_language') ? \pll_get_post_language($object_id) : static::DEFAULT_LANG;
            case "term":
                return function_exists('pll_get_term_language') ? \pll_get_term_language($object_id) : static::DEFAULT_LANG;
            default:
                return static::DEFAULT_LANG;
        }
    }
   
    /**
     * @param int $object_id
     * @param string $object_type
     * @param string $lang
     * 
     * @return boolean
     */
    public static function set_lang( $object_id, $object_type, $lang ){
        if( empty($object_id) || empty($object_type) )
            return false;

        switch( $object_type ){
            case "post":
                \pll_set_post_language($object_id, $lang);
            case "term":
                \pll_set_term_language($object_id, $lang);
            default:
                return false;
        }

        return true;
    }

    /**
     * Return all frontend links to related posts in other languages 
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

        $current_lang = static::get_lang( $post_id,'post' );

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

    /**
     * @param int term or post id
     * @param string type of object post|term
     * 
     * @return int|null 
     */
    public static function get_trid($object_id, $object_type){
        if( empty($object_id) || empty($object_type) )
            return null;

        switch( $object_type ){
            case "post":
                return \get_post_meta($object_id, '_pll_trid', true );
            case "term":
                return \get_term_meta($object_id, '_pll_trid', true );
            default:
                return null;
        }
    }

    /**
     * @param int term or post id
     * @param string type of object post|term
     * @param int trid
     * 
     */
    public static function set_trid( $object_id, $object_type, $trid=0 ){
        
        if( empty($object_id) || empty($object_type) )  return null;

        if( empty($trid) )
            $trid = static::get_new_trid();

        switch( $object_type ){
            case "post":
                \update_post_meta($object_id, '_pll_trid', $trid );
                return $trid;
            case "term":
                \update_term_meta($object_id, '_pll_trid', $trid );
                return $trid;
            default:
                return null;
        }

    }
    
    /**
     * Return a new (unique) TRID integer.
     *
     * We probe both termmeta and postmeta for existing _pll_trid values and
     * return max+1. As an extra fallback (to avoid tiny race conditions),
     * we also generate a large unique integer if needed.
     *
     * @return int|string  an integer TRID (or large string-int)
     */
    public static function get_new_trid() {
        global $wpdb;

        // Get max trid from termmeta
        $term_max = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->termmeta} WHERE meta_key = %s",
                '_pll_trid'
            )
        );

        // Get max trid from postmeta (Polylang also stores _pll_trid there)
        $post_max = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key = %s",
                '_pll_trid'
            )
        );

        $max = max($term_max, $post_max);

        if ($max > 0) {
            // Normal case: return next integer
            return $max + 1;
        }

        // Fallback: create a large unique integer based on microtime + random
        // This should basically never collide.
        $micro = microtime(true);
        $rand  = wp_rand(1000, 9999);
        // Multiply to preserve integer-like value but keep it as string if too large
        $trid = (string) ((int) ($micro * 1000) . $rand);

        return $trid;
    }

    /**
     * @return Array of $language objects [name, slug,...]
     */
    public static function get_all_languages(){
        return pll_languages_list(['fields' => []]);

    }

}