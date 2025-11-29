<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\WcAddToCartVariationOptionImg') ){

class WcAddToCartVariationOptionImg extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.wc-add-to-cart-variation-option-img');

        //unique block id
        $bid = uniqid('wc-add-to-cart-variation-option-img');

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