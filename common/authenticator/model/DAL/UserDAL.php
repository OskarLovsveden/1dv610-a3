<?php

namespace Model\DAL;

class UserDAL {
    private $database;

    private static $table = "users";
    private static $rowUsername = "username";
    private static $rowPassword = "password";

    public function __construct(Database $database) {
        $this->database = $database;
        $this->createTableIfNotExists();
    }

    public function register(\Model\User $user) {
        $username = $user->getUsername()->getUsername();
        $password = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $connection = $this->database->getConnection();

        if ($this->exists($username)) {
            throw new \Exception("User exists, pick another username.");
        }

        $sql = "INSERT INTO " . self::$table . " (" . self::$rowUsername . ", " . self::$rowPassword . ") VALUES ('" . $username . "', '" . $password . "')";

        $connection->query($sql);
        $connection->close();
    }

    public function login(\Model\User $user) {
        $username = $user->getUsername()->getUsername();
        $password = $user->getPassword()->getPassword();


        $connection = $this->database->getConnection();

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
        $connection = $this->database->getConnection();

        $query = "SELECT * FROM " . self::$table . " WHERE " . self::$rowUsername . " LIKE BINARY '" . $username . "'";
        $userExists = 0;

        if ($stmt = $connection->prepare($query)) {
            $stmt->execute();
            $stmt->store_result();
            $userExists = $stmt->num_rows;
            $stmt->close();
        }

        return $userExists == 1;
    }

    private function createTableIfNotExists() {
        $connection = $this->database->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (
            " . self::$rowUsername . " VARCHAR(30) NOT NULL UNIQUE,
            " . self::$rowPassword . " VARCHAR(60) NOT NULL
            )";

        $connection->query($sql);
        $connection->close();
    }
}
