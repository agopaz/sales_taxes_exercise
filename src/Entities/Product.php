<?php

namespace SalesTaxesExample\Entities;

use Money\Currency;
use Money\Money;

/**
 * Model for the product.
 *
 * @author Agostino Pagnozzi
 */
class Product extends MongoModel
{
    use PriceTrait;

    protected $_collectionName = 'products';
    protected $_name = "";
    protected $_categoryName = "";


    /**
     * Product.
     *
     * @param mixed $id
     * @param string $name
     * @param string $categoryName
     * @param int|float|string|Money $price
     * @param Currency $currency
     */
    public function __construct($id = null, string $name = "", string $categoryName = null, $price = null, ?Currency $currency = null)
    {
        $this->_id = $id;
        $this->_name = $name;
        $this->_categoryName = $categoryName;

        $this->setPrice($price, $currency);
    }


    /**
     * Read product from db.
     *
     * @param string $name
     *
     * @return Product
     */
    public static function loadByName(string $name): Product
    {
        $product = (new static)->getCollection()->findOne(['name' => $name]);
        // $product = (new static)->getCollection()->findOne(['$text' => [ '$search' => $name ]]);

        // Prodotto presente in db:
        if ($product) {
            return new Product($product->_id, $product->name, $product->category_name, $product->price, new Currency($product->currency));
        }

        // Prodotto non presente in db:
        return new Product(null, $name, "unknown");
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
     * Name of product category.
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->_categoryName;
    }
}