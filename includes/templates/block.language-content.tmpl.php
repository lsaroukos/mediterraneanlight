<?php 
/**
 * template file for rendering reviews-slider block on frontend dynamically
 */

use MedLight\Utils\TranslationUtils;

$lang = $attributes['lang'] ?? 'el';
$current_lang = TranslationUtils::get_current_language();

if(  $lang === $current_lang ) :

    echo $content;

endif;
?>
