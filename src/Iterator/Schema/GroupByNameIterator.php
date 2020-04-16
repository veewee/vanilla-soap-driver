<?php

declare(strict_types=1);

namespace WsdlTools\Iterator\Schema;

use WsdlTools\Model\Schema\XsdType;
use WsdlTools\Wsdl;

class GroupByNameIterator implements \IteratorAggregate
{
    private iterable $xsdTypesIterator;

    public function __construct(iterable $xsdTypesIterator)
    {
        $this->xsdTypesIterator = $xsdTypesIterator;
    }

    public function getIterator(): \Generator
    {
        yield from array_reduce(
            [...$this->xsdTypesIterator],
            fn (array $grouped, XsdType $xsdType): array => array_merge_recursive(
                $grouped,
                [$xsdType->name() => [$xsdType]]
            ),
            []
        );
    }
}
