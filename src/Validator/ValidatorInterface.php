<?php

declare(strict_types=1);

namespace WsdlTools\Validator;

use WsdlTools\Wsdl;

interface ValidatorInterface
{
    /**
     * @return \Generator<int, string>
     */
    public function validate(Wsdl $wsdl): \Generator;
}
