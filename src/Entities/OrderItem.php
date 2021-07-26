<?php

namespace SalesTaxesExample\Entities;

use Money\Money;
use SalesTaxesExample\Entities\Helpers\MoneyHelper;

/**
 * Model for the order item.
 *
 * @author Agostino Pagnozzi
 */
class OrderItem
{
    protected $_qty = 1;
    protected $_product = null;


    public function __construct(Product $product, int $qty = 1)
    {
        $this->_qty = $qty;
        $this->_product = $product;
    }


    /**
     * Parse a string to identify a valid order item.
     *
     * @param string $orderItemString
     *
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $orderItemString): OrderItem
    {
        /**
         * Identify quantity and price:
         */
        $output = [];
        preg_match('/(\d) (.*) at (.*)/i', $orderItemString, $output);
        if (count($output) != 4) {
            throw new \InvalidArgumentException("Order item string is invalid.");
        }

        [ , $qty, $itemString, $price ] = $output;


        /**
         * Parse the string to identify if product is imported, name and package name.
         *
         * NB: the pattern "1 packet of headache imported pills at 9.75" is intentionally
         * parsed as NOT imported item with name: "headache imported pills".
         */
        $output = [];
        preg_match('/(imported )?((.*) of )?(imported )?(.*)/i', $itemString, $output);
        if (count($output) != 6) {
            throw new \InvalidArgumentException("Order item string is invalid.");
        }

        [ , , , $packageName, , $productName ] = $output;

        // Identify if product is imported:
        $isImported = false;
        if (!empty($output[1]) || !empty($output[4])) {
            $isImported = true;
        }

        // Create a product:
        $product = new Product(trim($productName), $price, $isImported, trim($packageName));

        return new OrderItem($product, (int)$qty);
    }


    /**
     * Get a string from an order item.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf("%s %s: %s", $this->_qty, $this->_product->__toString(), MoneyHelper::getInstance()->asDecimal($this->getTotalAfterTaxes()));
    }


    /**
     * Quantity of products for this order item.
     *
     * @return int
     */
    public function getQty(): int
    {
        return $this->_qty;
    }


    /**
     * Product
     *
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->_product;
    }


    /**
     * Grand total of the order item.
     *
     * @return Money
     */
    public function getTotalAfterTaxes(): Money
    {
        return $this->_product
                    ->getPriceAfterTaxes()
                    ->multiply($this->_qty);
    }


    /**
     * Total of taxes for the order item.
     *
     * @return Money
     */
    public function getTaxes(): Money
    {
        return $this->_product
                    ->getTaxes()
                    ->multiply($this->_qty);
    }
}