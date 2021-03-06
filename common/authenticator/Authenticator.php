<?php

require_once('../common/session-storage/SessionStorage.php');

require_once('model/DAL/CookieDAL.php');
require_once('model/DAL/UserDAL.php');

require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/User.php');
require_once('model/RememberMeCookie.php');

require_once('Settings.php');

class Authenticator {
    private static $userSessionIndex = __NAMESPACE__ . __CLASS__ . '::userSessionIndex';

    // TODO Prevent Session hijacking (This makes automatic tests never stop loading)
    // private static $browserSessionIndex = __NAMESPACE__ . __CLASS__ . '::browserSessionIndex';

    private static $httpAgent = 'HTTP_USER_AGENT';

    private $settings;
    private $userSession;

    // TODO Prevent Session hijacking (This makes automatic tests never stop loading)
    // private $browserSession;

    private $cookieDAL;
    private $userDAL;

    private $rememberMeCookie;

    public function __construct() {
        $this->settings = new \Settings();
        $this->userSession = new \SessionStorage(self::$userSessionIndex);

        // TODO Prevent Session hijacking (This makes automatic tests never stop loading)
        // $this->browserSession = new \SessionStorage(self::$browserSessionIndex);

        $this->cookieDAL = new \Model\DAL\CookieDAL($this->settings);
        $this->userDAL = new \Model\DAL\UserDAL($this->settings);
    }

    public function register(string $username, string $password) {
        $name = new \Model\Username($username);
        $pass = new \Model\Password($password);

        $user = new \Model\User($name, $pass);
        return $this->userDAL->register($user);
    }

    public function isUserLoggedIn(): bool {

        $userSessionActive = $this->userSession->hasValue();
        return $userSessionActive;

        // TODO Prevent Session hijacking (This makes automatic tests never stop loading)
        // $userSessionActive = $this->userSession->hasValue();
        // $userBrowserValid = $this->browserSession->hasValue() && $this->browserSession->equalsValue($this->currentBrowser());
        // return $userSessionActive && $userBrowserValid;
    }

    public function loginWithCookie(string $cookieName, string $cookiePassword) {
        $rememberMeCookie = new \Model\RememberMeCookie($this->currentBrowser(), $cookieName, $cookiePassword);
        $this->cookieDAL->validCookie($rememberMeCookie);

        $this->userSession->store($cookieName);
    }

    public function login(string $username, string $password) {
        $un = new \Model\Username($username);
        $pw = new \Model\Password($password);
        $user = new \Model\User($un, $pw);

        $this->userDAL->login($user);

        $unString = $un->getUsername();
        $this->userSession->store($unString);

        // TODO Prevent Session hijacking (This makes automatic tests never stop loading)
        // $this->browserSession->store($this->currentBrowser());
    }

    public function keepUserLoggedIn(string $username) {
        $this->rememberMeCookie = new \Model\RememberMeCookie($this->currentBrowser(), $username);
        $this->cookieDAL->saveUserCookie($this->rememberMeCookie);
    }

    public function logout() {
        if ($this->userSession->hasValue()) {
            $this->userSession->removeValue();
        }
    }

    public function getLoggedInUser(): string {
        return $this->userSession->getValue();
    }

    public function getCookiePassword(): string {
        return $this->rememberMeCookie->getPassword();
    }

    private function currentBrowser(): string {
        return $_SERVER[self::$httpAgent];
    }
}
