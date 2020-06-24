<?php

declare(strict_types=1);

namespace WsdlTools\Metadata;

use Phpro\SoapClient\Soap\Engine\Metadata\Collection\MethodCollection;
use Phpro\SoapClient\Soap\Engine\Metadata\Collection\TypeCollection;
use Phpro\SoapClient\Soap\Engine\Metadata\MetadataInterface;
use WsdlTools\Metadata\Detector\SchemaDetector;
use WsdlTools\Metadata\Detector\ServiceDetector;
use WsdlTools\Metadata\Parser\MethodsParser;
use WsdlTools\Metadata\Parser\TypesParser;
use WsdlTools\Metadata\Provider\TypeProvider;
use WsdlTools\Wsdl;

class WsdlMetadataProvider implements MetadataInterface
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function getTypes(): TypeCollection
    {
        return (new TypesParser(new SchemaDetector(), new TypeProvider()))->parse($this->wsdl);
    }

    public function getMethods(): MethodCollection
    {
        return (new MethodsParser(new ServiceDetector()))->parse($this->wsdl);
    }
}
