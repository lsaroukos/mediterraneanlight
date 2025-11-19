<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\MobileMenuToggler') ){

class MobileMenuToggler extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.mobile-menu-toggler');

        //unique block id
        $bid = uniqid('mobile-menu-toggler');

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