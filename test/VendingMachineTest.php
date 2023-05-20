<?php
namespace model\test;

require_once __DIR__ . "/../model/VendingMachine.php";

use Exception;
use model\class\Article;
use model\class\VendingMachine;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    private VendingMachine $vendingMachine;

    protected function setUp(): void
    {
        $articles[] = new Article(
            "Smarlies",
            "A01",
            10,
            1.60
        );

        $articles[] = new Article(
            "Carampar",
            "A02",
            5,
            0.60
        );
        $articles[] = new Article(
            "Avril",
            "A03",
            2,
            2.10
        );
        $articles[] = new Article(
            "KokoKola",
            "A04",
            1,
            2.95
        );
        $this->vendingMachine = new VendingMachine($articles);
    }

    public function testInsert(): void
    {
        $this->vendingMachine->Insert(1.00);
        $this->assertEquals(
            1.00,
            $this->vendingMachine->GetChange()
        );
    }

    public function testInsertNegative(): void
    {
        $this->vendingMachine->Insert(-1.00);
        $this->assertEquals(
            0.00,
            $this->vendingMachine->GetChange()
        );
    }

    /**
     * @throws Exception
     */
    public function testChoose(): void
    {
        $this->vendingMachine->Insert(1.60);
        $this->assertEquals(
            "Vending Smarlies",
            $this->vendingMachine->Choose("A01")
        );
    }

    /**
     * @throws Exception
     */
    public function testChooseNotEnoughMoney(): void
    {
        $this->vendingMachine->Insert(1.00);
        $this->assertEquals(
            "Not enough money!",
            $this->vendingMachine->Choose("A01")
        );
    }

    /**
     * @throws Exception
     */
    public function testChooseOutOfStock(): void
    {
        $this->vendingMachine->Insert(10.00);
        $this->vendingMachine->Choose("A04");
        $this->assertEquals(
            "Item KokoKola: Out of stock!",
            $this->vendingMachine->Choose("A04")
        );
    }

    /**
     * @throws Exception
     */
    public function testChooseInvalidSelection(): void
    {
        $this->vendingMachine->Insert(10.00);
        $this->assertEquals(
            "Invalid selection!",
            $this->vendingMachine->Choose("A05")
        );
    }

    /**
     * @throws Exception
     */
    public function testGetBalance(): void
    {
        $this->vendingMachine->Insert(10.00);
        $this->vendingMachine->Choose("A03");
        $this->vendingMachine->Choose("A03");
        $this->assertEquals(
            4.20,
            $this->vendingMachine->GetBalance()
        );
    }

    /**
     * @throws Exception
     */
    public function testGetChange(): void
    {
        $this->vendingMachine->Insert(10.00);
        $this->vendingMachine->Choose("A02");
        $this->vendingMachine->Choose("A02");
        $this->assertEquals(
            8.80,
            $this->vendingMachine->GetChange()
        );
    }

    #Test in CDC

    /**
     * @throws Exception
     */
    public function testGetChangeSuccess(): void
    {
        $this->vendingMachine->Insert(3.40);
        $this->vendingMachine->Choose("A01");
        $this->assertEquals(
            1.80,
            $this->vendingMachine->GetChange()
        );
    }

    /**
     * @throws Exception
     */
    public function testGetBalanceSuccess(): void
    {
        $this->vendingMachine->Insert(2.10);
        $this->vendingMachine->Choose("A03");
        $this->assertEquals(0,$this->vendingMachine->GetChange());
        $this->assertEquals(2.10,$this->vendingMachine->GetBalance());
    }

    /**
     * @throws Exception
     */
    public function testChooseNotEnoughMoneyError(): void
    {
        $this->assertEquals("Not enough money!", $this->vendingMachine->Choose("A01"));
    }

    /**
     * @throws Exception
     */
    public function testDoubleInsertSuccess(): void
    {
        $this->vendingMachine->Insert(1.00);
        $this->vendingMachine->Choose("A01");
        $this->assertEquals(1.00,$this->vendingMachine->GetChange());
        $this->vendingMachine->Choose("A02");
        $this->assertEquals(0.40,$this->vendingMachine->GetChange());
    }

    /**
     * @throws Exception
     */
    public function testChooseInvalidSelectionFail(): void
    {
        $this->vendingMachine->Insert(1.00);
        $this->assertEquals("Invalid selection!", $this->vendingMachine->Choose("A05"));
    }

    /**
     * @throws Exception
     */
    public function testChooseOutOfStockFail(): void
    {
        $this->vendingMachine->Insert(6.00);
        $this->assertEquals("Vending KokoKola", $this->vendingMachine->Choose("A04"));
        $this->assertEquals("Item KokoKola: Out of stock!", $this->vendingMachine->Choose("A04"));
    }

    /**
     * @throws Exception
     */
    public function testGetLogsSuccess(): void
    {
        $articles[] = new Article(
            "Smarlies",
            "A01",
            100,
            1.60
        );

        $articles[] = new Article(
            "Carampar",
            "A02",
            50,
            0.60
        );
        $articles[] = new Article(
            "Avril",
            "A03",
            20,
            2.10
        );
        $articles[] = new Article(
            "KokoKola",
            "A04",
            10,
            2.95
        );
        $vendingMachineExtension = new VendingMachine($articles);

        $vendingMachineExtension->Insert(1000.00);
        $vendingMachineExtension->SetTime("2020-01-01T20:30:00");
        $vendingMachineExtension->Choose("A01");
        $vendingMachineExtension->SetTime("2020-03-01T23:30:00");
        $vendingMachineExtension->Choose("A01");
        $vendingMachineExtension->SetTime("2020-03-04T09:22:00");
        $vendingMachineExtension->Choose("A01");
        $vendingMachineExtension->SetTime("2020-04-01T23:00:00");
        $vendingMachineExtension->Choose("A01");
        $vendingMachineExtension->SetTime("2020-04-01T23:59:59");
        $vendingMachineExtension->Choose("A01");
        $vendingMachineExtension->SetTime("2020-04-04T09:12:00");
        $vendingMachineExtension->Choose("A01");
        $this->assertEquals(
            [
                0 => 'Hour 23 generated a revenue of 4.80',
                1 => 'Hour 9 generated a revenue of 3.20',
                2 => 'Hour 20 generated a revenue of 1.60'
            ],
            $vendingMachineExtension->GetLogs()
        );
    }
}
