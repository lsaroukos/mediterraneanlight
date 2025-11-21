<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\SearchToggler') ){

class SearchToggler extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.search-toggler');

        //unique block id
        $bid = uniqid('search-toggler');

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