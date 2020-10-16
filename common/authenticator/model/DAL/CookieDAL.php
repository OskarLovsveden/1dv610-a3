<?php

namespace Model\DAL;

class CookieDAL {

    private $database;

    private static $table = 'cookies';
    private static $rowUsername = 'cookieUsername';
    private static $rowPassword = 'cookiePassword';
    private static $rowBrowser = 'cookieBrowser';

    public function __construct(Database $database) {
        $this->database = $database;
        $this->createTableIfNotExists();
    }


    public function saveUserCookie(\Model\RememberMeCookie $rememberMeCookie) {
        $cookieName = $rememberMeCookie->getName();
        $cookiePassword = $rememberMeCookie->getPassword();
        $userBrowser = $rememberMeCookie->getBrowser();

        $this->createTableIfNotExists();

        $connection = $this->database->getConnection();

        $sql = "REPLACE INTO " . self::$table . " (" . self::$rowUsername . ", " . self::$rowPassword . ", " . self::$rowBrowser . ") VALUES ('" . $cookieName . "', '" . $cookiePassword . "', '" . $userBrowser . "')";

        $connection->query($sql);
        $connection->close();
    }

    public function getUserCookie(string $username) {

        $connection = $this->database->getConnection();

        $sql = "SELECT * FROM " . self::$table . " WHERE " . self::$rowUsername . " LIKE BINARY '" . $username . "' LIMIT 1";

        $result = mysqli_query($connection, $sql);

        if ($result === false) {
            throw new \Exception("No such saved Cookie");
        }

        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    public function validCookie(string $cookieName, string $cookiePassword, string $userBrowser): bool {
        $userCookie = $this->getUserCookie($cookieName);

        $validPassword = $userCookie[self::$rowPassword] === $cookiePassword;
        $validBrowser = $userCookie[self::$rowBrowser] === $userBrowser;

        if ($userCookie && $validBrowser && $validPassword) {
            return true;
        }

        throw new \Exception("Wrong information in cookies");
    }

    private function createTableIfNotExists() {
        $connection = $this->database->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (
            " . self::$rowUsername . " VARCHAR(30) NOT NULL UNIQUE,
            " . self::$rowPassword . " VARCHAR(60) NOT NULL,
            " . self::$rowBrowser . " LONGTEXT NOT NULL
            )";

        $connection->query($sql);
        $connection->close();
    }
}
