<?php 
/**
 * Main block definition class
 */

namespace MedLight\Blocks;

use \MedLight\Utils\StringUtils;

if( !\class_exists('\MedLight\Blocks\Block') ){

abstract class Block {

    /**
     * The block namespace.
     *
     * @var string
     */
    private $namespace = 'medlight';

    /**
     * The relative path to the directory where the block's files are stored.
     *
     * @var string
     */
    protected $blocks_root = MEDLIGHT_DIR . DIRECTORY_SEPARATOR . 'blocks';

    /**
     * Whether the block uses Interactivity API
     *
     * @var bool
     */
    protected $use_interactivity = false;

    public function __construct(){

       $this->register_block();  
       \add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Get blocks directory name that corresponds to current (child) class name
     */
    public function get_block_name(){
        return StringUtils::convertToKebabCase(StringUtils::get_base_class_name(static::class));
    }

    /**
     * @return string path to current block dist directory
     */
    protected function get_block_src_dir(){
        $block_name = $this->get_block_name();
        return implode(DIRECTORY_SEPARATOR, [$this->blocks_root, 'dist', $block_name]);
    }

    /**
     * Register block and scripts
     */
    function register_block() {
        $block_dir = $this->get_block_src_dir();

        $result = \register_block_type(
            $block_dir,
            [
                'render_callback' => [$this, 'render_callback']
            ]
        );
    }

    /**
     * Render callback
     */
    function render_callback($attributes, $content, $block) {

        // load code and return
        ob_start();
        $this->render_html($attributes, $content, $block);
        $html = ob_get_clean();

        return $html ?: null;
    }

    /**
     * Abstract render method
     */
    abstract function render_html($attributes, $content, $block);

    /**
     * Enqueue scripts hook
     */
    public function enqueue_scripts(){}
}
}