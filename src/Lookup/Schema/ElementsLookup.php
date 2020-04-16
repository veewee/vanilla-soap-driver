<?php

declare(strict_types=1);

namespace WsdlTools\Lookup\Schema;

use WsdlTools\Model\Schema\XsdElement;
use WsdlTools\Model\Schema\XsdType;
use WsdlTools\Wsdl;

class ElementsLookup
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    /**
     * @return array<int, XsdElement>
     */
    public function __invoke(XsdType $type): array
    {
        return array_map(
            fn (\DOMElement $element) => new XsdElement($element),
            [...$this->wsdl->xpath()->query('.//schema:element', $type->element())]
        );
    }
}
