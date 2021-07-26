<?php

namespace SalesTaxesExample\Entities\Collection;

use ArrayAccess;

abstract class AbstractCollection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Collection items.
     *
     * @var array
     */
    protected $_items = [];


    /**
     * @param AbstractCollection|array $items
     */
    public function __construct($items = null)
    {
        // Check that it is not null:
        if (null === $items) {
            return;
        }

        if ($items instanceOf AbstractCollection) {
            $this->_items = $items->toArray();
        }
    }


    /**
     * Array of order items.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_items;
    }


    /**
     * Add an item to the collection.
     *
     * @param mixed $item
     */
    public function append($item): AbstractCollection
    {
        // Check the item:
        $this->asserIsValid($item);

        $this->_items[] = $item;

        return $this;
    }


    /**
     * Chek if the item is valid for the collection.
     *
     * @param mixed $item
     */
    abstract public function asserIsValid($item);


    /**
     * @param mixed $offset
     * @param mixed $item
     */
    public function offsetSet($offset, $item)
    {
        // Check the item:
        $this->asserIsValid($item);

        if (is_null($offset)) {
            $this->_items[] = $item;
        } else {
            $this->_items[$offset] = $item;
        }
    }


    /**
     * @param mixed $offset
     */
    public function offsetExists($offset)
    {
        return isset($this->_items[$offset]);
    }


    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_items[$offset]);
    }


    /**
     * @param mixed $offset
     */
    public function offsetGet($offset)
    {
        return $this->_items[$offset] ?? null;
    }


    /**
     * Number of items.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->_items);
    }


    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->_items);
    }
}