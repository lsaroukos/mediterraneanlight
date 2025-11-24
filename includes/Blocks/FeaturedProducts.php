<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\FeaturedProducts') ){

class FeaturedProducts extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.featured-products');

        //unique block id
        $bid = uniqid('featured-products');

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