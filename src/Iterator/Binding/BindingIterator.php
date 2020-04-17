<?php

declare(strict_types=1);

namespace WsdlTools\Iterator\Binding;

use WsdlTools\Wsdl;

class BindingIterator implements \IteratorAggregate
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
            [...$xpath->query('/wsdl:definitions/wsdl:binding')],
            fn (array $bindings, \DOMElement $binding): array => array_merge(
                $bindings,
                [
                    $binding->getAttribute('name') => [
                        'name' => $binding->getAttribute('name'),
                        'type' => $binding->getAttribute('type'),
                        'transport' => $xpath->evaluate('string(./soap:binding/@transport)', $binding),
                        'operations' => array_reduce(
                            [...$xpath->query('./wsdl:operation', $binding)],
                            fn (array $operations, \DOMElement $operation): array => array_merge(
                                $operations,
                                [
                                    $operation->getAttribute('name') => [
                                        'name' => $operation->getAttribute('name'),
                                        'soapAction' => $xpath->evaluate('string(./soap:operation/@soapAction)', $operation),
                                        'style' => $xpath->evaluate('string(./soap:operation/@style)', $operation),
                                        'input' => [
                                            'name' => $xpath->evaluate('string(./wsdl:input/@name)', $operation),
                                            'bodyUse' => $xpath->evaluate('string(./wsdl:input/soap:body/@use)', $operation),
                                        ],
                                        'output' => [
                                            'name' => $xpath->evaluate('string(./wsdl:output/@name)', $operation),
                                            'bodyUse' => $xpath->evaluate('string(./wsdl:output/soap:body/@use)', $operation),
                                        ]
                                    ],
                                ]
                            ),
                            []
                        ),
                    ]
                ]
            ),
            []
        );
    }
}
