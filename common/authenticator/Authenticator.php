<?php

require_once('../common/session-storage/SessionStorage.php');

require_once('model/DAL/Database.php');
require_once('model/DAL/CookieDAL.php');
require_once('model/DAL/UserDAL.php');

require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/User.php');
require_once('model/RememberMeCookie.php');

class Authenticator {
    private static $authenticatorSessionIndex = __CLASS__ . '::authenticatorSessionIndex';

    private $database;
    private $sessionDAL;
    private $cookieDAL;
    private $userDAL;

    private $rememberMeCookie;

    public function __construct() {
        $this->database = new \Model\DAL\Database();
        $this->authSession = new \SessionStorage(self::$authenticatorSessionIndex);
        $this->cookieDAL = new \Model\DAL\CookieDAL($this->database);
        $this->userDAL = new \Model\DAL\UserDAL($this->database);
    }

    public function setSessionIndexValue(string $index, string $data) {
        $this->sessionDAL->setSessionIndexValue($index, $data);
    }

    public function getSessionIndexValue(string $index): string {
        return $this->sessionDAL->getSessionIndexValue($index);
    }

    public function unsetSessionIndexValue(string $index) {
        $this->sessionDAL->unsetSessionIndexValue($index);
    }

    public function isSessionIndexValueSet(string $index): bool {
        return $this->sessionDAL->isSessionIndexValueSet($index);
    }

    public function validateAgainstSessionIndexValue(string $index, string $data): bool {
        return $this->sessionDAL->validateAgainstSessionIndexValue($index, $data);
    }

    public function keepUserLoggedIn(string $username) {
        $this->rememberMeCookie = new \Model\RememberMeCookie($username);
        $this->cookieDAL->saveUserCookie($this->rememberMeCookie);
    }

    public function getCookiePassword() {
        return $this->rememberMeCookie->getPassword();
    }

    public function getUserCookie(string $username) {
        return $this->cookieDAL->getUserCookie($username);
    }

    public function validCookie(string $cookieName, string $cookiePassword, string $userBrowser): bool {
        return $this->cookieDAL->validCookie($cookieName, $cookiePassword, $userBrowser);
    }

    public function registerUser(string $username, string $password) {
        $name = new \Model\Username($username);
        $pass = new \Model\Password($password);

        $user = new \Model\User($name, $pass);
        return $this->userDAL->register($user);
    }

    public function loginUser(string $username, string $password) {
        $name = new \Model\Username($username);
        $pass = new \Model\Password($password);

        $user = new \Model\User($name, $pass);
        return $this->userDAL->login($user);
    }
}
