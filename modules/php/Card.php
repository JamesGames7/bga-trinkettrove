<?php

class Card {
    private string $name;
    private int $value;
    private array $modifiers;
    private int $pos;

    public function __construct(string $name, int $value, array $modifiers, int $pos)
    {
        $this->name = $name;
        $this->value = $value;
        $this->modifiers = $modifiers;
        $this->pos = $pos;
    }

    public function getName() {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }

    public function getModifiers() {
        return $this->modifiers;
    }

    public function getPos() {
        return $this->pos;
    }

    public function getInfo($id = -1) {
        return ["id" => $id, "name" => $this->name, "value" => $this->value, "modifiers" => $this->modifiers, "pos" => $this->pos];
    }
}