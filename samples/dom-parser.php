<?php

$doc = new DOMDocument();
$doc->load('test.xml');

$targetNamespace = $doc->documentElement->getAttribute('targetNamespace');
$targetNamespaceAlias = $doc->documentElement->lookupPrefix($targetNamespace);

$xpath = new DOMXPath($doc);
$xpath->registerNamespace('wsdl', 'http://schemas.xmlsoap.org/wsdl/');
$xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/wsdl/soap/');
$xpath->registerNamespace($targetNamespaceAlias, $targetNamespace);
$xpath->registerNamespace('target', $targetNamespace);


$listNamespaces = static function (DOMXPath $xpath, DOMNode $context)
{
    foreach( $xpath->query('namespace::*', $context) as $node ) {
        yield $node;
    }
};

$parsePorts = fn (DOMNodeList $ports) =>
    array_map(
        fn (DOMElement $port) => [
            'name' => $port->getAttribute('name'),
            'operations' => array_map(
                fn(DOMElement $operation) => [
                    'name' => $operation->getAttribute('name'),
                    'input' => [
                        'name' => $xpath->evaluate('string(./wsdl:input/@name)', $operation),
                        'message' => $xpath->evaluate('string(./wsdl:input/@message)', $operation),
                    ],
                    'output' => [
                        'name' => $xpath->evaluate('string(./wsdl:output/@name)', $operation),
                        'message' => $xpath->evaluate('string(./wsdl:output/@message)', $operation),
                    ]
                ],
                [...$xpath->query('./wsdl:operation', $port)]
            )
        ],
        [...$ports]
    );

$parseBindings = fn (DOMNodeList $bindings) =>
    array_map(
        fn (DOMElement $binding) => [
            'name' => $binding->getAttribute('name'),
            'type' => $binding->getAttribute('type'),
            'transport' => $xpath->evaluate('string(./soap:binding/@transport)', $binding),
            'operations' => array_map(
                fn (DOMElement $operation): array => [
                    'name' => $operation->getAttribute('name'),
                    'soapAction' => $xpath->evaluate('string(./soap:operation/@soapAction)', $operation),
                    'style' => $xpath->evaluate('string(./soap:operation/@style)', $operation),
                    'input' => [
                        'name' => $xpath->evaluate('string(./wsdl:input/@name)', $operation),
                        'bodyUse' => $xpath->evaluate('string(./wsdl:input/soap:body/@use)', $operation),
                    ],
                    'output' => [
                        'name' => $xpath->evaluate('string(./wsdl:output/@name)', $operation),
                        'bodyUse' => $xpath->evaluate('string(./wsdl:output/soap:body/@use)', $operation),
                    ]
                ],
                [...$xpath->query('./wsdl:operation', $binding)],
            ),
        ],
        [...$bindings]
    );



$types = $xpath->query('/wsdl:definitions/wsdl:types');
$messages = $xpath->query('/wsdl:definitions/wsdl:message');
$portTypes = $parsePorts($xpath->query('/wsdl:definitions/wsdl:portType'));
$bindings = $parseBindings($xpath->query('/wsdl:definitions/wsdl:binding'));
$services = $xpath->query('/wsdl:definitions/wsdl:service');




