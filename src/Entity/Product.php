<?php
declare(strict_types=1);

namespace Recruitment\Entity;

use Recruitment\Entity\Exception\InvalidUnitPriceException;

class Product
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $unitPrice;

    /**
     * @var int
     */
    private int $minimumQuantity = 1;

    /**
     * @var float
     */
    private float $taxRate;

    /**
     * @param int $id
     *
     * @return Product
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return Product
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $unitPrice Product price in grosze
     *
     * @return Product
     */
    public function setUnitPrice(int $unitPrice): self
    {
        if ($unitPrice < 1) {
            throw new InvalidUnitPriceException('Product price is too low');
        }

        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    /**
     * @param int $minimumQuantity
     *
     * @return Product
     */
    public function setMinimumQuantity(int $minimumQuantity): self
    {
        if ($minimumQuantity < 1) {
            throw new \InvalidArgumentException('Minimum quantity must be equal or greater than 1');
        }

        $this->minimumQuantity = $minimumQuantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinimumQuantity(): int
    {
        return $this->minimumQuantity;
    }

    /**
     * @param float $taxRate
     *
     * @return $this
     */
    public function setTaxRate(float $taxRate): self
    {
        if ($taxRate < 0) {
            throw new \InvalidArgumentException('Provided invalid tax rate');
        }

        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }
}
