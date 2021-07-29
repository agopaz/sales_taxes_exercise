<?php

namespace SalesTaxesExample\Entities\Helpers;

use SalesTaxesExample\Entities\OrderItem;
use SalesTaxesExample\Entities\Collection\ProductTaxCollection;
use SalesTaxesExample\Entities\ProductTax;
use Money\Money;


/**
 * Helper for taxes.
 *
 * @author Agostino Pagnozzi
 */
class TaxHelper
{
    protected static $_instance = null;

    /**
     * Instance of the class.
     *
     * @return TaxHelper
     */
    public static function getInstance(): TaxHelper
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }


    /**
     * Protected constructor.
     */
    protected function __construct()
    {    }


    /**
     * Taxes for a product.
     *
     * @param OrderItem $orderItem
     *
     * @return Money
     */
    public function calculateTaxes(OrderItem $orderItem): Money
    {
        // Price of order item:
        $price = $orderItem->getPrice();

        // Determines the taxes for the order item:
        $productTaxCollection = $this->getTaxesForOrderItem($orderItem);

        // Total tax for order item:
        $totalTaxes = new Money("0.00", $price->getCurrency());

        // For each tax calculate tax amount:
        foreach ($productTaxCollection as $productTax) {
            $taxAmount = $productTax->calculate($price);
            $totalTaxes = $totalTaxes->add($taxAmount);
        }

        return $totalTaxes;
    }


    /**
     * Determines the taxes for the product.
     *
     * @param OrderItem $orderItem
     */
    public function getTaxesForOrderItem(OrderItem $orderItem)
    {
        // Collection of taxes:
        $productTaxCollection = new ProductTaxCollection();

        // Category name:
        $categoryName = $orderItem->getProduct()->getCategoryName();

        // Default tax (except for books, food, and medical products):
        switch ($categoryName) {
            case "book":
            case "food":
            case "medical":
                break;

            default:
                $productTaxCollection->append(
                    new ProductTax("Basic sales tax", 10) // a 10% tax
                );
                break;
        }

        // Import duty:
        if ($orderItem->getIsImported()) {
            $productTaxCollection->append(
                new ProductTax("Import duty", 5) // a 5% tax
            );
        }

        return $productTaxCollection;
    }


    private function __clone()
    {     }

    private function __wakeup()
    {    }
}