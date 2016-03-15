<?php

class eMailBody {

    private $header = 'This is email header';
    private $footer = 'This is email Footer';
    public $body = '';

    public function loadBody() {
        $this->body .= "This is Main Email body.<br />";
    }

}

class christmasEmail extends eMailBody {

    public function loadBody() {
        parent::loadBody();
        $this->body .= "Added Content for Xmas<br />";
    }

}

$christmasEmail = new christmasEmail();
$christmasEmail->loadBody();
echo $christmasEmail->body;
