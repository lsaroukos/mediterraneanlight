<?php 
/**
 * template file for language toggler menu
 */

use MedLight\Utils\TranslationUtils as TRNS;
use MedLight\Utils\URLUtils;
use MedLight\Utils\WPUtils;

$page_details = WPUtils::get_current_page_details();
switch( $page_details["type"] ){
    case("post" ):
        $links = TRNS::get_all_object_links( $page_details['post_id'], "post" );
        break;
    case("taxonomy"):
        $links = TRNS::get_all_object_links( $page_details['term_id'],"term",$page_details["taxonomy"] );
        break;
    case("special"):
        $links = TRNS::get_all_special_links( URLUtils::get_current_url() );
        break;
    default:
        $links = null;  
        break;
}
    
if( !empty($links) ):

$currentLink = array_find( $links, function( $link ){ return $link["is_current"]; } );
?>

<div id="<?php echo $block_id; ?>" class="language-toggler">
    <div class="language-toggler__dropdown">
        <label class="current-language">
            <input type="text" onfocusin="this.parentElement.classList.add('open')"  onfocusout="this.parentElement.classList.remove('open')" class="selector">
                <img src="<?php echo $currentLink['flag']; ?>" /><?php echo $currentLink["name"]; ?>
            </label>
        <ul class="submenu"><?php 
            foreach( $links as $link ): ?>
                <li><a href="<?php echo $link['link'] ?>" ><img src="<?php echo $link['flag'] ?>" /><?php echo $link["name"]; ?></a></li>
            <?php endforeach;
        ?></ul>
    </div>
</div>

<?php endif; ?>