<?php

declare(strict_types=1);

namespace WsdlTools\Metadata\Model;

use Phpro\SoapClient\Soap\Engine\Metadata\Model\XsdType;

class ExtendedXsdType extends XsdType
{
    private array $meta;

    public function withMeta(array $meta): self
    {
        $new = clone $this;
        $new->meta = $meta;

        return $new;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}
