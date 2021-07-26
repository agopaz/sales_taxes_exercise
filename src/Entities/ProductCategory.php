<?php

namespace SalesTaxesExample\Entities;

/**
 * Model for the product category.
 *
 * @author Agostino Pagnozzi
 */
class ProductCategory
{
    protected $_name = "";


    public function __construct(string $name)
    {
        $this->_name = $name;
    }


    public function getName(): string
    {
        return $this->_name;
    }
}

