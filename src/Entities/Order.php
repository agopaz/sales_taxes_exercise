<?php

namespace SalesTaxesExample\Entities;

use Money\Currency;
use Money\Money;
use SalesTaxesExample\Entities\Collection\OrderItemCollection;
use SalesTaxesExample\Entities\Helpers\MoneyHelper;

/**
 * Model for the order.
 *
 * @author Agostino Pagnozzi
 */
class Order
{
    /**
     * Collection of order items.
     *
     * @var OrderItemCollection
     */
    protected $_items = null;


    /**
     * @param OrderItemCollection|array|null $items
     */
    public function __construct($items = null, Currency $currency = null)
    {
        $this->_items = new OrderItemCollection($items);
        $this->_currency = $currency ?? MoneyHelper::getInstance()->getDefaultCurrency();
    }


    /**
     * Add an item to the order.
     *
     * @param OrderItem $item
     */
    public function addItem(OrderItem $item)
    {
        $this->_items->append($item);
    }


    /**
     * Order items.
     *
     * @return OrderItemCollection
     */
    public function getItems(): OrderItemCollection
    {
        return $this->_items;
    }


    /**
     * Parse a string to create a new order.
     *
     * @param string|array $orderString
     *
     * @return Order
     */
    public static function fromString($orderStrings): Order
    {
        // Parse into array of single line strings:
        if (!is_array($orderStrings)) {
            $orderStrings = explode('\n\r', $orderStrings);
        }

        // New order:
        $order = new Order();

        // Parse $orderString to identify order items:
        foreach ($orderStrings as $orderItemString) {
            // Get an order item from the string:
            try {
                $orderItem = OrderItem::fromString($orderItemString);

                // If order item is valid add it to the order:
                $order->addItem($orderItem);
            } catch(\InvalidArgumentException $ex) {

            }
        }

        return $order;
    }


    /**
     * Grand total of the order.
     *
     * @return Money
     */
    public function getGrandTotal(): Money
    {
        // Grand total:
        $total = new Money("0.00", $this->_currency);

        foreach ($this->_items as $item) {
            $total = $total->add($item->getTotalAfterTaxes());
        }

        return $total;
    }


    /**
     * Total of taxes for the order.
     *
     * @return Money
     */
    public function getTotalTaxes(): Money
    {
        // Total taxes:
        $taxes = new Money("0.00", $this->_currency);

        foreach ($this->_items as $item) {
            $taxes = $taxes->add($item->getTotalTaxes());
        }

        return $taxes;
    }


    /**
     * Get a string from an order.
     *
     * @return string
     */
    public function __toString(): string
    {
        $ret = "";

        if (count($this->_items) > 0) {
            foreach ($this->_items as $orderItem) {
                $ret .= $orderItem->__toString() . PHP_EOL;
            }
        }

        // Taxes:
        $ret .= "Sales Taxes: " . MoneyHelper::getInstance()->asDecimal($this->getTotalTaxes()) . PHP_EOL;

        // Total:
        $ret .= "Total: " . MoneyHelper::getInstance()->asDecimal($this->getGrandTotal()) . PHP_EOL;

        return rtrim($ret, PHP_EOL);
    }
}