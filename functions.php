<?php
/**
 * Mediterranean Light WP Theme
 */
$loader = require_once  __DIR__ . '/vendor/autoload.php';

if(!defined ('MEDLIGHT_VERSION')) {
    define('MEDLIGHT_VERSION', '1.0.6' );
    define('MEDLIGHT_LANG_DOMAIN', 'medlight' );
    define('MEDLIGHT_THEME_NAME', 'medlight');   //IMPORTANT to match with Theme Name in style.css
    define('MEDLIGHT_FILE', __FILE__);
    define('MEDLIGHT_DIR', get_stylesheet_directory());    //without trailing slash
    define('MEDLIGHT_URI', get_stylesheet_directory_uri());   
}
 
 
if( class_exists ('\MedLight\Theme') ){
    //initialize Theme
    $medlight = new \MedLight\Theme();
}
