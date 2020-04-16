<?php

declare(strict_types=1);

namespace WsdlTools\Model\Schema;

class XsdType
{
    private string $namespace;
    private \DOMElement $element;

    public function __construct(string $namespace, \DOMElement $element)
    {
        $this->namespace = $namespace;
        $this->element = $element;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function element(): \DOMElement
    {
        return $this->element;
    }

    public function name(): string
    {
        return $this->element->getAttribute('name');
    }

    public function isComplexType(): bool
    {
        return 'complexType' === $this->element->tagName;
    }

    public function isElement(): bool
    {
        return 'element' === $this->element->tagName;
    }

    public function isSimpleType(): bool
    {
        return 'simpleType' === $this->element->tagName;
    }

    public function requiresDownload(): bool
    {
        return in_array($this->element->tagName, ['import', 'include'], true);
    }
}
