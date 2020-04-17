<?php

namespace WsdlTools;

function parseNamespacedString(string $namespaced): array
{
    if (strpos($namespaced, ':') === false) {
        return ['', $namespaced];
    }

    return explode(':', $namespaced, 2);
}

function getNameFromNamespacedString(string $namespaced): string
{
    return parseNamespacedString($namespaced)[1];
}
