<?php

namespace Model;

class User {

    private $username;
    private $password;

    public function __construct(\Model\Username $username, \Model\Password $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): \Model\Username {
        return $this->username;
    }

    public function getPassword(): \Model\Password {
        return $this->password;
    }
}
