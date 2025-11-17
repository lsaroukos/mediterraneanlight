<?php 
/**
 * SettingsPage.php
 */

namespace Emassa\Pages;

if( !class_exists('\Emassa\Pages\SettingsPage') ){
class SettingsPage extends AdminPage{

    protected $name = 'emassa settings';

    protected $parent_slug = 'foodbakery_settings';

    protected $menu_slug = 'locations.php';

    protected $template_name = 'admin/page.settings';
    
    protected $menu_icon = 'dashicons-location-alt';



    public function get_content()
    {   
        $template = $this->get_template();
        if( !is_string($template) )
            $template->render();
    }
}
}