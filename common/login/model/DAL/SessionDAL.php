<?php

namespace Model\DAL;

class SessionDAL {
  private static $sessionInputFeedbackMessage = 'Model\\DAL\\SessionDAL::sessionInputFeedbackMessage';
  private static $sessionInputUserValue = 'Model\\DAL\\SessionDAL::sessionInputUserValue';
  private static $activeUser = 'Model\\DAL\\SessionDAL::activeUser';
  private static $userBrowser = 'Model\\DAL\\SessionDAL::userBrowser';
  private static $userAgent = 'HTTP_USER_AGENT';

  private $sessionInputFeedbackMessageWasSetAndShouldNotBeRemovedDuringThisRequest = false;

  public function setInputUserValue($username) {
    $_SESSION[self::$sessionInputUserValue] = $username;
  }

  public function getInputUserValue() {
    return $_SESSION[self::$sessionInputUserValue];
  }

  public function isInputUserValueSet(): bool {
    return isset($_SESSION[self::$sessionInputUserValue]);
  }

  public function isUserSessionActive(): bool {
    if (isset($_SESSION[self::$activeUser]) && !empty($_SESSION[self::$activeUser])) {
      if ($this->userBrowserValid()) {
        return true;
      }
    }

    return false;
  }

  public function setUserSession(string $username) {
    $_SESSION[self::$activeUser] = $username;
  }

  public function unsetUserSession() {
    if (isset($_SESSION[self::$activeUser])) {
      unset($_SESSION[self::$activeUser]);
    } else {
      throw new \Exception("Requires activeUser session to unset it");
    }
  }

  public function setInputFeedbackMessage(string $message) {
    $_SESSION[self::$sessionInputFeedbackMessage] = $message;
    $this->sessionInputFeedbackMessageWasSetAndShouldNotBeRemovedDuringThisRequest = true;
  }

  public function unsetInputFeedbackMessage() {
    if (isset($_SESSION[self::$sessionInputFeedbackMessage])) {
      unset($_SESSION[self::$sessionInputFeedbackMessage]);
    } else {
      throw new \Exception("Requires InputFeedbackMessage session to unset it");
    }
  }

  public function getInputFeedbackMessage() {
    if ($this->sessionInputFeedbackMessageWasSetAndShouldNotBeRemovedDuringThisRequest) {
      return $_SESSION[self::$sessionInputFeedbackMessage];
    }

    if (isset($_SESSION[self::$sessionInputFeedbackMessage])) {
      $message = $_SESSION[self::$sessionInputFeedbackMessage];
      $this->unsetInputFeedbackMessage();

      return $message;
    }
    return "";
  }

  public function flipFeedbackVisibilityBool() {
    $this->sessionInputFeedbackMessageWasSetAndShouldNotBeRemovedDuringThisRequest = false;
  }

  public function setUserBrowser() {
    $_SESSION[self::$userBrowser] = $_SERVER[self::$userAgent];
  }

  public function userBrowserValid(): bool {
    if ($_SESSION[self::$userBrowser] === $_SERVER[self::$userAgent]) {
      return true;
    }
    return false;
  }
}
