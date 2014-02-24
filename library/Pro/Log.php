<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 24.02.14
 * Time: 14:24
 */

class Pro_Log {
    /**
     * @param string $message Log message
     * @param string $stream Stream name
     * @param int $logLevel Message level as in Zend_Log
     * @param null|mixed $extra Additional information for logging
     */
    public static function log($message, $stream = "system", $logLevel = Zend_Log::INFO, $extra = null) {
        $logger = Pro_Log_Default::getInstance();
        $logger->log($message, $stream, $logLevel, $extra);
    }

    /**
     * @param string $message Log message
     * @param string $stream Stream name
     * @param null|mixed $extra Additional information for logging
     */
    public static function error($message, $stream = "system", $extra = null) {
        self::log($message, $stream, Zend_Log::ERR, $extra);
    }

    /**
     * @param string $message Log message
     * @param int $logLevel Message level as in Zend_Log
     * @param null|mixed $extra Additional information for logging
     */
    public static function system($message, $logLevel = Zend_Log::INFO, $extra = null) {
        self::log($message, "system", $logLevel, $extra);
    }
}