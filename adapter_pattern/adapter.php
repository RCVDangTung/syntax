<?php

/*
 * Change method in class method  
 * 
 *  */

class Facebook {

    public function postTowall_One() {
        echo 'Posting message ...';
    }

}

interface SocialMediaAdapter {

    public function post($msg);
}

class FacebookAdapter implements SocialMediaAdapter {

    private $facebook;

    public function __construct(Facebook $facebook) {
        $this->facebook = $facebook;
    }

    public function post($msg) {
        $this->facebook->postTowall_One();
    }

}

function getMessageFormUser() {
    return 'aaaaa';
}

$msg = getMessageFormUser();
$facebook = new FacebookAdapter(new Facebook());
$facebook->post($msg);
