<?php

declare(strict_types=1);

namespace WsdlTools\Iterator\Schema;

use WsdlTools\Model\Schema\XsdType;
use WsdlTools\Wsdl;

class AllXsdTypesIterator implements \IteratorAggregate
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function getIterator(): \Generator
    {
        $xpath = $this->wsdl->xpath();
        foreach ($xpath->query('/wsdl:definitions/wsdl:types/schema:schema') as $schema) {
            assert($schema instanceof \DOMElement);
            $namespace = $schema->getAttribute('targetNamespace');
            yield from array_map(
                fn (\DOMElement $element) => new XsdType($namespace, $element),
                [...$schema->childNodes]
            );
        }
    }
}
