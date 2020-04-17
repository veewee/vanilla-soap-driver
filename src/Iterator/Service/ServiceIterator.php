<?php

declare(strict_types=1);

namespace WsdlTools\Iterator\Service;

use WsdlTools\Wsdl;

class ServiceIterator implements \IteratorAggregate
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
            [...$this->wsdl->xpath()->query('/wsdl:definitions/wsdl:service')],
            fn(array $services, \DOMElement $service): array => array_merge(
                $services,
                [
                    $service->getAttribute('name') => [
                        'name' => $service->getAttribute('name'),
                        'port' => [
                            'name' => $xpath->evaluate('string(./wsdl:port/@name)', $service),
                            'binding' => $xpath->evaluate('string(./wsdl:port/@binding)', $service),
                        ],
                        'address' => [
                            'location' => $xpath->evaluate('string(./wsdl:port/soap:address/@location)', $service)
                        ],
                    ]
                ]
            ),
            []
        );
    }
}
