<?php

namespace SalesTaxesExample\Entities;

use Money\Money;
use Money\Currency;
use SalesTaxesExample\Entities\Helpers\TaxHelper;
use SalesTaxesExample\Entities\Helpers\MoneyHelper;


/**
 * Trait used to add same price features the product model.
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
     * @var Money
     */
    protected $_taxes = null;


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
        $this->_calculateTaxes();
    }


    /**
     * Total taxes.
     *
     * @return Money
     */
    public function getTaxes(): Money
    {
        return $this->_taxes;
    }


    /**
     * Total after taxes.
     *
     * @return Money
     */
    public function getPriceAfterTaxes(): Money
    {
        return $this->_price->add($this->_taxes);
    }


    /**
     * Calculate taxes on price.
     */
    protected function _calculateTaxes()
    {
        $this->_taxes = TaxHelper::getInstance()->calculateTaxes($this);
    }
}