<?php

namespace Model;

class RememberMeCookie {
    private $cookieName;
    private $cookiePassword;
    private $cookieBrowser;

    public function __construct($cookieName, $cookieBrowser) {
        $this->cookieName = $cookieName;
        $this->cookiePassword = $this->generatePassword();
        $this->cookieBrowser = $cookieBrowser;
    }

    private function generatePassword() {
        return bin2hex(random_bytes(20));
    }

    public function getCookieName() {
        return $this->cookieName;
    }

    public function getCookiePassword() {
        return $this->cookiePassword;
    }

    public function getUserBrowser() {
        return $this->cookieBrowser;
    }
}