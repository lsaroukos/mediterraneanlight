<?php 
/**
 * Translations API
 */
namespace MedLight\Rest;

use MedLight\Utils\TranslationUtils;

if( !class_exists('MedLight\Rest\TranslationsAPI') ){
class TranslationsAPI extends RestAPI
{

    /**
	 * Override default route
	 * @var string
	 */
	const route = 'translations';

    /**
     * API Endpoint, resolves on /wp-json/medlight/v1/
     * 
     * register controller routes
     */
    public function register_api_routes()
    {
        // resolves at /wp-json/medlight/v1/translations/languages
        register_rest_route( $this->get_namespace(), $this->get_route() . '/languages',[
            [
                'methods'   =>  \WP_Rest_Server::READABLE,
                'callback'  =>  [$this, 'get_active_languages'],
                'permission_callback'    => [$this,'check_nonce'],  
            ]
        ]);

        // resolves at /wp-json/medlight/v1/translations/links
        register_rest_route( $this->get_namespace(), $this->get_route('links'.'(/(?P<pid>\d+))?') ,[
            [
                'methods'   =>  \WP_Rest_Server::READABLE,
                'callback'  =>  [$this, 'get_post_language_links'],
                'permission_callback'    => [$this,'check_nonce'],  
                'args' => [
                    "pid" => [
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ],
                ]
            ]
        ]);


    }

    /**
     * get a list of all registered translations
     */
    public function get_active_languages( $request ){

        // Get active Polylang languages
        if (function_exists('pll_the_languages')) {
            $languages = \pll_the_languages(['raw' => 1]);
        } else {
            $languages = [];
        }

        return $this->response([
            'status'    => 'success',
            'languages' =>  $languages
        ]);

        exit;
    }

    /**
     * get links of post_id in all active languages
     * If pid is not defined, then assume home page pid
     * 
     */
    function get_post_language_links( $request ) {

        $post_id = $request->get_param('pid');  // get post id from request

        if( empty($post_id) )
            $post_id = \get_option('page_on_front');

        $links = TranslationUtils::get_all_object_links( $post_id,"post" );
        if ( empty($links) ) {
            return $this->response(['status'    => 'fail','message'   => 'failed to fetch language links']);exit;
        }

        return $this->response([
            'status'    => 'success',
            'links'     =>  $links
        ]);exit;

        exit;
    }
}
}