<?php
declare(strict_types=1);

namespace Recruitment\Cart;

use Recruitment\Cart\Exception\QuantityTooLowException;
use Recruitment\Entity\Product;

class Item
{
    /**
     * @var Product
     */
    private Product $product;

    /**
     * @var int
     */
    private int $quantity;

    /**
     * @var int
     */
    private int $totalPrice;

    /**
     * @param Product $product
     * @param int     $quantity
     */
    public function __construct(Product $product, int $quantity)
    {
        if ($quantity < $product->getMinimumQuantity()) {
            throw new \InvalidArgumentException(
                'Given quantity is less than minimum quantity set for specified product'
            );
        }

        $this->product = $product;
        $this->quantity = $quantity;

        $this->calculateTotalPrice();
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        if ($quantity < $this->product->getMinimumQuantity()) {
            throw new QuantityTooLowException('Given quantity is too low');
        }

        $this->quantity = $quantity;

        $this->calculateTotalPrice();

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    /**
     * @return int
     */
    public function getTotalPriceGross(): int
    {
        return (int)($this->totalPrice * (1 + $this->product->getTaxRate()));
    }

    private function calculateTotalPrice(): void
    {
        $this->totalPrice = $this->product->getUnitPrice() * $this->quantity;
    }
}
