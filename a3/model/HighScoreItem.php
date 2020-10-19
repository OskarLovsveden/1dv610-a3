<?php

namespace A3\Model;

class HighScoreItem {
    private $player;
    private $difficulty;
    private $score;

    public function __construct(string $player, int $difficulty, int $score) {
        $this->player = $player;
        $this->difficulty = $difficulty;
        $this->score = $score;
    }

    public function getPlayer(): string {
        return $this->player;
    }

    public function getDifficulty(): int {
        return $this->difficulty;
    }

    public function getScore(): int {
        return $this->score;
    }
}
