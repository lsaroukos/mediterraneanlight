<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\LanguageContent') ){

class LanguageContent extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.language-content');

        //unique block id
        $bid = uniqid('langugage-content');

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