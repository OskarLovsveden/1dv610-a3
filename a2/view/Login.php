<?php

namespace View;

class Login {

	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $cookieNameKey = 'LoginView::CookieName';
	private static $cookiePasswordKey = 'LoginView::CookiePassword';

	private static $usernameInputIndex = __CLASS__ . '::usernameInputIndex';
	// private static $activeUser = __CLASS__ . '::activeUser';
	// private static $userBrowser = __CLASS__ . '::userBrowser';

	private $flashMessage;
	private $usernameInputSession;

	public function __construct(\FlashMessage $flashMessage) {
		$this->flashMessage = $flashMessage;
		$this->usernameInputSession = new \SessionStorage(self::$usernameInputIndex);
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
		header("Location: /a2");
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

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response(bool $isLoggedIn) {
		$message = $this->flashMessage->get();

		$response = "";

		if ($isLoggedIn) {
			$response .= $this->generateLogoutButtonHTML($message);
		} else {
			$usernameInputValue = "";

			if ($this->usernameInputSession->hasValue()) {
				$usernameInputValue = $this->usernameInputSession->getValue();
			}

			$response .= $this->generateLoginFormHTML($message, $usernameInputValue);
		}
		return $response;
	}

	/**
	 * Generate HTML code on the output buffer for the logout button
	 * @param $message, String output message
	 * @return  void, BUT writes to standard output!
	 */
	private function generateLogoutButtonHTML($message) {
		return '
		<form  method="post" >
		<p id="' . self::$messageId . '">' . $message . '</p>
		<input type="submit" name="' . self::$logout . '" value="logout"/>
		</form>
		';
	}

	/**
	 * Generate HTML code on the output buffer for the logout button
	 * @param $message, String output message
	 * @return  void, BUT writes to standard output!
	 */
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
}
