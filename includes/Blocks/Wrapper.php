<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\Wrapper') ){

class Wrapper extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.wrapper');

        //unique block id
        $bid = uniqid('wrapper-block');

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