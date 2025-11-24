<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\RelatedPostsSlider') ){

class RelatedPostsSlider extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.related-posts-slider');

        //unique block id
        $bid = uniqid('related-posts-slider');

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