<?php

namespace A3\Model;

class HighScore {
    private $items = array();

    public function add(\A3\Model\HighScoreItem $toBeAdded) {
        $this->items[] = $toBeAdded;
    }

    public function get(): array {
        return $this->items;
    }
}
