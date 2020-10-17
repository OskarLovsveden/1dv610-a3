<?php

require_once('../common/session-storage/SessionStorage.php');

require_once('model/DAL/Database.php');
require_once('model/DAL/CookieDAL.php');
require_once('model/DAL/UserDAL.php');

require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/User.php');
require_once('model/RememberMeCookie.php');

require_once('Settings.php');

class Authenticator {
    private static $authenticatorSessionIndex = __CLASS__ . '::authenticatorSessionIndex';

    private $database;
    private $authSession;
    private $cookieDAL;
    private $userDAL;

    private $rememberMeCookie;

    public function __construct() {
        $this->database = new \Model\DAL\Database(new \Settings());
        $this->authSession = new \SessionStorage(self::$authenticatorSessionIndex);
        $this->cookieDAL = new \Model\DAL\CookieDAL($this->database);
        $this->userDAL = new \Model\DAL\UserDAL($this->database);
    }

    public function register(string $username, string $password) {
        $name = new \Model\Username($username);
        $pass = new \Model\Password($password);

        $user = new \Model\User($name, $pass);
        return $this->userDAL->register($user);
    }

    public function isUserLoggedIn(): bool {
        if ($this->authSession->hasValue()) {
            return true;
        }
        return false;
    }

    public function loginWithCookie(string $cookieName, string $cookiePassword) {
        $this->isUserCookieValid($cookieName, $cookiePassword);
        $this->authSession->store($cookieName);
    }

    public function login(string $username, string $password) {
        $un = new \Model\Username($username);
        $pw = new \Model\Password($password);

        $user = new \Model\User($un, $pw);
        $this->userDAL->login($user);

        $unString = $un->getUsername();
        $this->authSession->store($unString);
    }

    public function keepUserLoggedIn(string $username) {
        $this->rememberMeCookie = new \Model\RememberMeCookie($username);
        $this->cookieDAL->saveUserCookie($this->rememberMeCookie);
    }

    public function logout() {
        if ($this->authSession->hasValue()) {
            $this->authSession->removeValue();
        }
    }

    public function getCookiePassword(): string {
        return $this->rememberMeCookie->getPassword();
    }

    // public function getUserCookie(string $username) {
    //     return $this->cookieDAL->getUserCookie($username);
    // }

    public function isUserCookieValid(string $cookieName, string $cookiePassword): bool {
        $rememberMeCookie = new \Model\RememberMeCookie($cookieName, $cookiePassword);
        $cn = $rememberMeCookie->getName();
        $cp = $rememberMeCookie->getPassword();
        $ub = $rememberMeCookie->getBrowser();

        return $this->cookieDAL->validCookie($cn, $cp, $ub);
    }
}
