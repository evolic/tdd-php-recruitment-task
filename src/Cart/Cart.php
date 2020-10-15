<?php
declare(strict_types=1);

namespace Recruitment\Cart;

use OutOfBoundsException;
use Recruitment\Entity\Order;
use Recruitment\Entity\Product;

class Cart
{
    /**
     * @var Item[]
     */
    private array $items = [];

    /**
     * @var int
     */
    private int $totalPrice = 0;

    /**
     * @param Product $product
     * @param int     $quantity
     *
     * @return self
     */
    public function addProduct(Product $product, int $quantity = 1): self
    {
        $productIndex = $this->getProductItemIndex($product);

        if (null === $productIndex) {
            $newProductIndex = $this->getNewProductItemIndex();

            $this->items[$newProductIndex] = new Item($product, $quantity);
        } else {
            $newQuantity = $this->items[$productIndex]->getQuantity() + $quantity;

            $this->items[$productIndex]->setQuantity($newQuantity);
        }

        $this->totalPrice += $product->getUnitPrice() * $quantity;

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return int|null
     */
    private function getProductItemIndex(Product $product): ?int
    {
        foreach ($this->items as $index => $item) {
            if ($item->getProduct() === $product) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @return int
     */
    private function getNewProductItemIndex(): int
    {
        if (empty($this->items)) {
            return 0;
        }

        $indices = array_keys($this->items);
        $lastIndex = end($indices);

        return $lastIndex + 1;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param int $index
     *
     * @return Item
     */
    public function getItem(int $index): Item
    {
        if (!array_key_exists($index, $this->items)) {
            throw new OutOfBoundsException(sprintf('Item with index %d does not exist', $index));
        }

        return $this->items[$index];
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    /**
     * @param Product $product
     *
     * @return self
     */
    public function removeProduct(Product $product): self
    {
        $this->items = array_reduce($this->items, function (array $carrier, Item $item) use ($product) {
            if ($item->getProduct() !== $product) {
                $carrier[] = $item;
            }

            return $carrier;
        }, []);

        $this->recalculateTotalPrice();

        return $this;
    }

    /**
     * @param Product $product
     * @param int     $quantity
     *
     * @return self
     */
    public function setQuantity(Product $product, int $quantity): self
    {
        $productIndex = $this->getProductItemIndex($product);

        if (null === $productIndex) {
            return $this->addProduct($product, $quantity);
        }

        $this->getItem($productIndex)->setQuantity($quantity);

        $this->recalculateTotalPrice();

        return $this;
    }

    private function recalculateTotalPrice(): void
    {
        $this->totalPrice = array_reduce($this->items, function (int $totalPrice, Item $item) {
            $totalPrice += $item->getProduct()->getUnitPrice() * $item->getQuantity();

            return $totalPrice;
        }, 0);
    }

    /**
     * @param int $orderId
     *
     * @return Order
     */
    public function checkout(int $orderId): Order
    {
        $order = new Order($orderId);

        $order->setItems($this->items);

        $this->items = [];

        $this->recalculateTotalPrice();

        return $order;
    }
}
