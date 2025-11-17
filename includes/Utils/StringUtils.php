<?php 

namespace MedLight\Utils;


if( !class_exists('\MedLight\Utils\StringUtils') ){

class StringUtils{

    /**
     * converts a string to kebab case
     * e.g. HElloWorld  -> hello-world
     * 
     * @param {string} $string     
     * @return string
     */
    static function convertToKebabCase($string) {
        // Replace uppercase letters with a hyphen before them
        $string = preg_replace('/(?<!^)(?=[A-Z])/', '-', $string);
        // Convert the string to lowercase
        $string = strtolower($string);
        return $string;
    }

    /**
     * returns the class name of a namespaced class
     * @param string $classname namespaced (or not ) classname e.g. PLL_GTNB\Utils\StringUtils
     * 
     * @return string
     */
    static function get_base_class_name( $classname ){
        return basename(str_replace('\\', '/', $classname) );
    }
}
}