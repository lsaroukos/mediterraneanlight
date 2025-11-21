<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\MobileMenuNavItem') ){

class MobileMenuNavItem extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.mobile-menu-nav-item');

        //unique block id
        $bid = uniqid('mobile-menu-nav-item');

        $html = $template->render ( [
             'attributes' => $attributes, 
             'content'    => $content,
             'block'      => $block,
             'block_id'   => $bid,
         ]);    

         return $html;
    }


}
}