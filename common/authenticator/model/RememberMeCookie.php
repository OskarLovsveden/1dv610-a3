<?php

namespace Model;

class RememberMeCookie {
    private $cookieName;
    private $cookiePassword;
    private $cookieBrowser;

    private static $httpAgent = 'HTTP_USER_AGENT';

    public function __construct(string $cookieName, string $cookiePassword = null) {
        $this->cookieName = $cookieName;

        if ($cookiePassword == null) {
            $this->cookiePassword = $this->generatePassword();
        } else {
            $this->cookiePassword = $cookiePassword;
        }

        $this->cookieBrowser = $_SERVER[self::$httpAgent];
    }

    private function generatePassword() {
        return bin2hex(random_bytes(20));
    }

    public function getName() {
        return $this->cookieName;
    }

    public function getPassword() {
        return $this->cookiePassword;
    }

    public function getBrowser() {
        return $this->cookieBrowser;
    }
}
