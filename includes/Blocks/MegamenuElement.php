<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\MegamenuElement') ){

class MegamenuElement extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.megamenu-element');

        //unique block id
        $bid = uniqid('megamenu-element');

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