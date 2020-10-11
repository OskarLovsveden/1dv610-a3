<?php

namespace Model;

class Credentials {
    private $username;
    private $password;
    private $keepMeLoggedIn;

    public function __construct(Username $username, Password $password, bool $keepMeLoggedIn = false) {
        $this->username = $username;
        $this->password = $password;
        $this->keepMeLoggedIn = $keepMeLoggedIn;
    }

    public function getUsername(): string {
        return $this->username->getUsername();
    }

    public function getPassword(): string {
        return $this->password->getPassword();
    }

    public function getKeepUserLoggedIn(): bool {
        return $this->keepMeLoggedIn;
    }
}