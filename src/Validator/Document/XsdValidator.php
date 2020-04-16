<?php

declare(strict_types=1);

namespace WsdlTools\Validator\Document;

class XsdValidator
{
    const TYPE_NONE = 'none';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    const TYPE_FATAL = 'fatal';

    public function validate(\DOMDocument $document, string $schemaPath): \Generator
    {
        $internalLogging = $this->useInternalXmlLoggin(true);

        $document->schemaValidate($schemaPath);
        yield from $this->collectXmlErrors();

        $this->useInternalXmlLoggin($internalLogging);
    }

    private function useInternalXmlLoggin(bool $useInternalErrors = false): bool
    {
        return libxml_use_internal_errors($useInternalErrors);
    }

    private function collectXmlErrors(): \Generator
    {
        yield from array_map(
            fn (\LibXMLError $error): string => $this->formatError($error),
            libxml_get_errors()
        );
        $this->flushXmlErrors();
    }

    /**
     * Make sure the libxml errors are flushed and won't be occurring again.
     */
    private function flushXmlErrors(): void
    {
        libxml_clear_errors();
    }

    private function formatError(\LibXMLError $error): string
    {
        $type = self::TYPE_NONE;
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $type = self::TYPE_WARNING;
                break;
            case LIBXML_ERR_FATAL:
                $type = self::TYPE_FATAL;
                break;
            case LIBXML_ERR_ERROR:
                $type = self::TYPE_ERROR;
                break;
        }

        return sprintf(
            '[%s] %s: %s (%s) on line %s,%s',
            strtoupper($type),
            $error->file,
            $error->message,
            $error->code ?: 0,
            $error->line,
            $error->column ?: 0
        );
    }
}
