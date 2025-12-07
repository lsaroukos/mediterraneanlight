<?php 

namespace MedLight\Utils;


if( !class_exists('\MedLight\Utils\URLUtils') ){

class URLUtils{


    /**
     * return base url e.g. https://domain.com:4040/
     */
    public static function get_base_url() {

        // Detect protocol
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
                    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        $protocol = $is_https ? "https://" : "http://";

        // Host
        $host = $_SERVER['HTTP_HOST'];

        // Extract port
        $port = $_SERVER['SERVER_PORT'];

        // Append port only if it's NOT a default one
        $default_port = $is_https ? 443 : 80;
        $port_part = ($port != $default_port) && !str_ends_with($host,$port) ? ':' . $port : '';
      
        return $protocol . $host . $port_part . "/";
    }

    /**
     * return current full url
     */
    public static function get_current_url() {

        // Detect protocol
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
                    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        $protocol = $is_https ? "https://" : "http://";

        // Host
        $host = $_SERVER['HTTP_HOST'];

        // Extract port
        $port = $_SERVER['SERVER_PORT'];

        // Append port only if it's NOT a default one
        $default_port = $is_https ? 443 : 80;
        $port_part = ($port != $default_port) ? ':' . $port : '';

        // URI
        $request_uri = $_SERVER['REQUEST_URI'];

        return $protocol . $host . $port_part . $request_uri;
    }

    /**
     * @param Array asscosiative array returned from parse_url ['scheme','host', 'path', 'query']
     */
    public static function compose_url($parsed_url) {

        $scheme = $parsed_url['scheme'] ?? 'http';
        $host   = $parsed_url['host'] ?? '';
        $path   = $parsed_url['path'] ?? '';
        $query  = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';

        // Handle port
        $port = $parsed_url['port'] ?? null;
        $default_port = ($scheme === 'https') ? 443 : 80;
        $port_part = ($port && $port != $default_port) ? ':' . $port : '';

        return $scheme . '://' . $host . $port_part . $path . $query;
    }
}
}