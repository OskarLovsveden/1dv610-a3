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
                $username = $this->loginView->getRequestUserName();
                $password = $this->loginView->getRequestPassword();
                $keepUserLoggedIn = $this->loginView->getRequestKeepMeLoggedIn();

                $this->authenticator->loginUser($username, $password, $keepUserLoggedIn);

                if ($keepUserLoggedIn) {
                    $userBrowser = $this->loginView->getUserBrowser();

                    $cookiePassword = $this->authenticator->saveUserCookieAndReturnPassword($username, $userBrowser);

                    $this->loginView->setUserCookies($username, $cookiePassword);

                    $this->authenticator->setInputFeedbackMessage("Welcome and you will be remembered");
                } else {
                    $this->authenticator->setInputFeedbackMessage("Welcome");
                }

                $this->authenticator->setUserSession($username);

                $userBrowser = $this->loginView->getUserBrowser();
                $this->authenticator->setUserBrowser($userBrowser);

                $this->loginView->redirectIndex();
            } catch (\Exception $e) {
                $this->authenticator->setInputFeedbackMessage($e->getMessage());
                $this->loginView->redirectIndex();
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
            $this->loginView->redirectIndex();
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
            $this->loginView->redirectIndex();
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
