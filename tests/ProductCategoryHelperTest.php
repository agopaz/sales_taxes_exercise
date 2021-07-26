<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use SalesTaxesExample\Entities\Helpers\ProductCategoryHelper;

final class ProductCategoryHelperTest extends BaseTestCase
{
    protected $_productCategoryHelperInstance = null;


    /**
     * @dataProvider orderCollectionProvider
     */
    public function testGuess($input, $output)
    {
        // Guess product category:
        $productCategory = ProductCategoryHelper::getInstance()->guess($input);

        $this->assertEquals($output, $productCategory->getName());
    }

    /**
     * Data provider per gli ordini.
     *
     * @return array
     */
    public function orderCollectionProvider(): CsvFileIterator
    {
        return new CsvFileIterator(__DIR__ . '/data/product_category_helper/dataset.csv');
    }
}