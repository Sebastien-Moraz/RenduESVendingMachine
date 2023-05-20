<?php

namespace model\class;

use DateTime;
use Exception;

require_once "Article.php";

/**
 * Class VendingMachine
 * @package model\class
 */
class VendingMachine
{
    #private parameters
    private array $articles = [];
    private float $amount = 0;
    private float $balance = 0;
    private array $logs;
    private string $dateTime = "";

    public function __construct(array $articles)
    {
        date_default_timezone_set("Europe/Zurich");
        $this->articles = $articles;
    }

    /**
     * @param  float  $amount
     * @return void
     */
    public function Insert(float $amount): void
    {
        if ($amount > 0) {
            $this->amount = $amount;
        }
    }

    /**
     * @param string $code
     * @return string
     * @throws Exception
     */
    public function Choose(string $code): string
    {
        foreach ($this->articles as $article) {
            if ($article->GetCode() === $code) {
                if ($article->GetQuantity() > 0) {
                    if ($this->amount >= $article->GetPrice()) {
                        $article->setQuantity($article->GetQuantity() - 1);
                        $this->amount -= $article->GetPrice();
                        $this->balance += $article->GetPrice();
                        $this->AddLog($article->GetPrice());
                        return $article->GetName();
                    }
                    return "Not enough money!";
                }
                return $article->GetName() . ": Out of stock!";
            }
        }
        return "Invalid selection!";
    }

    /**
     * @return float
     */
    public function GetChange(): float
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function GetBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param  string  $dateTime
     */
    public function SetTime(string $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @param  float  $price
     * @throws Exception
     */
    private function AddLog(float $price): void
    {
        if ($this->dateTime != ""){
            $date = new DateTime($this->dateTime);
        }
        else{
            $date = new DateTime();
        }
        $this->logs[] = [
            "dateTime" => $date->format("H"),
            "price" => $price
        ];
    }

    public function GetLogs(): array
    {
        $hoursSplit = [];
        $result = [];
        foreach ($this->logs as  $values) {
            $check = false;
            foreach ($hoursSplit as $key => $valuesSplit) {
                if ($valuesSplit["dateTime"] === $values["dateTime"]) {
                    $hoursSplit[$key]["price"] += $values["price"];
                    $check = true;
                    break;
                }
            }
            if (!$check) {
                $hoursSplit[] = [
                    "price" => $values["price"],
                    "dateTime" => $values["dateTime"]
                ];
            }
        }

        arsort($hoursSplit);
        foreach ($hoursSplit as $value){
            $result[] =
                'Hour '
                . intval($value["dateTime"])
                . ' generated a revenue of '
                . number_format($value["price"], 2);
        }
        return $result;
    }
}


