<?php

declare(strict_types=1);

namespace WsdlTools\Iterator\Schema;

use WsdlTools\Model\Schema\XsdType;
use WsdlTools\Wsdl;

class SchemaIterator implements \IteratorAggregate
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function getIterator(): \Generator
    {
        $xpath = $this->wsdl->xpath();

        yield from [...$xpath->query('/wsdl:definitions/wsdl:types/schema:schema')];
    }
}
