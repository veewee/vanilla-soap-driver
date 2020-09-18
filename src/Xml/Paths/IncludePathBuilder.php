<?php

declare(strict_types=1);

namespace WsdlTools\Xml\Paths;

use League\Uri\Uri;
use League\Uri\UriModifier;
use League\Uri\UriResolver;

/**
 * @psalm-immutable
 */
final class IncludePathBuilder
{
    /**
     * @psalm-pure
     */
    public function __invoke(string $relativePath, string $fromFile): string
    {
        return UriModifier::removeEmptySegments(
            UriModifier::removeDotSegments(
                UriResolver::resolve(
                    Uri::createFromString($relativePath),
                    Uri::createFromString($fromFile)
                )
            )
        )->__toString();
    }
}
