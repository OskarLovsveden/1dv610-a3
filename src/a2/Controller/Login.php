<?php

namespace Controller;

require_once('model/DAL/UserDAL.php');
require_once('model/RememberMeCookie.php');

class Login {
    private $loginView;
    private $authenticator;
    private $userBrowser;

    public function __construct(\View\Login $loginView, \Authenticator $authenticator) {
        $this->loginView = $loginView;
        $this->authenticator = $authenticator;
        $this->userBrowser = $_SERVER['HTTP_USER_AGENT'];
    }

    public function isUserLoggedIn(): bool {
        if ($this->authenticator->isUserSessionActive()) {
            return true;
        }
        if ($this->checkIfCookieExists()) {
            $this->tryToLoginWithCookie();

            $cookieUsername = $this->loginView->getUserCookieName();
            $this->authenticator->setUserSession($cookieUsername);
            $this->loginView->reloadPage();
        }
        return false;
    }

    public function checkIfCookieExists() {
        return $this->loginView->isUserCookieNameSet() && $this->loginView->isUserCookiePasswordSet();
    }

    public function tryToLoginWithCookie() {
        $cookieName = $this->loginView->getUserCookieName();
        $cookiePassword = $this->loginView->getUserCookiePassword();

        try {
            $this->authenticator->validCookie($cookieName, $cookiePassword, $this->userBrowser);
            $this->authenticator->setInputFeedbackMessage("Welcome back with cookie");
        } catch (\Exception $e) {
            $this->authenticator->setInputFeedbackMessage($e->getMessage());
        }
    }

    public function doLogin() {

        if ($this->loginView->userWantsToLogin()) {
            try {
                $this->loginView->validateLoginForm();
                $credentials = $this->loginView->getLoginCredentials();
                $username = $credentials->getUsername();

                if ($credentials->getKeepUserLoggedIn()) {
                    $rememberMeCookie = new \Model\RememberMeCookie($username, $this->userBrowser);
                    $cookieName = $rememberMeCookie->getCookieName();
                    $cookiePassword = $rememberMeCookie->getCookiePassword();
                    $cookieBrowser = $rememberMeCookie->getUserBrowser();

                    $this->loginView->setUserCookies($cookieName, $cookiePassword);
                    $this->authenticator->saveUserCookie($cookieName, $cookiePassword, $cookieBrowser);

                    $this->authenticator->setInputFeedbackMessage("Welcome and you will be remembered");
                } else {
                    $this->authenticator->setInputFeedbackMessage("Welcome");
                }

                $this->authenticator->setUserSession($username);
                $this->authenticator->setUserBrowser($this->userBrowser);

                $this->authenticator->loginUser($credentials);

                $this->loginView->reloadPage();
            } catch (\Exception $e) {
                $this->authenticator->setInputFeedbackMessage($e->getMessage());
                $this->loginView->reloadPage();
            }
        }
    }

    public function doLogout() {
        if ($this->loginView->userWantsToLogout()) {
            if ($this->authenticator->isUserSessionActive()) {
                $this->authenticator->unsetUserSession();
            }

            if ($this->loginView->isUserCookieNameSet()) {
                $this->loginView->unsetUserCookies();
            }

            $this->authenticator->setInputFeedbackMessage("Bye bye!");
            $this->loginView->reloadPage();
        }
    }
}