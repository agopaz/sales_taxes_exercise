<?php

namespace SalesTaxesExample\Entities;

use SalesTaxesExample\Entities\Helpers\MongoDbHelper;
use MongoDB;

/**
 * Simple Mongo db model.
 *
 * @author Agostino Pagnozzi
 */
abstract class MongoModel
{
    /**
     * Id del document nella collection.
     *
     * @var mixed
     */
    protected $_id = null;


    /**
     * Nome of collection.
     *
     * @var string
     */
    protected $_collectionName = '';


    /**
     * Instance of Collection.
     *
     * @var MongoDB\Collection
     */
    protected static $_collectionInstance = null;


    /**
     * Instance of mongo collection for the model.
     *
     * @return MongoDB\Collection
     */
    public function getCollection(): MongoDB\Collection
    {
        if (null === static::$_collectionInstance) {
            static::$_collectionInstance = MongoDbHelper::getInstance()->selectCollection($this->_collectionName);
        }

        return static::$_collectionInstance;
    }


    /**
     * Id del document nella collection.
     *
     * @return mixed
     */
    public function getMongoId()
    {
        return $this->_id;
    }
}