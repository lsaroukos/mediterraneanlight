<?php 
/**
 * template file for language toggler menu
 */

use MedLight\Utils\TranslationUtils;

$post_id = get_the_ID();
$links = TranslationUtils::get_all_post_links( $post_id );

$currentLink = array_find( $links, function( $link ){ return $link["is_current"]; } );

?>

<div id="language-toggler" class="language-toggler">
    <div class="language-toggler__dropdown">
        <label class="current-language"><a href=<?php echo $currentLink['link']; ?> ><img src=<?php echo $currentLink['flag']; ?> /><?php echo $currentLink["name"]; ?></a></label>
        <ul class="submenu"><?php 
            foreach( $links as $link ): ?>
                <li><a href="<?php echo $link['link'] ?>" ><img src="<?php echo $link['flag'] ?>" /><?php echo $link["name"]; ?></a></li>
            <?php endforeach;
        ?></ul>
    </div>
</div>