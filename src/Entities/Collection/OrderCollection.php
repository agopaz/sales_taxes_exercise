<?php

namespace SalesTaxesExample\Entities\Collection;

use SalesTaxesExample\Entities\Order;

class OrderCollection extends AbstractCollection
{
    /**
     * Chek if the order is valid.
     *
     * @param mixed $item
     */
    public function asserIsValid($order)
    {
        if (!($order instanceOf Order)) {
            throw new \InvalidArgumentException("Order not valid.");
        }
    }


    /**
     * Get an orders collection from a multiline string or an array of single line strings.
     *
     * @param string|array $orderCollectionStrings
     *
     * @throws \InvalidArgumentException
     *
     * @return OrderCollection
     */
    public static function fromString($orderCollectionStrings): OrderCollection
    {
        // New orders collection:
        $orderCollection = new OrderCollection();

        // Parse into array of single line strings:
        if (!is_array($orderCollectionStrings)) {
            $orderCollectionStrings = explode(PHP_EOL, $orderCollectionStrings);
        } else {
            // Get the array values with numeric index:
            $orderCollectionStrings = array_values($orderCollectionStrings);
        }

        // Add an empty line at the end of collection:
        $orderCollectionStrings[] = "";

        $newOrderStart = 0;
        $numStrings = count($orderCollectionStrings);

        foreach ($orderCollectionStrings as $k => $orderCollectionString) {
            // If the string ends with ":" or we have reached the end of array, add a new order:
            if (substr(trim($orderCollectionString), -1, 1) == ":" || $k == $numStrings - 1) {
                // Avoid empty array:
                if ($k != $newOrderStart) {
                    // First order line:
                    $firstLine = trim($orderCollectionStrings[$newOrderStart]);

                    // Parse first order line, to check if order have an index:
                    $output = [];
                    preg_match('/input ([0-9]+):/i', $firstLine, $output);

                    // Errore nel formato della stringa:
                    if (empty($output[1])) {
                        throw new \InvalidArgumentException("Orders collection string is invalid.");
                    }

                    $orderIndex = (int)$output[1];

                    // Order data:
                    $orderData = array_slice($orderCollectionStrings, $newOrderStart + 1, $k - $newOrderStart - 1);

                    // Create the order:
                    $order = Order::fromString($orderData);

                    // Add the order to the collection:
                    $orderCollection[$orderIndex] = $order;

                    // Update the index:
                    $newOrderStart = $k;
                }
            }
        }

        return $orderCollection;
    }


    /**
     * Get a string from an orders collection.
     *
     * @return string
     */
    public function __toString(): string
    {
        $ret = "";

        if (count($this->_items) > 0) {
            foreach ($this->_items as $k => $order) {
                $ret .= sprintf("Output %d:", $k) . PHP_EOL;
                $ret .= $order->__toString() . PHP_EOL . PHP_EOL;
            }
        }

        return rtrim($ret, PHP_EOL);
    }
}
