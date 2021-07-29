<?php

namespace SalesTaxesExample\Entities;

use Money\Money;
use SalesTaxesExample\Entities\Helpers\TaxHelper;


/**
 * Trait used to add taxes features.
 *
 * @author Agostino Pagnozzi
 */
trait TaxesTrait
{
    /**
     * @var Money
     */
    protected $_taxes = null;


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