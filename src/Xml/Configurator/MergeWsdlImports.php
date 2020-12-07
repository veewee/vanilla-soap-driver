<?php

declare(strict_types=1);

namespace WsdlTools\Xml\Configurator;

use DOMDocument;
use DOMElement;
use Phpro\SoapClient\Wsdl\Loader\WsdlLoaderInterface;
use Webmozart\Assert\Assert;
use WsdlTools\Xml\Paths\IncludePathBuilder;
use WsdlTools\Xml\XPath\XpathProvider;
use function HappyHelpers\callables\pipe;
use function HappyHelpers\dom\configurator\withTrimmedContents;
use function HappyHelpers\dom\configurator\withUtf8;
use function HappyHelpers\dom\documentFromXmlString;
use function HappyHelpers\dom\manipulator\replaceByExternalNode;

final class MergeWsdlImports
{
    private WsdlLoaderInterface $loader;
    private string $currentLocation;

    public function __construct(WsdlLoaderInterface $loader, string $currentLocation)
    {
        $this->loader = $loader;
        $this->currentLocation = $currentLocation;
    }

    public function __invoke(DOMDocument $document): DOMDocument
    {
        $xpath = XPathProvider::provide($document);
        $imports = $xpath->query('wsdl:import');

        foreach ($imports as $import) {
            Assert::isInstanceOf($import, DOMElement::class);
            $location = (new IncludePathBuilder())($import->getAttribute('location'), $this->currentLocation);

            // TODO : $import->getAttribute('namespace')
            // @see http://itdoc.hitachi.co.jp/manuals/3020/30203Y2310e/EY230669.HTM#ID01496
            // -> targetNamespace

            replaceByExternalNode($import, documentFromXmlString(
                $this->loader->load($location),
                pipe(
                    withUtf8(),
                    withTrimmedContents(),
                    new MergeWsdlImports($this->loader, $location)
                    // TODO : with Validator WSDL?
                )
            ));
        }

        return $document;
    }
}
