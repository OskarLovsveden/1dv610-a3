<?php

namespace A3\Model\DAL;

class HighScoreDAL {
    private static $tableName = "HighScore";
    private static $rowPlayer = "Player";
    private static $rowDifficulty = "Difficulty";
    private static $rowScore = "Score";

    private $settings;

    public function __construct(\GameSettings $settings) {
        $this->settings = $settings;
        $this->createTableIfNotExists();
    }

    public function get(): \A3\Model\HighScore {
        $sql = "SELECT * FROM " . self::$tableName;
        $ret = new \A3\Model\HighScore();

        $db = $this->settings->getDBConnection();
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $highScoreItem = new \A3\Model\HighScoreItem(
                    $row[self::$rowPlayer],
                    $row[self::$rowDifficulty],
                    $row[self::$rowScore]
                );

                $ret->add($highScoreItem);
            }
        }

        return $ret;
    }

    public function save(\A3\Model\HighScoreItem $toBeSaved) {
        $db = $this->settings->getDBConnection();

        $player = $toBeSaved->getPlayer();
        $difficulty = $toBeSaved->getDifficulty();
        $score = $toBeSaved->getScore();

        $sql = "INSERT INTO " . self::$tableName . " ( " . self::$rowPlayer . ", " . self::$rowDifficulty . ", " . self::$rowScore . ") VALUES ( ?, ?, ?) ";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("sii", $player, $difficulty, $score);
        $stmt->execute() === FALSE;
        $stmt->close();
    }

    public function createTableIfNotExists() {

        $connection = $this->settings->getDBConnection();

        $sql = "CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
            " . self::$rowPlayer . " VARCHAR(30) NOT NULL,
            " . self::$rowDifficulty . " INT NOT NULL,
            " . self::$rowScore . " INT NOT NULL
            )";

        $connection->query($sql);
        $connection->close();
    }
}
