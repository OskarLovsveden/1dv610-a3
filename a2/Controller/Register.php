<?php

namespace Controller;

class Register {
    private static $usernameInputIndex = __CLASS__ . '::usernameInputIndex';

    private $registerView;
    private $authenticator;
    private $flashMessage;
    private $usernameInputSession;

    public function __construct(\View\Register $registerView, \Authenticator $authenticator, \FlashMessage $flashMessage) {
        $this->registerView = $registerView;
        $this->authenticator = $authenticator;
        $this->flashMessage = $flashMessage;
        $this->usernameInputSession = new \SessionStorage(self::$usernameInputIndex);
    }

    public function doRegister() {
        if ($this->registerView->userWantsToRegister()) {
            try {
                $this->registerView->registerFormValidAndSetMessage();

                $username = $this->registerView->getRequestUserName();
                $password = $this->registerView->getRequestPassword();

                $this->usernameInputSession->store($username);
                $this->authenticator->registerUser($username, $password);
                $this->flashMessage->set("Registered new user.");
                $this->registerView->redirectIndex();
            } catch (\Exception $e) {
                $this->flashMessage->set($e->getMessage());
                $this->registerView->redirectRegister();
            }
        }
    }
}
