<?php 
/**
 * Core API
 */
namespace MedLight\Rest;

use MedLight\Utils\SettingsUtils;
use MedLight\Utils\TranslationUtils as TRNS;
use MedLight\Utils\TranslationUtils;

if( !class_exists('Emassa\Rest\CoreAPI') ){
class CoreAPI extends RestAPI
{

    /**
	 * Override default route
	 * @var string
	 */
	const route = 'core';

    /**
     * API Endpoint, resolves on /wp-json/emassa/v1/
     * 
     * register controller routes
     */
    public function register_api_routes()
    {

        // resolves at /wp-json/emassa/v1/core
        register_rest_route( $this->get_namespace(), $this->get_route() ,[
            [
                'methods'   =>  \WP_Rest_Server::READABLE,
                'callback'  =>  [$this, 'get_core_settings'],
                'permission_callback'    => [$this, 'check_nonce'],  
            ]
        ]);
    }


    /**
     * array of core settings
     */
    public function get_core_settings( $request ){

        $pid = $request->get_header('X-Post-ID');   // wp header
        
        //get current language
        $current_lang = TranslationUtils::get_post_lang( $pid );

        //get links
        $links = [
            'home'  => home_url(),
            'search' => get_page_by_path('search')
        ];
        
        return $this->response([
            'status'    => 'success',
            'settings'  =>  [
                'lang'  => $current_lang,
                'links' => $links,
            ]
        ]);
        
        exit;
    }

}
}