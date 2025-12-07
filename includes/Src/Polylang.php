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
        add_filter('pll_get_taxonomies',[$this,'register_pa_as_translatable']);  // make attribute terms translatable
        add_action('init',[$this,'make_attribute_names_translatable']); // register attribute names as translatabe strings
        add_filter('woocommerce_attribute_label',[$this, 'get_attribute_name'],10, 2);   // get attibute name translation TODO: fix because this is wrong
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
     * 
     */
    public function make_attribute_names_translatable() {
        if (!function_exists('pll_register_string') || !function_exists('wc_get_attribute_taxonomies') ) {
            return;
        }

        foreach ( \wc_get_attribute_taxonomies() as $attr) {
            $name = $attr->attribute_label;
            $string_id = 'wc_attribute_' . $attr->attribute_name;

            \pll_register_string($string_id, $name, 'WooCommerce Attributes');
        }
    }

    /**
     * get attribute name translation
     */
    public function get_attribute_name( $label, $name ) {

        if (!function_exists('pll__')) {
            return $label;
        }

      
        return pll__($label);
    }

}
}