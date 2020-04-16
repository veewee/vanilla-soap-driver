<?php

declare(strict_types=1);

namespace WsdlTools\Iterator;

use Exception;
use Traversable;

class FilteredIterator implements \IteratorAggregate
{
    /**
     * @var callable
     */
    private $filter;
    private \IteratorAggregate $innerIterator;

    public function __construct(\IteratorAggregate $innerIterator, callable $filter)
    {
        $this->filter = $filter;
        $this->innerIterator = $innerIterator;
    }

    public function getIterator(): \Generator
    {
        foreach ($this->innerIterator as $key => $item) {
            if (($this->filter)($item)) {
                yield $key => $item;
            }
        }
    }
}
