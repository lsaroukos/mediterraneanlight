<?php

namespace MedLight\Blocks;

use MedLight\Src\Template;

if( !class_exists('MedLight\Blocks\LanguageToggler') ){

class LanguageToggler extends Block{

    public function render_html($attributes, $content, $block)
    { 
        
        $template = new Template('block.language-toggler');

        //unique block id
        $bid = uniqid('langugage-toggler');

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