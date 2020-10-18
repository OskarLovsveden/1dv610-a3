<?php

namespace Model\DAL;

class CookieDAL {
    private static $table = 'cookies';
    private static $rowUsername = 'cookieUsername';
    private static $rowPassword = 'cookiePassword';
    private static $rowBrowser = 'cookieBrowser';

    private $settings;

    public function __construct(\Settings $settings) {
        $this->settings = $settings;
        $this->createTableIfNotExists();
    }


    public function saveUserCookie(\Model\RememberMeCookie $rememberMeCookie) {
        $cookieName = $rememberMeCookie->getName();
        $cookiePassword = $rememberMeCookie->getPassword();
        $userBrowser = $rememberMeCookie->getBrowser();

        $connection = $this->settings->getDBConnection();

        $sql = "REPLACE INTO " . self::$table . " (" . self::$rowUsername . ", " . self::$rowPassword . ", " . self::$rowBrowser . ") VALUES ('" . $cookieName . "', '" . $cookiePassword . "', '" . $userBrowser . "')";

        $connection->query($sql);
        $connection->close();
    }

    public function getUserCookie(string $username) {

        $connection = $this->settings->getDBConnection();

        $sql = "SELECT * FROM " . self::$table . " WHERE " . self::$rowUsername . " LIKE BINARY '" . $username . "' LIMIT 1";

        $result = mysqli_query($connection, $sql);

        if ($result === false) {
            throw new \Exception("No such saved Cookie");
        }

        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    public function validCookie(\Model\RememberMeCookie $rememberMeCookie) {
        $cn = $rememberMeCookie->getName();
        $cp = $rememberMeCookie->getPassword();
        $ub = $rememberMeCookie->getBrowser();

        $userCookie = $this->getUserCookie($cn);

        $validPassword = $userCookie[self::$rowPassword] === $cp;
        $validBrowser = $userCookie[self::$rowBrowser] === $ub;

        if (!$userCookie && !$validBrowser && !$validPassword) {
            throw new \Exception("Wrong information in cookies");
        }
    }

    private function createTableIfNotExists() {
        $connection = $this->settings->getDBConnection();

        $sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (
            " . self::$rowUsername . " VARCHAR(30) NOT NULL UNIQUE,
            " . self::$rowPassword . " VARCHAR(60) NOT NULL,
            " . self::$rowBrowser . " LONGTEXT NOT NULL
            )";

        $connection->query($sql);
        $connection->close();
    }
}
