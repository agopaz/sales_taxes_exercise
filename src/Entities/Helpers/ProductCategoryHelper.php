<?php

namespace SalesTaxesExample\Entities\Helpers;

use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Datasets\Unlabeled;
use SalesTaxesExample\Entities\ProductCategory;

class ProductCategoryHelper
{
    protected static $_instance = null;

    /**
     * Instance of the class.
     *
     * @return TaxHelper
     */
    public static function getInstance(): ProductCategoryHelper
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }


    protected function __construct()
    {
        // Estimator for product category:
        $this->_estimator = PersistentModel::load(new Filesystem(__DIR__ . '/../../../ml/product_category/data/model.rbx'));
    }


    /**
     * Guess product category by product name.
     *
     * @param string $productName
     *
     * @return ProductCategory
     */
    public function guess(string $productName): ProductCategory
    {
        // Dataset:
        $dataset = new Unlabeled([ $productName ]);

        // Prediction:
        $prediction = current($this->_estimator->predict($dataset));

        // Product category:
        $productCategory = new ProductCategory($prediction);

        return $productCategory;
    }


    private function __clone()
    {     }

    private function __wakeup()
    {    }
}