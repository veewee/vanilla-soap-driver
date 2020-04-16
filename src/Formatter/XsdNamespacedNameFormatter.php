<?php

declare(strict_types=1);

namespace WsdlTools\Formatter;

use WsdlTools\Model\Schema\XsdType;

class XsdNamespacedNameFormatter
{
    public function __invoke(XsdType $xsdType): string
    {
        return '{'.$xsdType->namespace().'}'.$xsdType->name();
    }
}
