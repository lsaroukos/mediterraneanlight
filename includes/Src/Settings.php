<?php
/**
 * Theme settings
 */

namespace medlight\Src;

if( !class_exists('Medlight\Src\Settings') ){

class Settings{

    /**
     * 
     */
    public function __construct(){
/*        add_action('after_setup_theme', [$this,'init_settings'] );
        add_action( 'widgets_init', [$this,'register_sidebars'] );
        add_action( 'customize_register', [$this,'register_customizer_settings'] );
        add_filter('upload_mimes', [$this,'allow_svg_uploads_admin_only']);
        add_action( 'after_setup_theme', [$this,'register_menus'] );*/
     //   add_action('admin_init', [$this,'register_pages']);
    }
    
    /**
     * allow svg uploads only for admins
     */
    public function allow_svg_uploads_admin_only($mimes) {
        if (current_user_can('administrator')) {
            $mimes['svg'] = 'image/svg+xml';
        }
        return $mimes;
    }

    /**
     * allow extra theme settings
     */
    public function init_settings(){
        add_theme_support( 'custom-logo' );   //enable custom logo
    }

    /**
     * Customizer API settings
     */
    function register_sidebars(){

        register_sidebar( [
            'name' => "Footer Widget Area 1",
            'id' => "footer1",
            'description' => "footer widget area 1",
        ]);

        register_sidebar( [
            'name' => "Footer Widget Area 2",
            'id' => "footer2",
            'description' => "footer widget area 2",
        ]);

        register_sidebar( [
            'name' => "Footer Widget Area 3",
            'id' => "footer3",
            'description' => "footer widget area 3",
        ]);

        register_sidebar( [
            'name' => "Footer Widget Area 4",
            'id' => "footer4",
            'description' => "footer widget area 4",
        ]);

    }

    /**
     * Register customizer settings
     */
    public function register_customizer_settings( $wp_customize ) {
        
        // register alternative logo seeting and mode
        $wp_customize->add_setting( 'alt_logo', array(
            'title'     =>  'Alt Logo',
            'default'   =>  '',
            'type'      =>  'theme_mod',
            'capability'=>  'edit_theme_options',
            'transport' =>  'refresh',
        ));
        $wp_customize->add_control( new \WP_Customize_Cropped_Image_Control( $wp_customize, 'alt_logo', array(
            'label'      => 'Alt Logo',
            'section'    => 'title_tagline',
            'mime_type'  => 'image',
            'width'      => 300, // Set the desired width for cropping
            'height'     => 100, // Set the desired height for cropping
            'flex_width' => true, // Set to true if you want to allow flexible width
            'flex_height' => true, // Set to true if you want to allow flexible height
        ) ) );

        // contact page
        $wp_customize->add_setting( 'contact-page', array(
            'type'      =>  'theme_mod',
            'default'   =>  '',
        ));
        $wp_customize->add_control( 'contact-page', array(
            'type'          =>  'dropdown-pages',
            'allow_addition'=>  true,   //add new page feature
            'section'       =>  'title_tagline',
            'label'         =>  'Σελίδα Επικοινωνίας',
            'description'   =>  'Σελίδα Επικοινωνίας',
            'settings'      =>  'contact-page',
        ));

        // projects page
        $wp_customize->add_setting( 'projects-page', array(
            'type'      =>  'theme_mod',
            'default'   =>  '',
        ));
        $wp_customize->add_control( 'projects-page', array(
            'type'          =>  'dropdown-pages',
            'allow_addition'=>  true,   //add new page feature
            'section'       =>  'title_tagline',
            'label'         =>  'Σελίδα Προβολής Λίστας Project',
            'description'   =>  'Σελίδα Προβολής Λίστας Project',
            'settings'      =>  'projects-page',
        ));

    }
    

    /**
     * register additional menus
     */
    public function register_menus(){
        
        global $content_width;

        if ( !isset( $content_width ) ) { $content_width = 1920; }
        register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'medlight' ) ) );
        register_nav_menus( array( 'mobile-menu' => esc_html__( 'Mobile Menu', 'medlight' ) ) );
    }

    /**
     * register custom pages
     */
    public function register_pages(){

        register_setting(
            'reading',
            'medlight_search_page',
            [
                'type' => 'integer',
                'sanitize_callback' => 'absint',
                'default' => 0
            ]
        );

        add_settings_field(
            'medlight_search_page',
            __('Search Page', 'medlight'),
            function () {
                $pages = get_pages();
                $value = get_option('medlight_search_page');
                echo '<select name="medlight_search_page">';
                echo '<option value="0">— Select —</option>';
                foreach ($pages as $page) {
                    printf(
                        '<option value="%d" %s>%s</option>',
                        $page->ID,
                        selected($value, $page->ID, false),
                        esc_html($page->post_title)
                    );
                }
                echo '</select>';
            },
            'reading',
            'default'
        );
    }
}
}