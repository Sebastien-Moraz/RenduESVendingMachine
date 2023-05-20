<?php

namespace model\class;

/**
 * Class Article
 * @package model\class
 */
class Article
{
    #private parameters
    private string $name;
    private string $code;
    private int $quantity;
    private float $price;

    public function __construct(string $name, string $code, int $quantity, float $price)
    {
        $this->name = $name;
        $this->code = $code;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function GetName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function GetCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

}