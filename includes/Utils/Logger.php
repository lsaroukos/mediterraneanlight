<?php


namespace MedLight\Utils;

use Monolog\Handler\StreamHandler;

use Monolog\Logger as MonoLogger;

class Logger extends MonoLogger{

    /**
     * @var \MedLight\Utils\Logger $instance
     */
    private static $instance;

    /**
     * @var string, path to logs
     *
     */
    private static $project_root = MEDLIGHT_DIR . '/.logs';

    /**
     * @return \MedLight\Utils\Logger $instance
     * @throws \Exception
     */
    public static function get_instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof Logger)) {
            self::$instance = new Logger('local');
            self::$instance
                ->pushHandler(new StreamHandler(MEDLIGHT_DIR . '/.logs' . '/logs.txt', Logger::DEBUG));
        }
        return self::$instance;
    }

    /**
     * @param $value
     *
     * @throws \Exception
     */
    public static function note($context, $message = '')
    {
        $logger = Logger::get_instance();

        if ($context instanceof \Exception) {
            /**
             * @var \Exception $context
             **/
            $context = ["{$context->getMessage()} {$context->getLine()} on file: {$context->getFile()}"];
            $message = "{$message} {$context->getCode()}";
        }

        if (is_string($context) || is_object($context)) {
            $context = [$context];
        }
        if(is_array($message)){
            $message = implode(' ',$message);
        }

        $logger->debug($message, $context);
    }

    /**
     * @param $filepath
     * @param $content
     * @return void
     */
    public static function create_file_with_content($filepath, $content)
    {
        $f = fopen($filepath, 'w');
        fwrite($f, $content);
        fclose($f);
    }
}