<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 24.02.14
 * Time: 18:17
 */

class Logger_Application_Resource_Logger extends Zend_Application_Resource_ResourceAbstract {
    /**
     * Strategy pattern: initialize resource
     *
     * @return mixed
     */
    public function init() {
        Logger_Application_Logger::setup($this->getOptions());
        $logger = Logger_Application_Logger::getInstance();
        Zend_Registry::set('logger', $logger); // share logger
        return $logger;
    }

}