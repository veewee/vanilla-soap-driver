<?php

declare(strict_types=1);

namespace WsdlTools\Lookup\Schema;

use WsdlTools\Model\Schema\XsdType;
use WsdlTools\Wsdl;

class ElementCountLookup
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function __invoke(XsdType $type): int
    {
        return (int) $this->wsdl->xpath()->evaluate('count(.//schema:element)', $type->element());
    }
}
