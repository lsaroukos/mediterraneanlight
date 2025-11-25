<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\ShopLayoutToggler') ){

class ShopLayoutToggler extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.shop-layout-toggler');

        //unique block id
        $bid = uniqid('shop-layout-toggler');

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