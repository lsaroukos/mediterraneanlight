<?php
/**
 * helper functions for handling translations and itegrate with polylang
 */

namespace MedLight\Utils;

use MedLight\Utils\TranslationUtils as TRNS;    

class WCUtils{

    /**
     * searches products by sku keyword 
     * @param string
     * @param Array array of product ids
     */
    public static function search_product_by_sku_keyword( $keyword ):array{
        global $wpdb;

        return $wpdb->get_col($wpdb->prepare("
            SELECT post_id FROM $wpdb->postmeta
            WHERE meta_key = '_sku' AND meta_value LIKE %s
        ", '%' . $wpdb->esc_like($keyword) . '%'));

    }
   
    /**
     * searches products by categories or tags keyword
     * @param string
     * @param Array array of product ids
     */
    public static function search_products_by_tag_categories_keyword( $keyword ):array{
        global $wpdb;

        return $wpdb->get_col($wpdb->prepare("
            SELECT object_id FROM $wpdb->term_relationships tr
            INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN $wpdb->terms t ON tt.term_id = t.term_id
            WHERE t.name LIKE %s
            AND tt.taxonomy IN ('product_cat', 'product_tag')
        ", '%' . $wpdb->esc_like($keyword) . '%'));


    }

    /**
     * @param string $keyword
     * @param int results limit
     * @param int results set
     * @
     * @return Array
     */
    public static function search_product_by_keyword( $keyword, $limit = 50, $page = 1, $lang=null ) {

        $lang = empty($lang) ? TRNS::get_default_lang() : $lang;
        global $wpdb;

        $offset = ( $page - 1 ) * $limit;
        $like   = '%' . $wpdb->esc_like( $keyword ) . '%';

        $sql = $wpdb->prepare("
            SELECT DISTINCT p.ID
            FROM {$wpdb->posts} p

            LEFT JOIN {$wpdb->postmeta} post_meta
                ON p.ID = post_meta.post_id AND post_meta.meta_key = '_sku'

            LEFT JOIN {$wpdb->term_relationships} tr 
                ON p.ID = tr.object_id

            LEFT JOIN {$wpdb->term_taxonomy} tt
                ON tr.term_taxonomy_id = tt.term_taxonomy_id
                AND tt.taxonomy IN ('product_cat', 'product_tag', 'language')

            LEFT JOIN {$wpdb->terms} t
                ON tt.term_id = t.term_id
            
            WHERE p.post_type = 'product'
            AND p.post_status = 'publish'
            AND (
                p.post_title LIKE %s
                OR p.post_content LIKE %s
                OR p.post_excerpt LIKE %s
                OR post_meta.meta_value LIKE %s
                OR t.name LIKE %s
            )
            AND t.slug = %s
            GROUP BY p.ID
            LIMIT %d OFFSET %d
        ", $like, $like, $like, $like, $like, $lang, $limit, $offset );
        return $wpdb->get_col( $sql );
    }

    /**
     * get total number of searh product results
     */
    public static function search_product_total( $keyword ) {

        $lang = empty($lang) ? TRNS::get_default_lang() : $lang;

        global $wpdb;

        $like = '%' . $wpdb->esc_like( $keyword ) . '%';

        $sql = $wpdb->prepare("
            SELECT COUNT(DISTINCT p.ID)
            FROM {$wpdb->posts} p

            LEFT JOIN {$wpdb->postmeta} sku_meta
                ON p.ID = sku_meta.post_id AND sku_meta.meta_key = '_sku'

            LEFT JOIN {$wpdb->term_relationships} tr 
                ON p.ID = tr.object_id

            LEFT JOIN {$wpdb->term_taxonomy} tt
                ON tr.term_taxonomy_id = tt.term_taxonomy_id
                AND tt.taxonomy IN ('product_cat', 'product_tag', 'language')

            LEFT JOIN {$wpdb->terms} t
                ON tt.term_id = t.term_id

            WHERE p.post_type = 'product'
            AND p.post_status = 'publish'
            AND (
                    p.post_title LIKE %s
                    OR p.post_content LIKE %s
                    OR p.post_excerpt LIKE %s
                    OR sku_meta.meta_value LIKE %s
                    OR t.name LIKE %s
            )
            AND t.slug = %s
        ", $like, $like, $like, $like, $like, $lang );

        return (int) $wpdb->get_var( $sql );
    }


    /**
     * Get term images from term_meta for multiple taxonomies/terms.
     *
     * @param array $terms_by_taxonomy Indexed array of taxonomy => array of term slugs or names.
     *                               Example: [ 'pa_color' => ['red','blue'], 'pa_size' => ['medium','small'] ]
     * @return array Nested array of term images: [ 'pa_color' => [ 'red' => 'url', ... ], ... ]
     */
    public static function get_term_images( $terms_by_taxonomy ) {
        global $wpdb;

        if ( empty( $terms_by_taxonomy ) ) {
            return [];
        }

        $results = [];

        foreach ( $terms_by_taxonomy as $taxonomy => $terms ) {

            if ( empty( $terms ) ) {
                continue;
            }

            // Sanitize the terms
            $terms_placeholders = implode( ',', array_fill( 0, count( $terms ), '%s' ) );

            $query = "
                SELECT t.slug, tm.meta_value AS image_id
                FROM {$wpdb->terms} AS t
                INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id
                INNER JOIN {$wpdb->termmeta} AS tm ON t.term_id = tm.term_id
                WHERE tt.taxonomy = %s
                AND t.slug IN ($terms_placeholders)
                AND tm.meta_key = 'term_image'
            ";

            // Prepare query parameters: first taxonomy, then terms
            $query_params = array_merge( [ $taxonomy ], $terms );

            $rows = $wpdb->get_results( $wpdb->prepare( $query, ...$query_params ) );

            // Map slug => image
            $results[ $taxonomy ] = [];
            if ( $rows ) {
                foreach ( $rows as $row ) {
                    $results[ $taxonomy ][ $row->slug ] = !empty($row->image_id) ? \wp_get_attachment_image_url($row->image_id, 'thumbnail') : "";
                }
            }
        }

        return $results;
    }

}