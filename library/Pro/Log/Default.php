<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 24.02.14
 * Time: 15:01
 */

class Pro_Log_Default {
    /**
     * @var Pro_Log_Default
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
     * @var array Options of the Pro_Log_Default constructor
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

    protected function setOptions($options = array()) {
        $this->_defaultPriority = Zend_Log::ERR;
        if (empty($options)) {
            $this->setDefaultOptions();
        } else {
            $confLogLevel = isset($options['level']) ? $options['level'] : "error";
            $defaultLogLevel = Zend_Log::ERR;
            if (array_key_exists($confLogLevel, self::$_logLevelMap)) {
                $defaultLogLevel = self::$_logLevelMap[$confLogLevel];
            }
            $this->_defaultPriority = $defaultLogLevel;
            foreach ($options['stream'] as $stream => $streamOption) {
                $this->addStream($stream, $streamOption['path'], $streamOption['level']);
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
        if (null == $priority) {
            $priority = $this->_defaultPriority;
        }
        if (null == $path) {
            $writer = new Zend_Log_Writer_Stream($path);
            $writer->addFilter(new Zend_Log_Filter_Priority($priority));
        } else {
            $writer = new Zend_Log_Writer_Null();
        }
        self::$_loggers[$stream] = new Zend_Log($writer);
        return true;
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
            $logger = self::$_loggers[$stream];
            $logger->log($message, $logLevel, $extra);
        }
    }
}