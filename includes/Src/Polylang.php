<?php
/**
 * Register Polylang functions
 */

namespace medlight\Src;

use MedLight\Src\Template;
use MedLight\Utils\TranslationUtils as TRNS;

if( !class_exists('\medlight\Src\Polylang') ){

class Polylang{

    /**
     * 
     */
    public function __construct(){       
        add_filter('pll_get_taxonomies',[$this,'register_pa_as_translatable']);
       //add_action('created_term', [$this,'assign_trid_to_term'], 10, 3);
        //add_action('pa_edit_form_fields', [$this,'add_term_edit_translation_fields'], 10, 2);
        //add_action('edited_term', [$this,'save_term_edit_translation_fields'], 10, 3);
    }

    /**
     * adds wc product attributes to the translatable objects list
     * This is important to in turn consider their terms as trasnlatable too
     */
    public function register_pa_as_translatable( $taxonomies ){
        // Add WooCommerce attribute taxonomies dynamically
        foreach (wc_get_attribute_taxonomies() as $attr) {
            $taxonomy = 'pa_' . $attr->attribute_name;
            $taxonomies[$taxonomy] = $taxonomy;
        }
        return $taxonomies;
    }

    /**
     * Create translation groups for terms when a term is created
     */
    public function assign_trid_to_term($term_id, $tt_id, $taxonomy) {
        if (strpos($taxonomy, 'pa_') !== 0) {
            return; // only WC attributes
        }

        if ( empty(TRNS::get_trid( $term_id, 'term')) ) {
            TRNS::set_trid( $term_id, 'term');
        }
    }


    /**
     * Add translation selector on WooCommerce attribute term pages.
     */
    public function add_term_edit_translation_fields($term, $taxonomy) {

        if (strpos($taxonomy, 'pa_') !== 0) {
            return;
        }

        (new Template('admin.wc-attributes-pll-box'))->render([
            'term'  => $term,
            'taxonomy'  => $taxonomy
        ]);

    }

    /**
     * Save translation term
     */
    public function save_term_edit_translation_fields($term_id, $tt_id, $taxonomy) {
        if (empty($_POST['pll_term']) || strpos($taxonomy, 'pa_') !== 0) {
            return;
        }

        $trid = TRNS::get_trid( $term_id, "term" );

        if (!$trid) {
            $trid = TRNS::set_trid( $trid, 'term' );
            if( empty($trid) ) return; 
        }

        foreach ($_POST['pll_term'] as $lang_slug => $linked_id) {

            if (!$linked_id)
                continue;            

            // ensure term has an assigned language
            if ( TRNS::get_lang($linked_id, 'term') ) {
                TRNS::set_lang( $linked_id, 'term', $lang_slug );
            }

            TRNS::set_trid( $linked_id, 'term', $trid );

        }
    }

}
}