<?php

class Database {

    public function getData($id = 0) {
        echo $id;
    }

}

class Template {

    public function __construct($id = 0, $data = '') {
        echo $id . '<br />';
        echo $data . '<br />';
    }

    public function serve() {
        
    }

}

class Logger {
    public function log($msg = ''){
        echo $msg;
    }
}

class PageFacade {

    public function createAndServe($id, $msg = '') {
        $db = new Database();

        $data = $db->getData($id);

        $template = new Template($id, $data);
        $template->serve();

        $logs = new Logger();
//        $logs->log("Create a page for ID {$id}");
        $logs->log($msg);
    }

}

$page = new PageFacade();

$id = $_GET['id'];
$page->createAndServe($id, "Serving a page for id {$id}");
