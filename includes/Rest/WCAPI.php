<?php 
/**
 * Search API
 */
namespace MedLight\Rest;

use MedLight\Utils\WCUtils;
use MedLight\Utils\TranslationUtils as TRNS;

if( !class_exists('MedLight\Rest\WCAPI') ){
class WCAPI extends RestAPI
{

    /**
	 * Override default route
	 * @var string
	 */
	const route = 'wc';

    /**
     * API Endpoint, resolves on /wp-json/medlight/v1/wc
     * 
     * register controller routes
     */
    public function register_api_routes()
    {
        // resolves at /wp-json/medlight/v1/wc/products/search?s=
        register_rest_route( $this->get_namespace(), $this->get_route("products/search") ,[
            [
                'methods'   =>  \WP_Rest_Server::READABLE,
                'callback'  =>  [$this, 'search_products'],
                'permission_callback'    => [$this,'check_nonce'],  
            ]
        ]);
        register_rest_route( $this->get_namespace(), $this->get_route("products/featured") ,[
            [
                'methods'   =>  \WP_Rest_Server::READABLE,
                'callback'  =>  [$this, 'get_featured_products'],
                'permission_callback'    => [$this,'check_nonce'],  
            ]
        ]);
        register_rest_route( $this->get_namespace(), $this->get_route("terms/images") ,[
            [
                'methods'   =>  \WP_Rest_Server::CREATABLE,
                'callback'  =>  [$this, 'get_swatches'],
                'permission_callback'    => [$this,'check_nonce'],  
            ]
        ]);

    }

    /**
     * search on all products in any given language
     */
    public function search_products( $request ){
        
        // get posts in requested language
        $lang = null;
        if (method_exists($request, 'get_header')) {
			$lang = $request->get_header('X-WP-Lang');
		}
        $lang = empty($lang) ? TRNS::get_default_lang() : $lang;
       
        $q = sanitize_text_field($request->get_param('s'));
        $page    = (int) $request->get_param('page') ?: 1;
        $limit   = (int) $request->get_param('limit') ?: get_option( 'posts_per_page' );

        if (strlen($q) < 4) {
            return $this->response([
                'status'    => 'fail',
                'products'  =>  "search parameter less than 4 characters"
            ]);exit;
        }

        $product_ids = WCUtils::search_product_by_keyword( $q, $limit, $page, $lang );
        $total_results = WCUtils::search_product_total( $q, $lang );

        if( empty($product_ids) ){
            return $this->response([
                'status'    => 'success',
                'products'  => [],
                'total_results' => 0
            ]);
        }

        // Build output
        $products = [];
        foreach ($product_ids as $id) {
            
            $product_img_url = get_the_post_thumbnail_url($id, 'medium');

           // $id = TRNS::get_translation_id($id, 'post', $lang );    // only return products in $lang
            if( empty($id) || !empty($products[$id]) ) continue;  // if already assigned (from a translation) skip

            $products[ $id ] = [
                'id'        => $id,
                'title'     => get_the_title($id),
                'price'     => get_post_meta($id, '_price', true),
                'sku'       => get_post_meta($id, '_sku', true),
                'image'     => !empty($product_img_url) ? $product_img_url : MEDLIGHT_URI."/assets/static/img/no-product-image__small.png",
                'permalink' => get_permalink($id)
            ];
        }

        return $this->response([
            'status'    => 'success',
            'products'  => $products,
            'total_results' => $total_results
        ]);

        exit;
    }

    /**
     * return featured products
     */
    public function get_featured_products( $request ) {

        // get posts in requested language
        $lang = null;
        if (method_exists($request, 'get_header')) {
			$lang = $request->get_header('X-WP-Lang');
		}
        $lang = empty($lang) ? TRNS::get_default_lang() : $lang;

        $limit = $request->get_param("limit") ?: 12;

        $args = [
            'post_type'      => 'product',
            'numberposts'    => $limit,
            'post_status'    => 'publish',
            'tax_query'      => [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ]
            ],
            'lang'  => $lang

        ];

        $posts = get_posts($args);
        $products = [];

        foreach ( $posts as $post ) {

            $product = wc_get_product( $post->ID );
            if ( ! $product ) continue;

            $image_id = $product->get_image_id();
            $image_src = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : MEDLIGHT_URI."/assets/static/img/no-product-image__small.png";

            $products[] = [
                'id'              => $product->get_id(),
                'title'           => $product->get_name(),
                'regular_price'   => $product->get_regular_price(),
                'sale_price'      => $product->get_sale_price(),
                'rating'          => $product->get_average_rating(), // number from 0–5
                'thumbnail'       => $image_src,
                'permalink'       => get_permalink($product->get_id())
            ];
        }

        return $this->response([
            "status"   => "success",
            "products" => $products
        ]);
    }

    /**
     * return an array of term taxonomies->terms->img_urls
     */
    public function get_swatches( $request ){

        $terms_by_taxonomy = $request->get_param('terms');

        return $this->response( [
            "status"    =>  "success",
            "terms"     =>  WCUtils::get_term_images( $terms_by_taxonomy )              
        ] );

        exit;
    }

}
}