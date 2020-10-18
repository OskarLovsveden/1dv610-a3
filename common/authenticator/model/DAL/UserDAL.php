<?php

namespace Model\DAL;

class UserDAL {
    private static $table = "users";
    private static $rowUsername = "username";
    private static $rowPassword = "password";

    private $settings;

    public function __construct(\Settings $settings) {
        $this->settings = $settings;
        $this->createTableIfNotExists();
    }

    public function register(\Model\User $user) {
        $username = $user->getUsername()->getUsername();
        $password = password_hash($user->getPassword()->getPassword(), PASSWORD_BCRYPT);

        $connection = $this->settings->getDBConnection();

        if ($this->exists($username)) {
            throw new \Exception("User exists, pick another username.");
        }

        $sql = "INSERT INTO " . self::$table . " (" . self::$rowUsername . ", " . self::$rowPassword . ") VALUES ( ?, ? ) ";

        $stmt = $connection->prepare($sql);
        if ($stmt === FALSE) {
            // TODO Fix Unhandled Exception
            // throw new \Exception("Error when preparing INSERT :" . $connection->error);
        }

        $bindParam = $stmt->bind_param("ss", $username, $password);
        if ($bindParam === FALSE) {
            // TODO Fix Unhandled Exception
            // throw new \Exception("Error on bind_param :" . $stmt->error);
        }

        $execute = $stmt->execute();
        if ($execute === FALSE) {
            // TODO Fix Unhandled Exception
            // throw new \Exception("Error on execute :" . $stmt->error);
        }

        $stmt->close();
    }

    public function login(\Model\User $user) {
        $connection = $this->settings->getDBConnection();

        $username = $user->getUsername()->getUsername();
        $password = $user->getPassword()->getPassword();

        if ($this->exists($username)) {
            $sql = "SELECT " . self::$rowPassword . " FROM " . self::$table . " WHERE " . self::$rowUsername . " LIKE BINARY '" . $username . "'";

            $stmt = $connection->query($sql);
            $stmt = \mysqli_fetch_row($stmt);

            if (!\password_verify($password, $stmt[0])) {
                throw new \Exception("Wrong name or password");
            }
        } else {
            throw new \Exception("Wrong name or password");
        }

        $connection->close();
    }

    private function exists(string $username): bool {
        $connection = $this->settings->getDBConnection();

        $sql = "SELECT * FROM " . self::$table . " WHERE " . self::$rowUsername . " LIKE BINARY '" . $username . "'";
        $userExists = 0;

        if ($stmt = $connection->prepare($sql)) {
            $stmt->execute();
            $stmt->store_result();
            $userExists = $stmt->num_rows;
            $stmt->close();
        }

        return $userExists == 1;
    }

    private function createTableIfNotExists() {
        $connection = $this->settings->getDBConnection();

        $sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (
    	    " . self::$rowUsername . " VARCHAR(30) NOT NULL UNIQUE,
            " . self::$rowPassword . " VARCHAR(60) NOT NULL
    	  )";

        $result = $connection->query($sql);

        if ($result === FALSE) {
            // TODO Fix Unhandled Exception
            // throw new  \Exception("Error when creating table " . self::$table . " :" . $connection->error);
        }
    }
}
