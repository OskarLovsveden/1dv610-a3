<?php

session_start();

class SessionStorage {
  private $index;

  public function __construct(string $index) {
    $this->index = $index;
  }

  public function store(string $data) {
    $_SESSION[$this->index] = $data;
  }

  public function getValue(): string {
    if (isset($_SESSION[$this->index])) {
      return $_SESSION[$this->index];
    } else {
      throw new \Exception("No session with index [" . $this->index . "] was found");
    }
  }

  public function removeValue() {
    if (isset($_SESSION[$this->index])) {
      unset($_SESSION[$this->index]);
    } else {
      throw new \Exception("[" . $this->index . "] is not an active session.");
    }
  }

  public function hasValue(): bool {
    if (isset($_SESSION[$this->index]) && !empty($_SESSION[$this->index])) {
      return true;
    }
    return false;
  }

  public function equalsValue(string $data): bool {
    if ($_SESSION[$this->index] === $data) {
      return true;
    }
    return false;
  }
}
