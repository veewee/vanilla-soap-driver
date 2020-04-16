<?php

declare(strict_types=1);

namespace WsdlTools;

class Wsdl
{
    private \DOMXPath $xpath;
    private \DOMDocument $document;

    private function __construct(\DOMDocument $document)
    {
        $this->document = $document;
        $this->xpath = new \DOMXPath($document);
        $this->xpath->registerNamespace('wsdl', 'http://schemas.xmlsoap.org/wsdl/');
        $this->xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/wsdl/soap/');
        $this->xpath->registerNamespace('schema', 'http://www.w3.org/2001/XMLSchema');
        $this->xpath->registerNamespace('tns', $document->documentElement->getAttribute('targetNamespace'));
    }

    public static function fromFile(string $file)
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = false;
        $doc->load($file);

        return new self($doc);
    }

    public function document(): \DOMDocument
    {
        return $this->document;
    }

    public function xpath(): \DOMXPath
    {
        return $this->xpath;
    }
}
