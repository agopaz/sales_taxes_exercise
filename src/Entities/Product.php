<?php

namespace SalesTaxesExample\Entities;

use Money\Money;
use SalesTaxesExample\Entities\Helpers\ProductCategoryHelper;

/**
 * Model for the product.
 *
 * @author Agostino Pagnozzi
 */
class Product
{
    use PriceTrait;

    protected $_name = "";
    protected $_is_imported = false;
    protected $_package_name = null;
    protected $_category = null;


    /**
     * Product
     *
     * @param string $name
     * @param int|float|string|Money $price
     * @param bool $isImported
     * @param string $packageName
     */
    public function __construct(string $name, $price, bool $isImported = false, string $packageName = null)
    {
        $this->_name = $name;
        $this->_is_imported = $isImported;
        $this->_package_name = $packageName;

        // Category:
        $this->_guessCategory();

        // Price:
        $this->setPrice($price);
    }


    /**
     * Name of the product.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }


    /**
     * Package name of the product.
     *
     * @return string
     */
    public function getPackageName(): string
    {
        return $this->_package_name;
    }


    /**
     * Is product imported?
     *
     * @return bool
     */
    public function getIsImported(): bool
    {
        return $this->_is_imported;
    }


    /**
     * Product category.
     *
     * @return ProductCategory
     */
    public function getCategory()
    {
        return $this->_category;
    }


    /**
     * Guess category by product name.
     */
    protected function _guessCategory()
    {
        $this->_category = ProductCategoryHelper::getInstance()->guess($this->_name);
    }


    /**
     * Represents the product as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $fullName = "";

        // Imported product or not:
        if ($this->_is_imported) {
            $fullName .= "imported ";
        }

        // Name of the package:
        if (!empty($this->_package_name)) {
            $fullName .= $this->_package_name . " of ";
        }

        // Name of the product:
        $fullName .= $this->getName();

        return $fullName;
    }
}