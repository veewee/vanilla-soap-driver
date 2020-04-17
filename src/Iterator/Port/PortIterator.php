<?php

declare(strict_types=1);

namespace WsdlTools\Iterator\Port;

use Exception;
use Traversable;
use WsdlTools\Wsdl;

class PortIterator implements \IteratorAggregate
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function getIterator(): \Generator
    {
        $xpath = $this->wsdl->xpath();

        yield from array_reduce(
            [...$xpath->query('/wsdl:definitions/wsdl:portType')],
            fn (array $ports, \DOMElement $port): array => array_merge(
                $ports,
                [
                    $port->getAttribute('name') => [
                        'name' => $port->getAttribute('name'),
                        'operations' => array_reduce(
                            [...$xpath->query('./wsdl:operation', $port)],
                            fn(array $bindings, \DOMElement $operation) => array_merge(
                                $bindings,
                                [
                                    $operation->getAttribute('name') => [
                                        'name' => $operation->getAttribute('name'),
                                        'input' => [
                                            'name' => $xpath->evaluate('string(./wsdl:input/@name)', $operation),
                                            'message' => $xpath->evaluate('string(./wsdl:input/@message)', $operation),
                                        ],
                                        'output' => [
                                            'name' => $xpath->evaluate('string(./wsdl:output/@name)', $operation),
                                            'message' => $xpath->evaluate('string(./wsdl:output/@message)', $operation),
                                        ]
                                    ],
                                ]
                            ),
                            []
                        )
                    ],
                ]
            ),
            []
        );
    }
}
