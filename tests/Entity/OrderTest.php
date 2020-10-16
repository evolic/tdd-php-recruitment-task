<?php
declare(strict_types=1);

namespace Recruitment\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Recruitment\Cart\Item;
use Recruitment\Entity\Order;
use Recruitment\Entity\Product;

class OrderTest extends TestCase
{
    /**
     * @test
     */
    public function itCanCreateAnOrder(): void
    {
        $order = new Order(1);

        $item1 = $this->buildCartItem(11, 1000, 5, 10);
        $item2 = $this->buildCartItem(12, 750, 2, 3);

        $order->setItems([
            $item1,
            $item2
        ]);

        $this->assertEquals([$item1, $item2], $order->getItems());
        $this->assertEquals([
            'id' => 1,
            'items' => [
                [
                    'id' => 11,
                    'quantity' => 10,
                    'total_price' => 10000,
                ],
                [
                    'id' => 12,
                    'quantity' => 3,
                    'total_price' => 2250,
                ]
            ],
            'total_price' => 12250,
        ], $order->getDataForView());
    }

    /**
     * @param int $productId
     * @param int $unitPrice
     * @param int $minimumQuantity
     * @param int $quantity
     *
     * @return Item
     */
    private function buildCartItem(int $productId, int $unitPrice, int $minimumQuantity, int $quantity): Item
    {
        $product = (new Product())
            ->setId($productId)
            ->setUnitPrice($unitPrice)
            ->setMinimumQuantity($minimumQuantity)
        ;

        return new Item($product, $quantity);
    }
}
