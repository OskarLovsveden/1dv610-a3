<?php

namespace Model;

class Username {
    private $username;

    public function __construct(string $username) {
        $this->username = $username;

        if (strlen($username) <= 0) {
            throw new \Exception("Username can not be 0 or fewer chars");
        }
    }

    public function getUsername(): string {
        return $this->username;
    }
}