<?php

namespace Model;

class RandomNumber {
    private $value;

    public function __construct(int $minValue, int $maxValue) {
        if ($minValue < 0) {
            throw new \Exception("minValue cannot be less than 0");
        }

        $this->value = rand($minValue, $maxValue);
    }

    public function getValue() {
        return $this->value;
    }
}
