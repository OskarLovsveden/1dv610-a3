<?php

namespace A3\Controller;

class Register {
    private $registerView;
    private $authenticator;
    private $flashMessage;

    public function __construct(\A3\View\Register $registerView, \Authenticator $authenticator, \FlashMessage $flashMessage) {
        $this->registerView = $registerView;
        $this->authenticator = $authenticator;
        $this->flashMessage = $flashMessage;
    }

    public function doRegister() {
        if ($this->registerView->userWantsToRegister()) {
            try {
                $this->registerView->registerFormValidAndSetMessage();

                $username = $this->registerView->getRequestUserName();
                $password = $this->registerView->getRequestPassword();

                $this->authenticator->register($username, $password);
                $this->flashMessage->set("Registered new user.");
                $this->registerView->redirectLogin();
            } catch (\Exception $e) {
                $this->flashMessage->set($e->getMessage());
                $this->registerView->redirectRegister();
            }
        }
    }
}
