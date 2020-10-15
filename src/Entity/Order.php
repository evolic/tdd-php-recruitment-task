<?php
declare(strict_types=1);

namespace Recruitment\Entity;

use Recruitment\Cart\Item;

class Order
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var Item[]
     */
    private array $items = [];

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Item[] $items
     *
     * @return $this
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function getDataForView(): array
    {
        $data = [
            'id' => $this->id,
        ];

        $data['items'] = array_reduce($this->items, function(array $carrier, Item $item) {
            $carrier[] = [
                'id' => $item->getProduct()->getId(),
                'quantity' => $item->getQuantity(),
                'total_price' => $item->getTotalPrice(),
            ];

            return $carrier;
        }, []);

        $data['total_price'] = array_reduce($this->items, function(int $totalPrice, Item $item) {
            $totalPrice += $item->getProduct()->getUnitPrice() * $item->getQuantity();

            return $totalPrice;
        }, 0);

        return $data;
    }
}
