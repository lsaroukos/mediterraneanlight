<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\LatestPostsSlider') ){

class LatestPostsSlider extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.latest-posts-slider');

        //unique block id
        $bid = uniqid('latest-posts-slider');

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