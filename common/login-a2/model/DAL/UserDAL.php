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

    public function createTableIfNotExists() {
        $connection = new \mysqli($this->database->getHostname(), $this->database->getUsername(), $this->database->getPassword(), $this->database->getDatabase());

        $sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (
            " . self::$rowUsername . " VARCHAR(30) NOT NULL UNIQUE,
            " . self::$rowPassword . " VARCHAR(60) NOT NULL
            )";

        $connection->query($sql);
    }

    public function registerUser(\Model\User $user) {
        $this->createTableIfNotExists();

        $username = $user->getUsername();
        $password = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $connection = new \mysqli(
            $this->database->getHostname(),
            $this->database->getUsername(),
            $this->database->getPassword(),
            $this->database->getDatabase()
        );

        if ($this->userExists($username)) {
            throw new \Exception("User exists, pick another username.");
        }

        $sql = "INSERT INTO " . self::$table . " (" . self::$rowUsername . ", " . self::$rowPassword . ") VALUES ('" . $username . "', '" . $password . "')";

        $connection->query($sql);
    }

    public function loginUser(\Model\Credentials $credentials) {
        $username = $credentials->getUsername();
        $password = $credentials->getPassword();

        $connection = new \mysqli(
            $this->database->getHostname(),
            $this->database->getUsername(),
            $this->database->getPassword(),
            $this->database->getDatabase()
        );

        if ($this->userExists($username)) {
            $sql = "SELECT " . self::$rowPassword . " FROM " . self::$table . " WHERE " . self::$rowUsername . " LIKE BINARY '" . $username . "'";

            $stmt = $connection->query($sql);
            $stmt = \mysqli_fetch_row($stmt);

            if (!\password_verify($password, $stmt[0])) {
                throw new \Exception("Wrong name or password");
            }
        } else {
            throw new \Exception("Wrong name or password");
        }
    }

    private function userExists(string $username): bool {
        $connection = new \mysqli(
            $this->database->getHostname(),
            $this->database->getUsername(),
            $this->database->getPassword(),
            $this->database->getDatabase()
        );

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
}
