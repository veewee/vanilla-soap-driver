<?php

declare(strict_types=1);

namespace WsdlTools\Iterator\Message;

use Exception;
use Traversable;
use WsdlTools\Wsdl;

class MessageIterator implements \IteratorAggregate
{
    private Wsdl $wsdl;

    public function __construct(Wsdl $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function getIterator(): \Generator
    {
        $xpath = $this->wsdl->xpath();

        yield from array_reduce(
            [...$xpath->query('/wsdl:definitions/wsdl:message')],
            fn (array $messages, \DOMElement $message): array => array_merge(
                $messages,
                [
                    $message->getAttribute('name') => [
                        'name' => $message->getAttribute('name'),
                        'parts' => array_reduce(
                            [...$xpath->evaluate('./wsdl:part', $message)],
                            fn(array $parts, \DOMElement $part) => array_merge(
                                $parts,
                                [
                                    $part->getAttribute('name') => [
                                        'name' => $part->getAttribute('name'),
                                        'element' => $part->getAttribute('element')
                                    ]
                                ]
                            ),
                            []
                        )
                    ]
                ]
            ),
            []
        );
    }
}
