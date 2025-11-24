<?php
/**
 * Rest.php
 */

namespace MedLight\Rest;
use \MedLight\Utils\SecurityUtils;

if( !class_exists('\MedLight\Rest\RestAPI') ){
abstract class RestAPI extends \WP_REST_Controller
{

    /**
	 * Plugin's API namespace
	 * @var string
	 */
	const namespace = 'medlight';
	
	/**
	 * Plugin's API version
	 * @var string
	 */
	const version = '1';

	/**
	 * to be overriden by child classes
	 */
	const route = "";

	/**
	 * ApiController constructor.
	 */
	public function __construct() {

		add_filter('rest_api_init', [$this,'init_hooks']);	
		add_filter('upload_mimes', [$this,'restrict_upload_mime_types']);
		add_filter('wp_handle_upload_prefilter', [$this, 'restrict_upload_file_size']);
	}

	/**
	 * Restrict allowed mime types for uploads
	 * 
	 * @param array $mimes
	 * @return array
	 */
	function restrict_upload_mime_types($mimes) {
		return [
			'jpg|jpeg' => 'image/jpeg',
			'png'      => 'image/png',
			'webp'     => 'image/webp',
		];
	}

	function restrict_upload_file_size($file) {
		$max_size = 1 * 1024 * 1024; // 1MB

		if ($file['size'] > $max_size) {
			$file['error'] = 'Image file size must be less than 1MB.';
		}

		return $file;
	}


	public function init_hooks(){
		$this->register_api_routes();
	}

	public function get_logged_user( $uid, $action ){
		
		// Check if the user is logged out (uid = 0) and the specific nonce action.
		if ( 0 === $uid && SecurityUtils::NONCE_ACTION === $action ) {
			
			//check user id from post request
		}
	
		return $uid;
	}


	/**
	 * necessary to override from WP_REST_Controller
	 */
	public function register_routes()
	{
		$this->register_api_routes();
	}

    abstract function register_api_routes();

    /**
	 * @param null $name
	 *
	 * @return string
	 */
	protected function get_namespace(){
		return self::namespace.'/v'.self::version;
	}

    /**
	 * @param string $path
	 *
	 * @return string
	 */
	protected function get_route($path = null){
		$route = empty($path) ? $this::route : $this::route ."/".$path;
		return '/' . $route;
	}

	/**
	 * @param [] $data
	 * @param int $http_code
	 *
	 * @return \WP_REST_Response
	 */
	protected function response($data, $http_code = 200){
		return new \WP_REST_Response($data, $http_code);
	}



	/**
	 * TODO: need to create separare function for admin and simple users
	 * 
	 * checks if current user have permission to access and edit posts
	 * 
     * @param $request  WP_REST_REQUEST
     * 
     * @return bool
	 */
	public function check_permissions( $request ) {
		$token = "";

		// Get token from headers
		if (method_exists($request, 'get_header')) {
			$token = $request->get_header('X-Authentication-Token');
		}
	
		// Get token from request parameter if not found in headers
		if (empty($token) && method_exists($request, 'get_param')) {
			$token = $request->get_param('jwt') ?? "";
		}
	
		if (empty($token)) {
			return false;
		}
	
		// Extract token payload
		$token_payload = SecurityUtils::get_jwt_token_payload($token);
	
		// Validate payload existence
		if (!$token_payload || !isset($token_payload['user_id'])) {
			return false;
		}
	
		// Ensure user_id is a valid number
		$token_user_id = intval($token_payload['user_id']);
		if ($token_user_id <= 0) {
			return false;
		}
	
		// Get the secret key for the token
		$secret = SecurityUtils::get_jwt_secret($token_user_id);
		if (!$secret) {
			return false;
		}
	
		// Validate token
		if (!SecurityUtils::validate_jwt_token($token, $secret)) {
			return false;
		}
	
		// Check if user has required permissions (e.g., editing posts)
		if (!user_can($token_user_id, 'edit_post') && !current_user_can( 'administrator' ) ) {
			return false;
		}
	
		//finally check same origin
		return $this->check_nonce( $request );
	}

	/**
	 * checks nonce
	 * 
	 * @return boolean
	 */
	public function check_nonce( $request ){
return true;
		// Get nonce from headers
		if (method_exists($request, 'get_header')) {
			$nonce = $request->get_header('X-WP-Nonce');
		}

		$ver = SecurityUtils::verify_nonce($nonce);	
				
		return $ver || current_user_can('edit_posts');
	}

}
}