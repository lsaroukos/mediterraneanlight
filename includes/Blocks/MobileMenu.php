<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\MobileMenu') ){

class MobileMenu extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.mobile-menu');

        //unique block id
        $bid = uniqid('mobile-menu');

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