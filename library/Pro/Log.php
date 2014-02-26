<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 24.02.14
 * Time: 15:01
 */

/**
 * Class Pro_Log
 * class for compatibility with current architecture, all methods are deprecated,
 * use Logger_Application_Logger instead
 */
class Pro_Log {
    /**
     * Write a log message
     *
     * @param string $message Log message
     * @param string $stream Stream name
     * @param int $logLevel Log level
     * @param mixed $extra Extra log information
     *
     * @deprecated
     */
    public static function log($message, $stream = "system", $logLevel = Zend_Log::INFO, $extra = null) {
        $message = "[DEPRECATED] $message";
        Logger_Application_Logger::getInstance()->log($message, $stream, $logLevel, $extra);
    }

    /**
     * Write a default log message
     *
     * @param string $message
     *
     * @deprecated
     */
    public static function error($message) {
        $message = "[DEPRECATED] $message";
        Logger_Application_Logger::getInstance()->log($message, "system", Zend_Log::ERR);
    }
}