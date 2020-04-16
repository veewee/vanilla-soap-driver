<?php

declare(strict_types=1);

namespace WsdlTools\Model\Schema;

use WsdlTools\Lookup\Schema\ElementsLookup;

class XsdElement
{
    /**
     * @var \DOMElement
     */
    private \DOMElement $element;

    public function __construct(\DOMElement $element)
    {
        $this->element = $element;
    }

    public function maxOccurs(): string
    {
        return $this->element->hasAttribute('maxOccurs') ? $this->element->getAttribute('maxOccurs') : '1';
    }

    public function canOccurMultipleTimes(): bool
    {
        return $this->maxOccurs() !== '1';
    }
}
