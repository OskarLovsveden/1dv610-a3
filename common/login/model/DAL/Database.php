<?php

namespace Model\DAL;

class Database {
    private $hostname;
    private $username;
    private $password;
    private $database;

    public function __construct() {
        if (isset($_SERVER, $_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost') {
            $this->hostname = "localhost";
            $this->username = "users";
            $this->password = "users";
            $this->database = "users";
        } else {
            $url = getenv('JAWSDB_URL');
            $dbparts = parse_url($url);

            $this->hostname = $dbparts['host'];
            $this->username = $dbparts['user'];
            $this->password = $dbparts['pass'];
            $this->database = ltrim($dbparts['path'], '/');
        }
    }

    public function getHostname() {
        return $this->hostname;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getDatabase() {
        return $this->database;
    }
}