<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.06.14
 * Time: 12:31
 */

class Logger_Application_ZendLogWrapper extends Zend_Log {

    protected $stream;
    public function setStream($stream) {
        $this->stream = $stream;
    }

    public function log($message, $priority, $extras = null) {
        if ($this->stream !== null && $this->stream !== "system") {
            $message = $this->stream . " " . $message;
        }
        parent::log($message, $priority, $extras);
    }

}