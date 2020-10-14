<?php

namespace Controller;

class Login {
    private $loginView;
    private $authenticator;

    public function __construct(\View\Login $loginView, \Authenticator $authenticator) {
        $this->loginView = $loginView;
        $this->authenticator = $authenticator;
    }

    public function doLogin() {

        if ($this->loginView->userWantsToLogin()) {
            try {
                $this->loginView->validateLoginForm();
                $credentials = $this->loginView->getLoginCredentials();
                $username = $credentials->getUsername();

                $this->authenticator->loginUser($credentials);

                if ($credentials->getKeepUserLoggedIn()) {
                    $userBrowser = $this->loginView->getUserBrowser();
                    $rememberMeCookie = new \Model\RememberMeCookie($username, $userBrowser);
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

                $userBrowser = $this->loginView->getUserBrowser();
                $this->authenticator->setUserBrowser($userBrowser);

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

    private function checkIfCookieExists() {
        return $this->loginView->isUserCookieNameSet() && $this->loginView->isUserCookiePasswordSet();
    }

    private function tryToLoginWithCookie() {
        $cookieName = $this->loginView->getUserCookieName();
        $cookiePassword = $this->loginView->getUserCookiePassword();

        try {
            $userBrowser = $this->loginView->getUserBrowser();
            $this->authenticator->validCookie($cookieName, $cookiePassword, $userBrowser);
            $this->authenticator->setInputFeedbackMessage("Welcome back with cookie");
        } catch (\Exception $e) {
            $this->authenticator->setInputFeedbackMessage($e->getMessage());
        }
    }
}
