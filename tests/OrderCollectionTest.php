<?php

namespace Tests;

use SalesTaxesExample\Entities\Collection\OrderCollection;
use PHPUnit\Framework\TestCase as BaseTestCase;

final class OrderCollectionTest extends BaseTestCase
{
    /**
     * @dataProvider orderCollectionProvider
     */
    public function testOrderReceipt($input, $output)
    {
        // Orders collection from input data:
        $orderCollection = OrderCollection::fromString($input);

        $this->assertEquals($output, $orderCollection->__toString());
    }

    /**
     * Data provider per gli ordini.
     *
     * @return array
     */
    public function orderCollectionProvider(): array
    {
        // Input data:
        $inputData = file_get_contents(__DIR__ . '/data/order_collection/input.txt');

        // Output data:
        $outputData = file_get_contents(__DIR__ . '/data/order_collection/output.txt');

        return [
            [ $inputData, $outputData ]
        ];
    }
}