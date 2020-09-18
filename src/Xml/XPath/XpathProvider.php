<?php

declare(strict_types=1);

namespace WsdlTools\Xml\XPath;

use DOMDocument;
use DOMXPath;
use function HappyHelpers\dom\xpath\xpath;

final class XpathProvider
{
    public static function provide(DOMDocument $document): DOMXPath
    {
        return xpath($document, [
            'wsdl' => 'http://schemas.xmlsoap.org/wsdl/',
            'soap' => 'http://schemas.xmlsoap.org/wsdl/soap/',
            'schema' => 'http://www.w3.org/2001/XMLSchema',
            'tns' => $document->documentElement->getAttribute('targetNamespace'),
        ]);
    }
}
