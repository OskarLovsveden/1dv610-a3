<?php

namespace Model\DAL;

class CookieDAL {

    private $database;

    // private $cookieUsername;
    // private $cookiePassword;

    private static $table = 'cookies';
    private static $rowUsername = 'cookieUsername';
    private static $rowPassword = 'cookiePassword';
    private static $rowBrowser = 'cookieBrowser';
    private static $userAgent = 'HTTP_USER_AGENT';

    // private static $cookieNameKey = 'LoginView::CookieName';
    // private static $cookiePasswordKey = 'LoginView::CookiePassword';

    public function __construct(Database $database) {
        $this->database = $database;
        $this->createTableIfNotExists();
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

    public function saveUserCookie($cookieName, $cookiePassword) {
        $this->createTableIfNotExists();

        $browser = $_SERVER[self::$userAgent];

        $connection = $this->database->getConnection();

        $sql = "REPLACE INTO " . self::$table . " (" . self::$rowUsername . ", " . self::$rowPassword . ", " . self::$rowBrowser . ") VALUES ('" . $cookieName . "', '" . $cookiePassword . "', '" . $browser . "')";

        $connection->query($sql);
        $connection->close();
    }

    public function getUserCookie($username) {

        $connection = $this->database->getConnection();

        $sql = "SELECT * FROM " . self::$table . " WHERE " . self::$rowUsername . " LIKE BINARY '" . $username . "' LIMIT 1";

        $result = mysqli_query($connection, $sql);

        if ($result === false) {
            throw new \Exception("No such saved Cookie");
        }

        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    // public function setUserCookies($cookieUsername) {
    //     $str = rand();
    //     $cookiePassword = md5($str);

    //     setcookie(self::$cookieNameKey, $cookieUsername, time() + (86400 * 30), "/");
    //     setcookie(self::$cookiePasswordKey, $cookiePassword, time() + (86400 * 30), "/");

    //     $this->cookieUsername = $cookieUsername;
    //     $this->cookiePassword = $cookiePassword;
    // }

    // public function unsetUserCookies() {
    //     setcookie(self::$cookieNameKey, "", time() - 3600);
    //     setcookie(self::$cookiePasswordKey, "", time() - 3600);
    // }

    // public function isUserCookieActive($cookieName, $cookiePassword) {
    //     $userCookie = $this->getUserCookie($cookieName);
    //     $validPassword = $userCookie[self::$rowPassword] === $cookiePassword;

    //     // $userCookie = $this->getUserCookie($_COOKIE[self::$cookieNameKey]);
    //     // $validPassword = $userCookie[self::$rowPassword] === $_COOKIE[self::$cookiePasswordKey];
    //     // $cookieSet = isset($_COOKIE[self::$cookieNameKey]);

    //     // if ($validPassword && $cookieSet) {
    //     if ($validPassword) {
    //         return true;
    //     }

    //     return false;
    // }

    // public function isUserCookieValid($cookieName, $cookiePassword) {
    //     $userCookie = $this->getUserCookie($cookieName);

    //     if ($userCookie) {
    //         $validPassword = $userCookie[self::$rowPassword] === $cookiePassword;
    //         // $userCookie = $this->getUserCookie($_COOKIE[self::$cookieNameKey]);

    //         // $validPassword = $userCookie[self::$rowPassword] === $_COOKIE[self::$cookiePasswordKey];
    //         // $cookieSet = isset($_COOKIE[self::$cookieNameKey]);

    //         // if (!$validPassword && $cookieSet) {
    //         if (!$validPassword) {
    //             // $this->unsetUserCookies();
    //             throw new \Exception("Wrong information in cookies");
    //         }
    //     }
    // }

    // public function userBrowserValid($cookieName) {
    //     $userCookie = $this->getUserCookie($cookieName);
    //     // $userCookie = $this->getUserCookie($_COOKIE[self::$cookieNameKey]);

    //     $validBrowser = $userCookie[self::$rowBrowser] === $_SERVER[self::$userAgent];

    //     if ($userCookie && $validBrowser) {
    //         return true;
    //     }
    //     return false;
    // }

    public function validCookie($cookieName, $cookiePassword): bool {
        $userCookie = $this->getUserCookie($cookieName);

        $validPassword = $userCookie[self::$rowPassword] === $cookiePassword;
        $validBrowser = $userCookie[self::$rowBrowser] === $_SERVER[self::$userAgent];

        if ($userCookie && $validBrowser && $validPassword) {
            return true;
        }

        throw new \Exception("Wrong information in cookies");
    }
}