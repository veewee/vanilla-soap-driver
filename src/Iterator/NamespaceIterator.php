<?php

declare(strict_types=1);

namespace WsdlTools\Iterator;

use WsdlTools\Wsdl;

class NamespaceIterator implements \IteratorAggregate
{
    private Wsdl $wsdl;
    private \DOMNode $node;

    public function __construct(Wsdl $wsdl, \DOMNode $node)
    {
        $this->wsdl = $wsdl;
        $this->node = $node;
    }

    public static function forRootElement(Wsdl $wsdl): self
    {
        return new self($wsdl, $wsdl->document()->documentElement);
    }

    public function getIterator(): \Generator
    {
        $xpath = $this->wsdl->xpath();

        yield from array_reduce(
            [...$xpath->query('namespace::*', $this->node)],
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
