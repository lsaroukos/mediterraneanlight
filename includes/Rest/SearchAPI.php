<?php 
/**
 * Search API
 */
namespace MedLight\Rest;

use MedLight\Utils\WCUtils;

if( !class_exists('MedLight\Rest\SearchAPI') ){
class SearchAPI extends RestAPI
{

    /**
	 * Override default route
	 * @var string
	 */
	const route = 'search';

    /**
     * API Endpoint, resolves on /wp-json/medlight/v1/
     * 
     * register controller routes
     */
    public function register_api_routes()
    {
        // resolves at /wp-json/medlight/v1/search/products?s=
        register_rest_route( $this->get_namespace(), $this->get_route("products") ,[
            [
                'methods'   =>  \WP_Rest_Server::READABLE,
                'callback'  =>  [$this, 'search_products'],
                'permission_callback'    => [$this,'check_nonce'],  
            ]
        ]);

    }

    /**
     * get a list of all registered translations
     */
    public function search_products( $request ){
       
        global $wpdb;

        $q = sanitize_text_field($request->get_param('s'));
        $page    = (int) $request->get_param('page') ?: 1;
        $limit   = (int) $request->get_param('limit') ?: get_option( 'posts_per_page' );

        if (strlen($q) < 4) {
            return $this->response([
                'status'    => 'fail',
                'products'  =>  "search parameter less than 4 characters"
            ]);exit;
        }

        $product_ids = WCUtils::search_product_by_keyword( $q, $limit, $page );
        $total_results = WCUtils::search_product_total( $q );

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

            $products[] = [
                'id'        => $id,
                'title'     => get_the_title($id),
                'price'     => get_post_meta($id, '_price', true),
                'sku'       => get_post_meta($id, '_sku', true),
                'image'     => !empty($product_img_url) ? $product_img_url : MEDLIGHT_URI."/assets/static/img/no-image.png",
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

}
}