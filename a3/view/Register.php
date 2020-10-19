<?php

namespace A3\View;

class Register {
	private static $register = __NAMESPACE__ . __CLASS__ . '::Register';
	private static $username = __NAMESPACE__ . __CLASS__ . '::UserName';
	private static $password = __NAMESPACE__ . __CLASS__ . '::Password';
	private static $passwordRepeat = __NAMESPACE__ . __CLASS__ . '::PasswordRepeat';
	private static $messageId = __NAMESPACE__ . __CLASS__ . '::Message';

	private static $registerURL = "?register";

	private $registerFormErrors = array();

	private $flashMessage;
	private $usernameInputSession;

	public function __construct(\FlashMessage $flashMessage, \SessionStorage $usernameInputSession) {
		$this->flashMessage = $flashMessage;
		$this->usernameInputSession = $usernameInputSession;
	}

	public function userWantsToRegister() {
		return isset($_POST[self::$register]);
	}

	public function registerFormValidAndSetMessage() {
		$username = $this->getRequestUserName();
		$password = $this->getRequestPassword();
		$passwordRepeat = $this->getRequestPasswordRepeat();

		$this->usernameInputSession->store(strip_tags($username));

		if (strlen($username) < 3) {
			array_push($this->registerFormErrors, "Username has too few characters, at least 3 characters.");
		}

		if (strlen($password) < 6) {
			array_push($this->registerFormErrors, "Password has too few characters, at least 6 characters.");
		}

		if ($password != $passwordRepeat) {
			array_push($this->registerFormErrors, "Passwords do not match.");
		}

		if ($username != strip_tags($username)) {
			array_push($this->registerFormErrors, "Username contains invalid characters.");
		}

		if (!empty($this->registerFormErrors)) {
			$brSeparatedErrors = implode("<br>", $this->registerFormErrors);
			throw new \Exception($brSeparatedErrors);
		}
	}

	public function response() {
		$message = $this->flashMessage->get();

		$usernameInputValue = "";

		if ($this->usernameInputSession->hasValue()) {
			$usernameInputValue = $this->usernameInputSession->getValue();
		}

		$response = $this->generateRegisterFormHTML($message, $usernameInputValue);
		return $response;
	}

	public function redirectLogin() {
		header("Location: /a3/?login");
	}

	public function redirectRegister() {
		header("Location: /a3/" . self::$registerURL . "");
	}

	public function getRequestUserName(): string {
		return $_POST[self::$username];
	}
	public function getRequestPassword(): string {
		return $_POST[self::$password];
	}

	private function generateRegisterFormHTML($message, $usernameInputValue) {
		return '
        <h2>Register new user</h2>
        <form action="' . self::$registerURL . '" method="post" enctype="multipart/form-data">
            <fieldset>
            <legend>Register a new user - Write username and password</legend>
                <p id="' . self::$messageId . '">' . $message . '</p>
                <label for="' . self::$username . '" >Username :</label>
                <input type="text" size="20" name="' . self::$username . '" id="' . self::$username . '" value="' . $usernameInputValue . '" />
                <br/>
                <label for="' . self::$password . '" >Password  :</label>
                <input type="password" size="20" name="' . self::$password . '" id="' . self::$password . '" value="" />
                <br/>
                <label for="' . self::$passwordRepeat . '" >Repeat password  :</label>
                <input type="password" size="20" name="' . self::$passwordRepeat . '" id="' . self::$passwordRepeat . '" value="" />
                <br/>
                <input id="submit" type="submit" name="' . self::$register . '"  value="Register" />
                <br/>
            </fieldset>
        </form>';
	}

	private function getRequestPasswordRepeat(): string {
		return $_POST[self::$passwordRepeat];
	}
}
