<?php
/**
 * Register WC functions
 */

namespace medlight\Src;

use MedLight\Src\Template;

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
}
}