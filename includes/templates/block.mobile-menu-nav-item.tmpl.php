<?php 
/**
 * template file for rendering mobile menu Navigation dynamically
 */


?>
<li class="mobile-menu-nav-item">
    <span><a><?php echo $attributes['link']; ?></a></span>
    <?php if( !empty($content) ): ?>
        <span class="open-button"> > </span>
        <ul class="submenu">
            <li class="submenu-label"><span><?php echo $attributes["link"]; ?></span><span class="close-button">⮐</span></li>
            <?php echo $content; ?>
        </ul>
    <?php endif; ?>
</li>