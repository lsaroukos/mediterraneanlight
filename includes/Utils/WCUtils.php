<?php
/**
 * helper functions for handling translations and itegrate with polylang
 */

namespace MedLight\Utils;


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
     * @return Array
     */
    public static function search_product_by_keyword( $keyword ){
        global $wpdb;

        $like = '%' . $wpdb->esc_like($keyword) . '%';

        // Custom SQL OR search
        $sql = $wpdb->prepare("
            SELECT DISTINCT p.ID
            FROM {$wpdb->posts} p
            
            LEFT JOIN {$wpdb->postmeta} sku_meta
                ON p.ID = sku_meta.post_id AND sku_meta.meta_key = '_sku'

            LEFT JOIN {$wpdb->term_relationships} tr 
                ON p.ID = tr.object_id
            
            LEFT JOIN {$wpdb->term_taxonomy} tt
                ON tr.term_taxonomy_id = tt.term_taxonomy_id
                AND tt.taxonomy IN ('product_cat', 'product_tag')

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
            LIMIT 50
        ", $like, $like, $like, $like, $like);

        return $wpdb->get_col($sql);

    }
}