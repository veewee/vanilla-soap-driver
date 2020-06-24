<?php

declare(strict_types=1);

namespace WsdlTools\Metadata\Parser;

use Phpro\SoapClient\Soap\Engine\Metadata\Collection\TypeCollection;
use WsdlTools\Metadata\Detector\SchemaDetector;
use WsdlTools\Metadata\Provider\TypeProvider;
use WsdlTools\Wsdl;

class TypesParser
{
    private SchemaDetector $schemaDetector;
    /**
     * @var TypeProvider
     */
    private TypeProvider $typeProvider;

    public function __construct(SchemaDetector $schemaDetector, TypeProvider $typeProvider)
    {
        $this->schemaDetector = $schemaDetector;
        $this->typeProvider = $typeProvider;
    }

    public function parse(Wsdl $wsdl): TypeCollection
    {
        $schema = $this->schemaDetector->detect($wsdl);
        $types = [...$this->typeProvider->forSchema($schema)];


        return new TypeCollection(...$types);


        /*return new MethodCollection(...array_values(array_map(
            fn (array $operation) => $this->parseMethod($service, $operation['name']),
            $service['binding']['operations']
        )));*/
    }
}
