<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.06.14
 * Time: 13:03
 */

class Logger_Application_LoggerTest extends PHPUnit_Framework_TestCase {

    public function testLogger() {

        date_default_timezone_set("Europe/Moscow");

        Logger_Application_Logger::setup(array(
            "level" => "debug",
            "stream" => array()
        ));
        $logger = Logger_Application_Logger::getInstance();
        $logger->addStream("error", realpath(".") . DIRECTORY_SEPARATOR . "error.log", Zend_Log::DEBUG);
        $logger->addStream("system", realpath(".") . DIRECTORY_SEPARATOR . "system.log");
        $logger->addStream("com.daofx.SomeClass", null, Zend_Log::CRIT);
        $system = $logger->ensureStream("system");
        $custom = $logger->ensureStream("custom");
        $anotherLogger = $logger->ensureStream("com.daofx.SomeClass");

        $system->log("Hello!", Zend_Log::ERR);
        $custom->log("test", Zend_Log::DEBUG);

        $anotherLogger->log("Something bad gonna happened!", Zend_Log::ALERT);
        $anotherLogger->log("As I told — error", Zend_Log::ERR, new Exception("Tro-lo-lo"));

        Pro_Log::log("Pro_Log::log interface");
        Pro_Log::error("Pro_Log::error interface");

        $system->info("test");
        $system->debug("test");
        $system->err("test");
        $system->warn("test");
        $system->alert("test");
        $system->notice("test");

        $anotherLogger->crit("Yes, it's critic!");

        $this->assertTrue(true);
    }
}
 