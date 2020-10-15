<?php

namespace Controller;

class Register {
    private $registerView;
    private $authenticator;

    public function __construct(\View\Register $registerView, \Authenticator $authenticator) {
        $this->registerView = $registerView;
        $this->authenticator = $authenticator;
    }

    public function doRegister() {
        if ($this->registerView->userWantsToRegister()) {
            try {
                $this->registerView->registerFormValidAndSetMessage();

                $username = $this->registerView->getRequestUserName();
                $password = $this->registerView->getRequestPassword();

                $this->authenticator->setInputUserValue($username);
                $this->authenticator->registerUser($username, $password);
                $this->authenticator->setInputFeedbackMessage("Registered new user.");
                $this->registerView->redirectIndex();
            } catch (\Exception $e) {
                $this->authenticator->setInputFeedbackMessage($e->getMessage());
                $this->registerView->redirectRegister();
            }
        }
    }
}
