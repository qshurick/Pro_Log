<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 24.02.14
 * Time: 18:17
 */

class Pro_Resource_Log extends Zend_Application_Resource_ResourceAbstract {
    /**
     * Strategy pattern: initialize resource
     *
     * @return mixed
     */
    public function init() {
        Pro_Log::setup($this->getOptions());
        $logger = Pro_Log::getInstance();
        Zend_Registry::set('logger', $logger); // share logger
        return $logger;
    }

}