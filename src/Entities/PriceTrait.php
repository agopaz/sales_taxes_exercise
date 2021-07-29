<?php

namespace SalesTaxesExample\Entities;

use Money\Money;
use Money\Currency;
use SalesTaxesExample\Entities\Helpers\MoneyHelper;


/**
 * Trait used to add price features.
 *
 * @author Agostino Pagnozzi
 */
trait PriceTrait
{
    /**
     * @var Money
     */
    protected $_price = null;


    /**
     * @return Money
     */
    public function getPrice(): Money
    {
        return $this->_price;
    }


    /**
     * Set the price.
     *
     * @param int|float|string|Money $price
     * @param Currency $currency
     */
    public function setPrice($price, Currency $currency = null)
    {
        $this->_price = MoneyHelper::getInstance()->parse($price, $currency);

        if (method_exists($this, "_calculateTaxes")) {
            $this->_calculateTaxes();
        }
    }
}