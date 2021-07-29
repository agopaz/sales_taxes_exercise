<?php

namespace SalesTaxesExample\Entities;

use Money\Money;
use SalesTaxesExample\Entities\Helpers\MoneyHelper;
use Money\Currency;

/**
 * Model for the order item.
 *
 * @author Agostino Pagnozzi
 */
class OrderItem
{
    use PriceTrait, TaxesTrait;

    protected $_qty = 1;
    protected $_product = null;
    protected $_isImported = false;
    protected $_packageName = "";


    public function __construct(Product $product = null, int $qty = 1, bool $isImported = false, string $packageName = "", string $price = null, ?Currency $currency = null)
    {
        $this->_qty = $qty;
        $this->_product = $product;
        $this->_isImported = $isImported;
        $this->_packageName = $packageName;

        $this->setPrice($price, $currency);
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
         * Parse the string to identify if item is imported, product name and package name.
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

        // Instance of product from mongo db:
        $product = Product::loadByName(trim($productName));

        return new OrderItem($product, (int)$qty, $isImported, trim($packageName), $price);
    }


    /**
     * Get a string from an order item.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf("%s %s%s%s: %s",
            $this->_qty,
            $this->_isImported ? "imported " : "",
            (!empty($this->_packageName)) ? $this->_packageName . " of " : "",
            $this->_product->getName(),
            MoneyHelper::getInstance()->asDecimal($this->getTotalAfterTaxes())
        );
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
     * Package name of the product.
     *
     * @return string
     */
    public function getPackageName(): string
    {
        return $this->_packageName;
    }


    /**
     * Is product imported?
     *
     * @return bool
     */
    public function getIsImported(): bool
    {
        return $this->_isImported;
    }


    /**
     * Grand total of the order item.
     *
     * @return Money
     */
    public function getTotalAfterTaxes(): Money
    {
        return $this->getPriceAfterTaxes()
                    ->multiply($this->_qty);
    }


    /**
     * Total of taxes for the order item.
     *
     * @return Money
     */
    public function getTotalTaxes(): Money
    {
        return $this->getTaxes()
                    ->multiply($this->_qty);
    }
}