<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 24.02.14
 * Time: 15:01
 */

class Logger_Application_Logger {
    /**
     * @var Logger_Application_Logger
     */
    private static $_instance;
    /**
     * @var array Zend_Log
     */
    private static $_loggers = array();
    /**
     * @var array mapping config`s log level into Zend_Log
     */
    private static $_logLevelMap = array(
        "emerg"  => Zend_Log::EMERG,
        "alert"  => Zend_Log::ALERT,
        "crit"   => Zend_Log::CRIT,
        "error"  => Zend_Log::ERR,
        "warn"   => Zend_Log::WARN,
        "notice" => Zend_Log::NOTICE,
        "info"   => Zend_Log::INFO,
        "debug"  => Zend_Log::DEBUG,
    );
    /**
     * @var array Options of the Logger_Application_Logger constructor
     */
    private static $_options = array();

    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self(self::$_options);
        }
        return self::$_instance;
    }

    /**
     * @param array|Zend_Config $options
     */
    public static function setup($options = array()) {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        self::$_options = $options;
    }

    private $_defaultPriority;

    private function __construct($options = array()) {
        $this->setOptions($options);
    }

    protected function mapPriority($priorityName) {
        if (array_key_exists($priorityName, self::$_logLevelMap)) {
            return self::$_logLevelMap[$priorityName];
        }
        if (null !== $this->_defaultPriority) {
            return $this->_defaultPriority;
        }
        return Zend_Log::ERR;
    }

    protected function setOptions($options = array()) {
        $this->_defaultPriority = Zend_Log::ERR;
        if (empty($options)) {
            $this->setDefaultOptions();
        } else {
            $this->_defaultPriority = $this->mapPriority(isset($options['level']) ? $options['level'] : null);
            foreach ($options['stream'] as $stream => $streamOption) {
                $this->addStream($stream, $streamOption['path'], $this->mapPriority(isset($streamOption['level']) ? $streamOption['level'] : null));
            }
        }
    }

    protected function setDefaultOptions() {
        $this->addStream('system');
    }

    /**
     * @param string $stream Stream name
     * @param null|string $path Log file path
     * @param null|number $priority Priority as in Zend_Log
     * @return bool True if stream was added, False if stream was already existed
     */
    public function addStream($stream, $path = null, $priority = null) {
        if (array_key_exists($stream, self::$_loggers)) return false;
        if (null === $priority) {
            $priority = $this->_defaultPriority;
        }
        if (null === $path) {
            if ($stream !== "system" && array_key_exists("system", self::$_loggers)) {
                /** @var Logger_Application_ZendLogWrapper $systemLogger */
                $systemLogger = self::$_loggers["system"];
                /** @var Logger_Application_ZendLogWrapper $customLogger */
                $customLogger = clone $systemLogger;
                $customLogger->setStream($stream);
                self::$_loggers[$stream] = $customLogger;

                return $customLogger;
            }
            $writer = new Zend_Log_Writer_Null();
        } else {
            $writer = new Zend_Log_Writer_Stream($path);
            $writer->addFilter(new Zend_Log_Filter_Priority($priority));
        }
        $logger = new Logger_Application_ZendLogWrapper($writer);
        $logger->setStream($stream);
        self::$_loggers[$stream] = $logger;
        return true;
    }

    /**
     * @param string $stream Stream name
     * @return Zend_Log configured for current stream
     */
    public function ensureStream($stream) {
        $this->addStream($stream);
        return self::$_loggers[$stream];
    }

    /**
     * @param string $message Log message
     * @param string $stream Stream name
     * @param int $logLevel Message level as in Zend_Log
     * @param null|mixed $extra Additional information for logging
     */
    public function log($message, $stream = "system", $logLevel = Zend_Log::INFO, $extra = null) {
        if (array_key_exists($stream, self::$_loggers)) {
            /**
             * @var $logger Zend_Log
             */
            $logger = $this->ensureStream($stream);
            $logger->log($message, $logLevel, $extra);
        } elseif ($stream !== "system") {
            $this->log("Stream not found: \"$stream\"", "system", Zend_Log::ERR, debug_backtrace());
            $this->log($message, "system", $logLevel, $extra);
        }
    }
}