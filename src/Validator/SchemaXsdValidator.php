<?php

declare(strict_types=1);

namespace WsdlTools\Validator;

use WsdlTools\Iterator\Schema\SchemaIterator;
use WsdlTools\Validator\Document\XsdValidator;
use WsdlTools\Wsdl;

class SchemaXsdValidator implements ValidatorInterface
{
    private XsdValidator $validator;

    public function __construct(XsdValidator $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Wsdl $wsdl): \Generator
    {
        $schemas = new SchemaIterator($wsdl);
        foreach ($schemas as $schema) {
            $document = $this->createDocumentFromSchema($schema);
            yield from $this->validator->validate($document, dirname(__DIR__, 2).'/validators/XMLSchema.xsd');
        }
    }

    private function createDocumentFromSchema(\DOMElement $schema): \DOMDocument
    {
        $newdoc = new \DOMDocument();
        $newdoc->formatOutput = false;
        $node = $newdoc->importNode($schema, true);
        $newdoc->appendChild($node);

        return $newdoc;
    }
}
