<?php
/**
 * Functions to interract with the frontend pages
 */

namespace medlight\Src;

use MedLight\Src\Template;
use MedLight\Utils\SecurityUtils;

if( !defined('\medlight\Src\Frontend') ){

class Frontend{

    /**
     * 
     */
    public function __construct(){
        add_action("wp_footer",[$this, "footer_actions"]);
    }

    /**
     * actions to run before </body> tag is rendered
     */
    public function footer_actions(){
        SecurityUtils::nonce_field();   // render a nonce field, necessary for API calls
        (new Template("final-elements"))->render();
    }
}
}