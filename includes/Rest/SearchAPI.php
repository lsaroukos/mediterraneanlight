<?php 
/**
 * Search API
 */
namespace MedLight\Rest;

use MedLight\Utils\TranslationUtils;
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
        // resolves at /wp-json/medlight/v1/search/products?key=
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

        if (strlen($q) < 4) {
            return $this->response([
                'status'    => 'fail',
                'products'  =>  "search parameter less than 4 characters"
            ]);exit;
        }

        $product_ids = WCUtils::search_product_by_keyword( $q );

        if( empty($product_ids) ){
            return $this->response([
                'status'    => 'success',
                'products'  => []
            ]);
        }

        // Build output
        $products = [];
        foreach ($product_ids as $id) {
            $products[] = [
                'id'        => $id,
                'title'     => get_the_title($id),
                'price'     => get_post_meta($id, '_price', true),
                'sku'       => get_post_meta($id, '_sku', true),
                'image'     => get_the_post_thumbnail_url($id, 'medium'),
                'permalink' => get_permalink($id)
            ];
        }

        return $this->response([
            'status'    => 'success',
            'products'  => $products
        ]);

        exit;
    }

}
}