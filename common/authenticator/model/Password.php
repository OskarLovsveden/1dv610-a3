<?php

namespace Model;

class Password {
    private $password;

    public function __construct(string $password) {
        $this->password = $password;

        if (strlen($password) <= 0) {
            throw new \Exception("Password can not be 0 or fewer chars");
        }
    }

    public function getPassword(): string {
        return $this->password;
    }
}