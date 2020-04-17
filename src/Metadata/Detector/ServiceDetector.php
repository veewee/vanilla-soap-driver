<?php

declare(strict_types=1);

namespace WsdlTools\Metadata\Detector;

use WsdlTools\Iterator\Binding\BindingIterator;
use WsdlTools\Iterator\Message\MessageIterator;
use WsdlTools\Iterator\NamespaceIterator;
use WsdlTools\Iterator\Port\PortIterator;
use WsdlTools\Iterator\Service\ServiceIterator;
use WsdlTools\Wsdl;
use function WsdlTools\parseNamespacedString;

/**
 * TODO : Currently doesn't consider namespaced binding lookup...
 */
class ServiceDetector
{
    public function detect(Wsdl $wsdl): array
    {
        $services = new ServiceIterator($wsdl);
        $ports = iterator_to_array(new PortIterator($wsdl), true);
        $bindings = iterator_to_array(new BindingIterator($wsdl), true);
        $messages = iterator_to_array(new MessageIterator($wsdl), true);
        $namespaces = iterator_to_array(new NamespaceIterator($wsdl), true);

        foreach ($services as $service) {
            $requiredPort = $service['port']['name'];
            [$bindingNamespace, $requiredBinding] = parseNamespacedString($service['port']['binding']);

            if (!array_key_exists($requiredPort, $ports) || !array_key_exists($requiredBinding, $bindings)) {
                continue;
            }

            return [
                'service' => $service,
                'port' => $ports[$requiredPort],
                'binding' => $bindings[$requiredBinding],
                'messages' => $messages,
                'namespaceMap' => $namespaces,
            ];
        }

        throw new \RuntimeException('Parsing WSDL: Couldn\'t bind to any service');
    }
}
