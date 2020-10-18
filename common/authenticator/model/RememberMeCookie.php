<?php

namespace Model;

class RememberMeCookie {
    private $cookieName;
    private $cookiePassword;
    private $cookieBrowser;

    public function __construct(string $cookieBrowser, string $cookieName, string $cookiePassword = null) {
        $this->cookieName = $cookieName;

        if ($cookiePassword == null) {
            $this->cookiePassword = $this->generatePassword();
        } else {
            $this->cookiePassword = $cookiePassword;
        }

        $this->cookieBrowser = $cookieBrowser;
    }

    private function generatePassword() {
        return bin2hex(random_bytes(20));
    }

    public function getName(): string {
        return $this->cookieName;
    }

    public function getPassword(): string {
        return $this->cookiePassword;
    }

    public function getBrowser(): string {
        return $this->cookieBrowser;
    }
}
