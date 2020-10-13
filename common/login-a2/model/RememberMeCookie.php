<?php

namespace Model;

class RememberMeCookie {
    private $cookieName;
    private $cookiePassword;

    public function __construct($cookieName) {
        $this->cookieName = $cookieName;

        $str = rand();
        var_dump($str);
        $cookiePassword = md5($str);
        $this->cookiePassWord = $cookiePassword;
        var_dump($this->cookiePassword);
    }

    public function getCookieName() {
        return $this->cookieName;
    }

    public function getCookiePassword() {
        return $this->cookiePassword;
    }
}