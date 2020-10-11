<?php

class Login {

    public function isLoggedIn(): bool {
        try {
            $sessionExists = $sessionDAL->isUserSessionActive();
            $cookieExists = $cookieDAL->isUserCookieActive();
            $validBrowser = $cookieDAL->userBrowserValid();
            return $sessionExists || $cookieExists && $validBrowser;
        } catch (\Throwable $th) {
            // Handle error 
        }
    }
}
