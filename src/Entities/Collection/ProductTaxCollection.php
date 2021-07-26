<?php

namespace SalesTaxesExample\Entities\Collection;

use SalesTaxesExample\Entities\ProductTax;

class ProductTaxCollection extends AbstractCollection
{
    /**
     * Chek if the item is an instance of order item class.
     *
     * @param mixed $item
     */
    public function asserIsValid($productTax)
    {
        if (!($productTax instanceOf ProductTax)) {
            throw new \InvalidArgumentException("Product tax not valid.");
        }
    }
}