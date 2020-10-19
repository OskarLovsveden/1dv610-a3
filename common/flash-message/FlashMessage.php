<?php

require_once('../common/session-storage/SessionStorage.php');

class FlashMessage {
    private static $flashMessageSessionIndex = __NAMESPACE__ . __CLASS__ . '::SessionIndex';
    private $flashMessageShouldPersistOneReload = false;

    private $sessionStorage;

    public function __construct() {
        $this->sessionStorage = new \SessionStorage(self::$flashMessageSessionIndex);
    }

    public function set(string $message) {
        $this->sessionStorage->store($message);
        $this->flashMessageShouldPersistOneReload = true;
    }

    public function get(): string {
        if ($this->flashMessageShouldPersistOneReload) {
            return $this->sessionStorage->getValue();
        }

        if ($this->sessionStorage->hasValue()) {
            $message = $this->sessionStorage->getValue();
            $this->remove();

            return $message;
        }
        return "";
    }

    private function remove() {
        if ($this->sessionStorage->hasValue()) {
            $this->sessionStorage->removeValue();
        }
    }
}
