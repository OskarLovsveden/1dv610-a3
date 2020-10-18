<?php

namespace A3\Model;

class RandomNumber {
    private $minValue;
    private $maxValue;
    private $value;

    public function __construct(int $minValue, int $maxValue) {
        if (!is_int($minValue)) {
            throw new \Exception("minValue must be an integer");
        }

        if (!is_int($maxValue)) {
            throw new \Exception("maxValue must be an integer");
        }

        if ($minValue < 0) {
            throw new \Exception("minValue cannot be less than 0");
        }

        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        $this->value = rand($minValue, $maxValue);
    }

    public function getValueToGuess(): int {
        return $this->value;
    }
    public function getMinValue(): int {
        return $this->minValue;
    }
    public function getMaxValue(): int {
        return $this->maxValue;
    }
}
