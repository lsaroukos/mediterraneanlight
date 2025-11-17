<?php 

namespace MedLight\Src;

class Template{
    
    //directory of public folder that hosts template files
    public $templates_dir;

    protected $file = null; //holds the full filename and path

    private $suffix = '.tmpl.php'; 

    /**
     * @param string $fname
     * 
     */
    public function __construct( $fname='default' ){

        //define templates directory path
        $this->templates_dir = implode(DIRECTORY_SEPARATOR, [ MEDLIGHT_DIR , 'includes' , 'templates','' ]);
        
        //get full file name
        $filepath = $this->get_full_fname($fname);

        //check if file exists
        if( file_exists( $filepath ) ){
            $this->file = $filepath;
        }

    }

    /**
     * @return Boolean
     */
    public function is_valid(){
        return file_exists( $this->file );
    }

    /**
     * @param $fname
     * 
     */
    private function get_full_fname( $fname ){
        return $this->templates_dir . $fname . $this->suffix;
    }

    /**
     * returns the full path to the template file
     */
    public function get_file(){
        return $this->file;
    }

    /**
     * @return string the code contained in the file template
     */
    public function get(array $options = [])
    {
        if($this->file){
            extract ($options); //extract options from array
            ob_start();
                if( !empty($attributes) ){
                    extract( $attributes );
                }
                include $this->file;
            return ob_get_clean();
        }

        return '';
    }

    /**
     * prints the code contained in the file template
     */
    public function render(array $options = []){
        echo $this->get($options);
    }
}