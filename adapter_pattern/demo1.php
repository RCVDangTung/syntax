<?php

interface UserMapperInterface{
    
}

class UserMapper implements UserMapperInterface {
    
}

class UserRepository {

    protected $userMapper;

    public function __construct(UserMapperInterface $userMapper = null) {
        $this->userMapper = ($userMapper) ? : new UserMapper;
        
//        var_dump($this->userMapper);
    }

}


$demo = new UserRepository();