<?php

declare(strict_types=1);

namespace WsdlTools\Validator;

use WsdlTools\Validator\Document\XsdValidator;
use WsdlTools\Wsdl;

class WsdlXsdValidator implements ValidatorInterface
{
    private XsdValidator $validator;

    public function __construct(XsdValidator $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Wsdl $wsdl): \Generator
    {
        yield from $this->validator->validate($wsdl->document(), dirname(__DIR__, 2).'/validators/wsdl.xsd');
    }
}
