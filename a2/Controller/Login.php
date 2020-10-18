<?php

namespace A2\Controller;

class Login {
    private $loginView;
    private $authenticator;
    private $flashMessage;

    public function __construct(\A2\View\Login $loginView, \Authenticator $authenticator, \FlashMessage $flashMessage) {
        $this->authenticator = $authenticator;
        $this->loginView = $loginView;
        $this->flashMessage = $flashMessage;
    }

    public function doLogin() {
        try {
            $this->attemptCookieLoginIfCookiesAreSet();
            if ($this->loginView->userWantsToLogin()) {
                $this->loginView->validateLoginForm();

                $username = $this->loginView->getRequestUserName();
                $password = $this->loginView->getRequestPassword();
                $keepUserLoggedIn = $this->loginView->getRequestKeepMeLoggedIn();

                if ($keepUserLoggedIn) {
                    $this->keepUserLoggedIn($username, $password);
                } else {
                    $this->authenticator->login($username, $password);
                    $this->flashMessage->set("Welcome");
                }

                $this->loginView->redirectIndex();
            }
        } catch (\Exception $e) {
            $this->flashMessage->set($e->getMessage());
            $this->loginView->unsetUserCookies();
            $this->loginView->redirectIndex();
        }
    }

    public function doLogout() {
        if ($this->loginView->userWantsToLogout()) {
            $this->authenticator->logout();

            if ($this->loginView->isUserCookieNameSet()) {
                $this->loginView->unsetUserCookies();
            }

            $this->flashMessage->set("Bye bye!");
            $this->loginView->redirectIndex();
        }
    }

    private function keepUserLoggedIn(string $username, string $password) {
        $this->authenticator->login($username, $password);
        $this->authenticator->keepUserLoggedIn($username);

        $cookiePassword = $this->authenticator->getCookiePassword();
        $this->loginView->setUserCookies($username, $cookiePassword);

        $this->flashMessage->set("Welcome and you will be remembered");
    }

    private function attemptCookieLoginIfCookiesAreSet() {
        $isCookieNameSet = $this->loginView->isUserCookieNameSet();
        $isCookiePasswordSet = $this->loginView->isUserCookiePasswordSet();

        if ($isCookieNameSet && $isCookiePasswordSet) {
            $cookieUsername = $this->loginView->getUserCookieName();
            $cookiePassword = $this->loginView->getUserCookiePassword();
            $this->authenticator->loginWithCookie($cookieUsername, $cookiePassword);

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->flashMessage->set("Welcome back with cookie");
            }

            $this->loginView->redirectIndex();
        }
    }
}
