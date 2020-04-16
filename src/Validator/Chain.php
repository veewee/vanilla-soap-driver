<?php

declare(strict_types=1);

namespace WsdlTools\Validator;

use WsdlTools\Wsdl;

class Chain implements ValidatorInterface
{
    /**
     * @var array|ValidatorInterface[]
     */
    private array $valdators;

    public function __construct(ValidatorInterface ... $valdators)
    {

        $this->valdators = $valdators;
    }

    public function validate(Wsdl $wsdl): \Generator
    {
        foreach ($this->valdators as $valdator) {
            yield from $valdator->validate($wsdl);
        }
    }
}
