<?php

namespace View;

require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/Credentials.php');

class Login {

	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	private $cookieDAL;
	private $sessionDAL;

	public function __construct(\Model\DAL\CookieDAL $cookieDAL, \Model\DAL\SessionDAL $sessionDAL) {
		$this->cookieDAL = $cookieDAL;
		$this->sessionDAL = $sessionDAL;
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response(bool $isLoggedIn) {
		$message = $this->sessionDAL->getInputFeedbackMessage();

		$sessionExists = $this->sessionDAL->isUserSessionActive();
		$cookieExists = $this->cookieDAL->isUserCookieActive();

		if (!$sessionExists && $cookieExists) {
			$message = "Welcome back with cookie";
		}

		$response = "";

		if ($isLoggedIn) {
			$response .= $this->generateLogoutButtonHTML($message);
		} else {
			$usernameInputValue = "";

			if ($this->sessionDAL->isInputUserValueSet()) {
				$usernameInputValue = $this->sessionDAL->getInputUserValue();
			}

			$response .= $this->generateLoginFormHTML($message, $usernameInputValue);
		}
		return $response;
	}

	public function userWantsToLogin(): bool {
		return isset($_POST[self::$login]);
	}

	public function validateLoginForm() {
		$this->sessionDAL->setInputUserValue($this->getRequestUserName());

		if (!$this->getRequestUserName()) {
			throw new \Exception("Username is missing");
		} else if (!$this->getRequestPassword()) {
			throw new \Exception("Password is missing");
		}
	}

	public function getLoginCredentials(): \Model\Credentials {
		$username = new \Model\Username($this->getRequestUserName());
		$password = new \Model\Password($this->getRequestPassword());
		$keepMeLoggedIn = $this->getRequestKeepMeLoggedIn();

		$credentials = new \Model\Credentials($username, $password, $keepMeLoggedIn);
		return $credentials;
	}

	public function userWantsToLogout(): bool {
		return isset($_POST[self::$logout]);
	}

	public function reloadPage() {
		header("Location: /");
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

	private function getRequestUserName() {
		return $_POST[self::$name];
	}

	private function getRequestPassword() {
		return $_POST[self::$password];
	}

	private function getRequestKeepMeLoggedIn() {
		return isset($_POST[self::$keep]);
	}
}
