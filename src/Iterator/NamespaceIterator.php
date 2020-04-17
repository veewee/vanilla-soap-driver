<?php

declare(strict_types=1);

namespace WsdlTools\Iterator;

use WsdlTools\Wsdl;

class NamespaceIterator implements \IteratorAggregate
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function getIterator()
    {
        $xpath = $this->wsdl->xpath();

        yield from array_reduce(
            [...$xpath->query('namespace::*', $this->wsdl->document()->documentElement)],
            fn(array $namespaces,\DOMNameSpaceNode $node): array => array_merge(
                $namespaces,
                [
                    $node->localName => $node->namespaceURI,
                ]
            ),
            []
        );
    }
}
