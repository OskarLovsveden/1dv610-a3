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
                $user = $this->registerView->getUserToRegister();
                $this->authenticator->setInputUserValue($user->getUsername());
                $this->authenticator->registerUser($user);
                $this->authenticator->setInputFeedbackMessage("Registered new user.");
                header("Location: /a2");
            } catch (\Exception $e) {
                $this->authenticator->setInputFeedbackMessage($e->getMessage());
                $registerURL = $this->registerView->getRegisterURL();
                header("Location: /" . $registerURL . "");
            }
        }
    }
}
