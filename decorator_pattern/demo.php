<?php

interface Logger {

    public function log($msg);
}

class FileLogger implements Logger {

    public function log($msg) {
        echo "<p>Logging to a <b>file</b> : {$msg}</p>";
    }

}

abstract class LoggerDecorator implements Logger {

//    private $logger;
    protected $logger;

    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

//    abstract public function log($msg);
    public function log($msg) {
        $this->logger->log($msg);
    }

}

class EmailLogger extends LoggerDecorator {

    public function log($msg) {
        $this->logger->log($msg);
        echo "<p>Logging to a <b>Email</b> : {$msg}</p>";
//        $this->logger->log($msg);
    }

}

class TextMessagesLogger extends LoggerDecorator {

    public function log($msg) {
        $this->logger->log($msg);
        echo "<p>Logging to a <b>SMS</b> : {$msg}</p>";
//        $this->logger->log($msg);
    }

}

class PrintLogger extends LoggerDecorator {

    public function log($msg) {
        
        echo "<p>Logging to a <b>Print</b> : {$msg}</p>";
        $this->logger->log($msg);
    }

}

class FaxLogger extends LoggerDecorator {

    public function log($msg) {
        $this->logger->log($msg);
        echo "<p>Logging to a <b>FAX</b> : {$msg}</p>";
    }

}

$log = new FileLogger();
$log = new TextMessagesLogger($log);
//$log = new PrintLogger($log);
$log = new FaxLogger($log);




$log->log('save file');
