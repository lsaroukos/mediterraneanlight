<?php
/**
 * helper functions for handling translations and itegrate with polylang
 */

namespace MedLight\Utils;


class TranslationUtils{

    const DEFAULT_LANG = "en";


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
     * Returns language assigned to the object. if no language is assigned, the default lang is returned.
     * Returns null if the object id is empty
     * 
     * @return string|null
     */
    public static function get_lang( int $object_id, string $object_type ){
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

        $lang = empty($lang) ? static::DEFAULT_LANG : $lang;

        $slug = $object_type==='post' ? $lang : 'pll_'.$lang;   // pll_$lang is used for terms
        $taxonomy = $object_type==='post' ? 'language' : 'term_language';
        
        $language_term = get_term_by('slug', $slug, $taxonomy); // get language entry from the wp_term_taxonomy table

        if ($language_term && !is_wp_error($language_term)) {
            wp_set_post_terms($object_id, [$language_term->term_id], $taxonomy, true);  // relate them in the wp_relationships table
        }

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
    public static function get_trid($object_id, $object_type='post'){
        global $wpdb;

        if (empty($object_id) || empty($object_type)) return null;

        $taxonomy = $object_type==='post' ? 'post_translations' : 'term_translations';

        // SQL: find the term_id of the hidden translation group
        $trid = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT tt.term_id
                FROM {$wpdb->term_relationships} tr
                INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE tr.object_id = %d
                AND tt.taxonomy = %s
                LIMIT 1",
                $object_id,
                $taxonomy
            )
        );

