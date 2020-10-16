<?php

namespace View;

class Register {
	private static $register = 'RegisterView::Register';
	private static $username = 'RegisterView::UserName';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
	private static $messageId = 'RegisterView::Message';

	private static $registerURL = "?register";
	private static $usernameInputIndex = __CLASS__ . '::usernameInputIndex';

	private $registerFormErrors = array();

	private $flashMessage;
	private $usernameInputSession;

	public function __construct(\FlashMessage $flashMessage) {
		$this->flashMessage = $flashMessage;
		$this->usernameInputSession = new \SessionStorage(self::$usernameInputIndex);
	}

	public function userWantsToRegister() {
		return isset($_POST[self::$register]);
	}

	public function registerFormValidAndSetMessage() {
		$username = $this->getRequestUserName();
		$password = $this->getRequestPassword();
		$passwordRepeat = $this->getRequestPasswordRepeat();

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
			$this->usernameInputSession->store(strip_tags($username));
			$brSeparatedErrors = implode("<br>", $this->registerFormErrors);
			throw new \Exception($brSeparatedErrors);
		}
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = $this->flashMessage->get();

		$usernameInputValue = "";

		if ($this->usernameInputSession->hasValue()) {
			$usernameInputValue = $this->usernameInputSession->getValue();
		}

		$response = $this->generateRegisterFormHTML($message, $usernameInputValue);
		return $response;
	}

	public function redirectIndex() {
		// header("Location: /a2");
	}

	public function redirectRegister() {
		// header("Location: /a2/" . self::$registerURL . "");
	}

	public function getRequestUserName() {
		return $_POST[self::$username];
	}
	public function getRequestPassword() {
		return $_POST[self::$password];
	}

	/**
	 * Generate HTML code on the output buffer for the logout button
	 * @param $message, String output message
	 * @return  void, BUT writes to standard output!
	 */
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

	private function getRequestPasswordRepeat() {
		return $_POST[self::$passwordRepeat];
	}
}
