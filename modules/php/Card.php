<?php

class Card {
    private string $name;
    private int $value;
    private array $points;
    private int $pos;

    public function __construct(string $name, int $value, array $points, int $pos)
    {
        $this->name = $name;
        $this->value = $value;
        $this->points = $points;
        $this->pos = $pos;
    }

    public function getName() {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }

    public function getPoints() {
        return $this->points;
    }

    public function getPos() {
        return $this->pos;
    }

    public function getInfo($id = -1) {
        return ["id" => $id, "name" => $this->name, "value" => $this->value, "points" => $this->points, "pos" => $this->pos];
    }
}