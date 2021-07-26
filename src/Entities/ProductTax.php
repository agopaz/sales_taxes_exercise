<?php

namespace SalesTaxesExample\Entities;

use Money\Money;

/**
 * Model for a product tax.
 *
 * @author Agostino Pagnozzi
 */
class ProductTax
{
    protected $_percentage;

    /**
     * Constructor for product tax model.
     *
     * @param string $name
     * @param int $percentage
     */
    public function __construct(string $name, int $percentage)
    {
        $this->_name = $name;
        $this->_percentage = $percentage;
    }


    /**
     * Apply a tax to a price and get the tax amount.
     *
     * @param Money $price
     *
     * @return Money
     */
    public function calculate(Money $price): Money
    {
        return $price->multiply($this->_percentage)
                     ->divide(500, Money::ROUND_UP) // divide by 100, and the to round up to the nearest 0.05
                     ->multiply(5);                 // divide e multiply by 5, rounding up after division
    }


    /**
     * Get the tax name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }


    /**
     * Get the tax percentage.
     *
     * @return int
     */
    public function getPercentage(): int
    {
        return $this->_percentage;
    }
}