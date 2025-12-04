<?php
/**
 * Register WC functions
 */

namespace medlight\Src;

use MedLight\Src\Template;
use MedLight\Utils\Logger;
use MedLight\Utils\TranslationUtils as TRNS;

if( !class_exists('\medlight\Src\WC') ){

class WC{

    /**
     * 
     */
    public function __construct(){
        add_action('admin_init', [$this, 'add_attr_term_meta']);
    }   

    public function add_attr_term_meta(){
  
        $attribute_taxonomies = wc_get_attribute_taxonomies();  // get all wc attribute taxonomies
        if( empty($attribute_taxonomies) ) return;  // return

        foreach( $attribute_taxonomies as $tax ){

            $taxonomy = 'pa_' . $tax->attribute_name;

            add_action($taxonomy . '_add_form_fields', [$this,'add_term_add_meta']);   // add term meta box on edit page 
            add_action($taxonomy . '_edit_form_fields', [$this,'add_term_edit_meta']);   // add term meta box on add page 
            add_action('created_' . $taxonomy, [$this,'save_img_on_create'] );
            add_action('edited_' . $taxonomy, [$this,'save_img_on_edit'] );
            add_action('admin_enqueue_scripts', [$this,'enqueue_img_uploader_js'] );
            add_action( 'woocommerce_after_product_object_save', [$this, 'sync_translated_products'],100 );
            add_action( 'woocommerce_new_product_variation', [ $this, 'create_translated_variations' ], 10 ,1 );    // on variation create
            add_action( 'woocommerce_save_product_variation', [$this, 'sync_translated_variations'],100, 2 );   // on variation update
            add_action( 'before_delete_post', [ $this, 'sync_delete_translated_variation' ] );  // on variation delete
        }
        
    }
    
    /**
     * add meta box on term add
     */
    public function add_term_add_meta() { 
        (new Template('admin/meta.wc-attributes-term-add'))->render();
    }

    /**
     * add meta box on term edit
     */
    public function add_term_edit_meta( $term ) { 
        (new Template('admin/meta.wc-attributes-term-edit'))->render(['term'=>$term]);
    }

    /**
     * save img on create term
     */
    public function save_img_on_create( $term_id ){
         if (isset($_POST['term_image'])) {
            \update_term_meta($term_id, 'term_image', absint($_POST['term_image']));
        }
    }

    /**
     * save img on edit term
     */
    public function save_img_on_edit( $term_id ){
         if (isset($_POST['term_image'])) {
            \update_term_meta($term_id, 'term_image', absint($_POST['term_image']));
        }
    }

    /**
     * enqueu img uploader script
     */
    public function enqueue_img_uploader_js( ){

        // Only load on attribute term pages
        if (!isset($_GET['taxonomy']) || strpos(sanitize_text_field($_GET['taxonomy']), 'pa_') !== 0) {
            return;
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'attribute-term-image-js',
            MEDLIGHT_URI . '/assets/static/js/attribute-term-image.js',
            ['jquery'],
            null,
            true
        );
    }

    /**
     * sync translated productmeta data and product type 
     * every time a product is saved. Featured image (product image)
     * is handled by polylang free version
     */
    public function sync_translated_products( $product ) {
        if( $product->get_type() === 'variation' ) 
            return; // variatiosn use their own functions 
        
        // Prevent infinite loops
        if ( defined( 'PLL_SYNCING' ) ) {
            return;
        }

        $post_id = $product->get_id();

        // Get the language of this product
        $lang =  TRNS::get_lang( $post_id, 'post' );
        if ( ! $lang ) return;
        $translations = TRNS::get_all_translations( $post_id ); // Get all translations for this product

        define( 'PLL_SYNCING', true );  // Start syncing flag

        // -----------------------------------------------
        // 1) Sync product type (simple, variable, grouped, external)
        // -----------------------------------------------

        $product_type_terms = wp_get_object_terms( $post_id, 'product_type', ['fields' => 'ids'] );

        foreach ( $translations as $tr_id ) {
            if ( $tr_id == $post_id ) continue;

            // Assign same product type to translation
            wp_set_object_terms( $tr_id, $product_type_terms, 'product_type', false );
        }
        
        // -----------------------------------------------
        // 2) Sync general product meta (prices, stock, attributes, gallery, SKU, etc.)
        // -----------------------------------------------

        $meta_keys = [
            '_sku',
            '_regular_price',
            '_sale_price',
            '_price',
            '_manage_stock',
            '_stock',
            '_stock_status',
            '_backorders',
            '_product_attributes',
            '_product_image_gallery',
        ];

        // Pull source meta values
        $source_meta = [];
        foreach ( $meta_keys as $key ) {
            $source_meta[$key] = get_post_meta( $post_id, $key, true );
        }
        
        foreach ( $translations as $tr_lang => $tr_id ) {

            if ( $tr_id == $post_id ) continue; // skip checking current language product
                       
            foreach ( $source_meta as $key => $value ) {    // Copy meta fields
                \update_post_meta( $tr_id, $key, $value );   
            }

        }

        unset( $GLOBALS['PLL_SYNCING'] );   // Done
    }

    /**
     * function to sync variation updates with its translations
     */
    public function sync_translated_variations( $variation_id, $i ) {

        $variation = wc_get_product( $variation_id );
        $parent_id = $variation->get_parent_id();

        $translations = TRNS::get_all_translations( $parent_id, 'post' );   // Get all translations of the parent product

        foreach ( $translations as $lang => $translated_parent_id ) {
            if ( $translated_parent_id == $parent_id ) continue;

            // Check if a corresponding variation exists
            $translated_var_id = TRNS::get_translation_id( $variation_id, 'post', $lang );

            if ( ! $translated_var_id ) {
                // If missing → log it and skip TODO: add Logger
                continue;
            }

            $tr_var = wc_get_product( $translated_var_id );
            if ( ! $tr_var instanceof \WC_Product_Variation ) {
                continue;
            }
         
            // Sync all relevant fields (always update, even if already exists)
            $translated_atts = TRNS::get_translated_variation_attributes( $variation_id, $lang );
            $tr_var->set_attributes( $translated_atts );
            $tr_var->set_regular_price( $variation->get_regular_price() );  // sync regular price
            $tr_var->set_sale_price( $variation->get_sale_price() );
            $tr_var->set_price( $variation->get_price() );
            $tr_var->set_manage_stock( $variation->get_manage_stock() );
            $tr_var->set_stock_quantity( $variation->get_stock_quantity() );
            $tr_var->set_stock_status( $variation->get_stock_status() );
            $tr_var->set_image_id( $variation->get_image_id() ); 
            $tr_var->save();
            \update_post_meta( $tr_var->get_id(), '_sku', $variation->get_sku() );    // sync sku
        }

    }

    /**
     * delete variation 
     */
    public function sync_delete_translated_variation( $variation_id ) {

        if ( get_post_type( $variation_id ) !== 'product_variation' ) {
            return;
        }

        $translations = TRNS::get_all_translations( $variation_id, 'post' );

        foreach ( $translations as $lang => $tr_var_id ) {
            if ( $tr_var_id != $variation_id ) {
                wp_delete_post( $tr_var_id, true );
            }
        }
    }


    /**
     * run on variation creation
     */
    public function create_translated_variations( $variation_id ) {

        if ( !empty( get_transient('ml_creating_translation') ) ) {
            return;
        }

        set_transient('ml_creating_translation', 1, 30); // 30 sec TTL

        $variation = wc_get_product( $variation_id );
        $parent_id = $variation->get_parent_id();

        // Get all product translations
        $translations = TRNS::get_all_translations( $parent_id, 'post' );

        // Detect source language
        $source_lang = TRNS::get_lang( $variation_id, 'post' );
        if( empty( $source_lang ) )
            TRNS::set_lang( $variation_id, 'post', TRNS::get_lang($parent_id, 'post') );


        foreach ( $translations as $lang => $translated_parent_id ) {

            if ( $lang === $source_lang ) continue;

            // Does translated variation already exist?
            $existing = TRNS::get_translation_id( $variation_id, 'post', $lang );

            if ( $existing ) continue; // Already created

            // Create new variation
            $tr_var = new \WC_Product_Variation();
            $tr_var->set_parent_id( $translated_parent_id );
              
            $tr_var->save();
            
            TRNS::set_lang($tr_var->get_id(),'post', $lang); // define variatiosn language
            \update_post_meta( $tr_var->get_id(), '_sku', $variation->get_sku() );  // update sku

            $tr_var->save();

            // Register translation relationship
            TRNS::add_translation( $variation_id, $tr_var->get_id(), $lang, 'post' );
        }

        delete_transient('ml_creating_translation');
    }

}
}