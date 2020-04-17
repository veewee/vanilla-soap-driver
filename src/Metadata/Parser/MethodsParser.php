<?php

declare(strict_types=1);

namespace WsdlTools\Metadata\Parser;

use Phpro\SoapClient\Soap\Engine\Metadata\Collection\MethodCollection;
use Phpro\SoapClient\Soap\Engine\Metadata\Model\Method;
use Phpro\SoapClient\Soap\Engine\Metadata\Model\Parameter;
use Phpro\SoapClient\Soap\Engine\Metadata\Model\XsdType;
use WsdlTools\Metadata\Detector\ServiceDetector;
use WsdlTools\Wsdl;
use function WsdlTools\parseNamespacedString;

/**
 * TODO: in current implementation we assume everything exists; Remove naivity by adding error handling.
 */
class MethodsParser
{
    private ServiceDetector $serviceDetector;

    public function __construct(ServiceDetector $serviceDetector)
    {
        $this->serviceDetector = $serviceDetector;
    }

    public function parse(Wsdl $wsdl): MethodCollection
    {
        $service = $this->serviceDetector->detect($wsdl);

        return new MethodCollection(...array_values(array_map(
            fn (array $operation) => $this->parseMethod($service, $operation['name']),
            $service['binding']['operations']
        )));
    }

    private function parseMethod(array $service, string $operationName): Method
    {
        $portInfo = $service['port']['operations'][$operationName] ?? [];
        $inputInfo = $portInfo['input'];
        $outputInfo = $portInfo['output'];

        $filterMessageName = fn (string $namespaced): string => parseNamespacedString($namespaced)[1];
        $inputMessage = $filterMessageName($inputInfo['message']);
        $outputMessage = $filterMessageName($outputInfo['message']);

        $messages = [
            $inputMessage => $service['messages'][$inputMessage] ?? [],
            $outputMessage => $service['messages'][$outputMessage] ?? [],
        ];

        return new Method(
            $operationName,
            $this->parseXsdTypesFromMessage($service, $messages[$inputMessage]),
            current($this->parseXsdTypesFromMessage($service, $messages[$outputMessage]))->getType()
        );
    }

    /**
     * @return array|Parameter[]
     */
    private function parseXsdTypesFromMessage(array $service, array $message): array
    {
        $lookupNsUri = fn (string $prefix): string => $service['namespaceMap'][$prefix] ?? '';

        return array_values(array_map(
            static function (array $param) use ($lookupNsUri): Parameter {
                [$elementNamespaceAlias, $elementName] = parseNamespacedString($param['element']);

                return new Parameter(
                    $elementName,
                    XsdType::guess($elementName)
                        ->withXmlNamespaceName($elementNamespaceAlias)
                        ->withXmlNamespace($lookupNsUri($elementNamespaceAlias))
                );
            },
            $message['parts']
        ));
    }
}