        return $trid ? (int)$trid : null;
    }

    /**
     * @param int term or post id
     * @param string type of object post|term
     * @param int trid
     * 
     */
    public static function set_trid($object_id, $object_type, $trid = 0) {
        if (empty($object_id) || empty($object_type)) return null;

        $taxonomy = $object_type === 'post' ? 'post_translations' : 'term_translations';

        // If no TRID provided, create a new hidden term
        if (empty($trid) || is_wp_error(get_term($trid, $taxonomy)) ) {
            $trid = static::get_new_trid($object_type);
        }
        
        // Assign object to the translation group
        wp_set_object_terms($object_id, [$trid], $taxonomy, false);

        return $trid;
    }


    
    /**
     * Return a new (unique) TRID integer.
     *
     * We probe both termmeta and postmeta for existing _pll_trid values and
     * return max+1. As an extra fallback (to avoid tiny race conditions),
     * we also generate a large unique integer if needed.
     *
     * @return int|null could not assign a new trid
     */
    public static function get_new_trid( $object_type='post' ) {
        $taxonomy = $object_type === 'post' ? 'post_translations' : 'term_translations';

        // Create a dummy term to get a unique ID
        $name = 'pll_' . uniqid();
        $term_id = wp_insert_term($name, $taxonomy);

        if (is_wp_error($term_id)) {
            return null;
        }

        return (int)$term_id['term_id'];
    }

    /**
     * @return Array of $language objects [name, slug,...]
     */
    public static function get_all_languages(){
        return pll_languages_list(['fields' => []]);

    }

    /**
     * @param int $pid product id
     * @return Array [lang->transation_id]
     */
    public static function get_all_translations( $object_id, $object_type="post" ){
        if( !function_exists('pll_get_post_translations') ) return [];

        if ( empty($object_id) )    return []; 
        
        global $wpdb;

        $taxonomy = $object_type==='post' ? 'post_translations' : 'term_translations';

        // SQL: find the json_encoded list of translations
        $serialized = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT tt.description 
                FROM {$wpdb->term_relationships} tr
                INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
                WHERE tr.object_id = %d
                AND tt.taxonomy = %s
                LIMIT 1",
                $object_id,
                $taxonomy
            )
        );

        // error_log( "serialized ".print_r($serialized, true) );
        if ( empty($serialized) )   return [];

        $translations = maybe_unserialize( $serialized );

        if ( ! is_array($translations) ) {
            return [];
        }

        /**
         * 3) Format result: lang => [object_id]
         */
        $result = [];

        foreach ( $translations as $lang => $obj_id ) {
            if ( ! empty($obj_id) ) {
                $result[$lang] = [ intval($obj_id) ];
            }
        }

        return $result;
    }

    /**
     * Add a new object as a translation of a source object
     *
     * @param int $source_object_id
     * @param int $new_object_id
     * @param string $new_object_lang
     * @param string $object_type 'post'|'term'
     *
     * @return void
     */
    public static function add_translation($source_object_id, $new_object_id, $new_object_lang, $object_type = 'post') {
        if( !function_exists('pll_get_post_translations') ) return;

        if ( empty($source_object_id) || empty($new_object_id) || empty($new_object_lang) ) {
            return false;
        }

        global $wpdb;

        $taxonomy = $object_type === 'post' ? 'post_translations' : 'term_translations';

        /**
         * STEP 1: Get source object's TRID
         */
        $trid = $wpdb->get_var(
            $wpdb->prepare("
                SELECT tt.term_taxonomy_id
                FROM {$wpdb->term_relationships} tr
                INNER JOIN {$wpdb->term_taxonomy} tt 
                        ON tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE tr.object_id = %d
                AND tt.taxonomy = %s
                LIMIT 1
            ", $source_object_id, $taxonomy )
        );

        /**
         * STEP 2: If no TRID exists, create a new one
         */
        if ( empty($trid) ) {

            // Generate unique slug for the relationship term (Polylang-style)
            $slug = 'pll_' . wp_generate_password( 12, false, false );

            // Insert term
            $term = wp_insert_term( $slug, $taxonomy );

            if ( is_wp_error($term) ) {
                return false;
            }

            $trid = $term['term_taxonomy_id'];

            // Create empty translation array with only the source object (but we need source lang)
            // We'll read its language next
        }

        /**
         * STEP 3: Read current serialized translations from TRID
         */
        $serialized = $wpdb->get_var(
            $wpdb->prepare("
                SELECT description
                FROM {$wpdb->term_taxonomy}
                WHERE term_taxonomy_id = %d
                LIMIT 1
            ", $trid )
        );

        $translations = [];

        if ( ! empty($serialized) ) {
            $data = maybe_unserialize( $serialized );
            if ( is_array($data) ) {
                $translations = $data;
            }
        }

        /**
         * STEP 4: Add or update the new translation
         */
        $translations[ $new_object_lang ] = intval($new_object_id);
        $source_object_lang = static::get_lang($source_object_id, $object_type);
        if( empty($translations[$source_object_lang]) )
            $translations[ $source_object_lang ] = intval($source_object_id);

        /**
         * STEP 5: Save back updated serialized array
         */
        $wpdb->update(
            $wpdb->term_taxonomy,
            [ 'description' => serialize( $translations ) ],
            [ 'term_taxonomy_id' => $trid ],
            [ '%s' ],
            [ '%d' ]
        );

        /**
         * STEP 6: Ensure the new object is attached to the TRID relationship
         */
        wp_set_object_terms( $new_object_id, intval($trid), $taxonomy, false );

        return true;
    }


    /**
     * Get translated variation attributes for a given variation and language
     *
     * @param int $variation_id ID of the source variation
     * @param string $lang Language slug (e.g., 'fr', 'de')
     * @return array Translated attributes ready to assign to a variation
     */
    public static function get_translated_variation_attributes( int $variation_id, string $lang ): array {
        if ( empty( $variation_id ) || empty( $lang ) ) return [];

        $variation = wc_get_product( $variation_id );
        if ( ! $variation ) return [];

        $original_attributes = $variation->get_attributes();
        $translated_attributes = [];

        
        foreach ( $original_attributes as $attr_name => $slug_value ) {
            
            // Only handle taxonomy-based attributes
            if ( \taxonomy_exists( $attr_name ) ) {
                
                $term = get_term_by( 'slug', $slug_value, $attr_name );
                if ( $term ) {
                    $translated_term_id = static::get_translation_id($term->term_id, 'term', $lang );
                    if ( $translated_term_id ) {
                        $translated_term = get_term( $translated_term_id, $attr_name );
                        if ( $translated_term ) {
                            $translated_attributes[ $attr_name ] = $translated_term->slug;
                        }
                    }
                }

            } else {
                $translated_attributes[ $attr_name ] = $slug_value; // Non-taxonomy attribute (custom attribute)
            }
        }

        return $translated_attributes;
    }



}