<?php

namespace SalesTaxesExample\Entities\Collection;

use SalesTaxesExample\Entities\OrderItem;
use Money\Currency;

class OrderItemCollection extends AbstractCollection
{
    /**
     * Chek if the item is an instance of order item class.
     *
     * @param mixed $item
     */
    public function asserIsValid($orderItem)
    {
        if (!($orderItem instanceOf OrderItem)) {
            throw new \InvalidArgumentException("Order item not valid.");
        }
    }
}