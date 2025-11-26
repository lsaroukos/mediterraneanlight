<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\ProductItem') ){

class ProductItem extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.product-item');

        //unique block id
        $bid = uniqid('product-item');

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