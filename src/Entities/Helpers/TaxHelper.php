<?php

namespace SalesTaxesExample\Entities\Helpers;

use SalesTaxesExample\Entities\Product;
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
     * @param Product $product
     *
     * @return Money
     */
    public function calculateTaxes(Product $product): Money
    {
        // Price of product:
        $price = $product->getPrice();

        // Determines the taxes for the product:
        $productTaxCollection = $this->getTaxesForProduct($product);

        // Total tax for product:
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
     * @param Product $product
     */
    public function getTaxesForProduct(Product $product)
    {
        // Collection of taxes:
        $productTaxCollection = new ProductTaxCollection();

        // Default tax (except for books, food, and medical products):
        switch ($product->getCategory()->getName()) {
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
        if ($product->getIsImported()) {
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