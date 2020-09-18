<?php

declare(strict_types=1);

namespace WsdlTools\Xml;

use Phpro\SoapClient\Wsdl\Loader\WsdlLoaderInterface;
use WsdlTools\Xml\Configurator\MergeWsdlImports;
use WsdlTools\Xml\Configurator\MergeXsdImports;
use WsdlTools\Xml\XPath\XpathProvider;
use function HappyHelpers\callables\pipe;
use function HappyHelpers\dom\configurator\withTrimmedContents;
use function HappyHelpers\dom\configurator\withUtf8;
use function HappyHelpers\dom\configurator\withValidator;
use function HappyHelpers\dom\documentFromXmlString;
use function HappyHelpers\dom\locator\locateXsdSchemas;
use function HappyHelpers\dom\validator\chainedValidator;
use function HappyHelpers\dom\validator\xsdValidator;
use function HappyHelpers\iterables\map;

class WsdlFactory
{
    private WsdlLoaderInterface $loader;

    public function __construct(WsdlLoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @return [DOMDocument, XPath]
     */
    public function __invoke(string $wsdl): array
    {
        $document = documentFromXmlString(
            $this->loader->load($wsdl),
            pipe(
                withTrimmedContents(),
                withUtf8(),
                new MergeWsdlImports($this->loader, $wsdl),
                new MergeXsdImports($this->loader, $wsdl)
                /*withValidator(fn (DOMDocument $document) => chainedValidator(
                    xsdValidator(INTERNAL_XSDS.'/wsdl.xsd'),
                    ...map(
                        locateXsdSchemas($document),
                        fn ($schema) => xsdValidator($schema)
                    )
                ))*/
            )
        );
        $xpath = XPathProvider::provide($document);

        return [$document, $xpath];
    }
}
