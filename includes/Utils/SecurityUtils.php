<?php 
/**
 * MedLight\Utils\SecurityUtils.php
 * 
 */

namespace MedLight\Utils;

use DateTime;
use \ReallySimpleJWT\Token;
use \MedLight\DB\BoatsDB;


if( !class_exists('MedLight\Utils\SecurityUtils') ){
class SecurityUtils{


    const NONCE_ACTION = 'wp_rest'; //important action name to pass authentication if meant to be used for REST requests with X-WP-Nonce header

    /**
     * genereates jwt from token
     * this formed token contains user_id and user_password
     * and we will use this pair later to validate
     * @param int $uid user id
     * 
     * @return string
     */
    public static function get_jwt_user_token($uid=0){

        //sanitize user id
        $uid = empty($uid) ? get_current_user_id() : $uid;
        
        $auth_token = Token::create(
            $uid, //user id
			self::get_jwt_secret( $uid ),   //secret (unique per user)
			time()+3600,           //expiration time 
			$_SERVER['SERVER_NAME'] //domain
		);
		return $auth_token;
    }
    
    /**
     * @return string
     */
    private static function get_jwt_secret_suffix(){
        return "a4R!";
    }
    
    /**
     * 
     * @param int $uid user id
     * 
     * @return string
     */
    public static function get_jwt_secret( $uid=0 ){
        
        if( empty($uid) ){
            return self::get_jwt_secret_suffix(); //if no user id is given, return only the suffix
        }

        //sanitize user id
        $uid = empty($uid) ? get_current_user_id() : $uid;

		$userdata = get_userdata($uid);
		if( empty($userdata) )
			return "";

		return $userdata->user_pass . self::get_jwt_secret_suffix(); //secret must contain a number, a small and a capital letter and a symbol
	}

    /**
     * @param string $payload
     * 
     * @return array
     */
    public static function get_jwt_token_payload( $token ){
        return Token::getPayload($token) ?? [];
    }


    /**
     * validate
     * In short it validates if $token.secret === secret, after of course secoding token
     */
    public static function validate_jwt_token($token, $secret){
        return Token::validate($token, $secret);
    }

    /**
     * generates jwt input field
     */
    public static function get_jwt_field(){
        $uid = \get_current_user_id();
        $auth_token = self::get_jwt_user_token( $uid );
        return '<input type="hidden" class="hidden" name="jwt" value="'. ($auth_token ?? "") .'" />' ;

    }


    /**
     * prints a nonce field
     * @return void
     */
    public static function nonce_field(){
        wp_nonce_field( self::NONCE_ACTION );
    }

    /**
     * @param string $nonce
     */
    public static function verify_nonce( $nonce ){
        return wp_verify_nonce( $nonce, self::NONCE_ACTION );
    }

}
}