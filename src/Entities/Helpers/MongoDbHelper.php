<?php

namespace SalesTaxesExample\Entities\Helpers;

use MongoDB;

/**
 * Simple helper to get a mongo db instance.
 *
 * @author Agostino Pagnozzi
 */
class MongoDbHelper
{
    protected static $_instance = null;


    /**
     * Mongo db name.
     *
     * TODO: save db name in application config and inject it on bootstrap.
     *
     * @var string
     */
    protected static $_dbName = "sales_taxes";


    /**
     * Instance of the mongo db.
     *
     * @return MongoDB\Database
     */
    public static function getInstance(): MongoDB\Database
    {
        if (null === static::$_instance) {
            static::$_instance = (new MongoDB\Client)->selectDatabase(static::$_dbName);
        }

        return static::$_instance;
    }

    private function __clone()
    {     }

    private function __wakeup()
    {    }
}