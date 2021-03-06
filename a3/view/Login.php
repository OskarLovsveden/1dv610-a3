<?php

namespace A3\View;

class Login {

	private static $login = __NAMESPACE__ . __CLASS__ . '::Login';
	private static $logout = __NAMESPACE__ . __CLASS__ . '::Logout';
	private static $name = __NAMESPACE__ . __CLASS__ . '::UserName';
	private static $password = __NAMESPACE__ . __CLASS__ . '::Password';
	private static $keep = __NAMESPACE__ . __CLASS__ . '::KeepMeLoggedIn';
	private static $messageId = __NAMESPACE__ . __CLASS__ . '::Message';
	private static $cookieNameKey = __NAMESPACE__ . __CLASS__ . '::CookieName';
	private static $cookiePasswordKey = __NAMESPACE__ . __CLASS__ . '::CookiePassword';

	private $flashMessage;
	private $usernameInputSession;

	public function __construct(\FlashMessage $flashMessage, \SessionStorage $usernameInputSession) {
		$this->flashMessage = $flashMessage;
		$this->usernameInputSession = $usernameInputSession;
	}

	public function userWantsToLogin(): bool {
		return isset($_POST[self::$login]);
	}

	public function validateLoginForm() {
		$username = $this->getRequestUserName();
		$password = $this->getRequestPassword();

		$this->usernameInputSession->store($username);

		if (!$username) {
			throw new \Exception("Username is missing");
		} else if (!$password) {
			throw new \Exception("Password is missing");
		}
	}

	public function userWantsToLogout(): bool {
		return isset($_POST[self::$logout]);
	}

	public function redirectIndex() {
		header("Location: /a3");
	}

	public function redirectLogin() {
		header("Location: /a3/?login");
	}

	public function setUserCookies($cookieName, $cookiePassword) {
		setcookie(self::$cookieNameKey, $cookieName, time() + (86400 * 30), "/");
		setcookie(self::$cookiePasswordKey, $cookiePassword, time() + (86400 * 30), "/");
	}

	public function unsetUserCookies() {
		setcookie(self::$cookieNameKey, "", time() - 3600, "/");
		setcookie(self::$cookiePasswordKey, "", time() - 3600, "/");
	}

	public function isUserCookieNameSet() {
		return isset($_COOKIE[self::$cookieNameKey]);
	}

	public function getUserCookieName() {
		return $_COOKIE[self::$cookieNameKey];
	}

	public function isUserCookiePasswordSet() {
		return isset($_COOKIE[self::$cookiePasswordKey]);
	}

	public function getUserCookiePassword() {
		return $_COOKIE[self::$cookiePasswordKey];
	}

	public function getRequestUserName() {
		return $_POST[self::$name];
	}

	public function getRequestPassword() {
		return $_POST[self::$password];
	}

	public function getRequestKeepMeLoggedIn() {
		return isset($_POST[self::$keep]);
	}

	public function response() {
		$message = $this->flashMessage->get();

		$usernameInputValue = "";

		if ($this->usernameInputSession->hasValue()) {
			$usernameInputValue = $this->usernameInputSession->getValue();
		}

		return $this->generateLoginFormHTML($message, $usernameInputValue);
	}

	private function generateLoginFormHTML($message, $usernameInputValue) {
		return '
		<form method="post" > 
		<fieldset>
		<legend>Login - enter Username and password</legend>
		<p id="' . self::$messageId . '">' . $message . '</p>
		
		<label for="' . self::$name . '">Username :</label>
		<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $usernameInputValue . '" />
		
		<label for="' . self::$password . '">Password :</label>
		<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
		
		<label for="' . self::$keep . '">Keep me logged in  :</label>
		<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
		
		<input type="submit" name="' . self::$login . '" value="Login" />
		</fieldset>
		</form>
		';
	}

	public function generateLogoutButtonHTML() {
		return '
		<form  method="post" >
		<input type="submit" name="' . self::$logout . '" value="logout"/>
		</form>
		';
	}
}
