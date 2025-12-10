<?php 

namespace MedLight\Utils;


if( !class_exists('\MedLight\Utils\WPUtils') ){

class WPUtils{

    public static function get_current_page_details() {

        // Special pages
        if ( 
            \is_search() || 
            \is_404() || 
            ( function_exists("is_shop") && \is_shop() ) ||
            \is_date() || \is_month() || \is_year()
        ) {
            return [
                "type" => "special"
            ];
        }

        // Tax, Tag, Category (all are taxonomies)
        if ( \is_tax() || \is_tag() || \is_category() ) {

            $term = get_queried_object(); //  THIS is the key

            return [
                "type"     => "taxonomy",
                "taxonomy" => $term->taxonomy ?? null,
                "term_id"  => $term->term_id ?? null,
                "slug"     => $term->slug ?? null,
                "name"     => $term->name ?? null,
            ];
        }

        // Generic archive (post type archives, author, etc.)
        if ( \is_archive() ) {
            return [
                "type" => "archive"
            ];
        }

        // Single post/page
        return [
            "type"      => "post",
            "post_id"   =>  get_the_ID()
        ];
    }

}
}