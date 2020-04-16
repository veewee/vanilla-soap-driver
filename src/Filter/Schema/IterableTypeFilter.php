<?php

declare(strict_types=1);

namespace WsdlTools\Filter\Schema;

use WsdlTools\Lookup\Schema\AttributeCountLookup;
use WsdlTools\Lookup\Schema\ElementCountLookup;
use WsdlTools\Lookup\Schema\ElementsLookup;
use WsdlTools\Model\Schema\XsdElement;
use WsdlTools\Model\Schema\XsdType;
use WsdlTools\Wsdl;

class IterableTypeFilter
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function __invoke(XsdType $type): bool
    {
        if (!$type->isComplexType()) {
            return false;
        }

        // Not iterable if the complex type contains attributes
        if ((new AttributeCountLookup($this->wsdl))($type)) {
            return false;
        }

        // Only iterable if there is exactly one element in there
        if ((new ElementCountLookup($this->wsdl))($type) !== 1) {
            return false;
        }

        // Validate if the element is configured to be added multiple times:
        $element = current(((new ElementsLookup($this->wsdl))($type)));
        assert($element instanceof XsdElement);
        if (!$element->canOccurMultipleTimes()) {
            return false;
        }

        return true;
    }
}
