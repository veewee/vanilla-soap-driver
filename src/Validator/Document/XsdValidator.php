<?php

declare(strict_types=1);

namespace WsdlTools\Validator\Document;

use Symfony\Component\Filesystem\Filesystem;

class XsdValidator
{
    const TYPE_NONE = 'none';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    const TYPE_FATAL = 'fatal';

    private Filesystem $filesystem;
    private string $xsdPath;

    public function __construct(Filesystem $filesystem, string $xsdPath)
    {
        $this->xsdPath = $xsdPath;
        $this->filesystem = $filesystem;
    }

    public function validate(\DOMDocument $document, string $schemaPath): \Generator
    {
        $this->loadInternalXsdsFirst();
        $internalLogging = $this->useInternalXmlLoggin(true);

        @$document->schemaValidate($this->buildLocalXsdPath($schemaPath));
        yield from $this->collectXmlErrors();

        $this->useInternalXmlLoggin($internalLogging);
    }

    /**
     * Make sure to load the internal XSD's first.
     * Otherwise NET calls are made which slow the process down by a lot!
     */
    private function loadInternalXsdsFirst(): void
    {
        libxml_set_external_entity_loader(
            function (?string $public, string $system, array $context): string {
                // Fetch local from filesystem
                if($this->filesystem->exists($system)){
                    return $system;
                }

                // Check if remote XSDs are already available locally:
                if (stripos($system, 'http') === 0) {
                    $path = parse_url($system, PHP_URL_PATH);
                    $baseName = basename($path);

                    if ($this->filesystem->exists($localPath = $this->buildLocalXsdPath($baseName))) {
                        return $localPath;
                    }
                }

                // Cache locally for faster load times the second time.
                $cached_file= tempnam(sys_get_temp_dir(), md5($system));
                if (is_file($cached_file)) {
                    return $cached_file;
                }
                copy($system,$cached_file);
                return $cached_file;
            }
        );
    }

    private function buildLocalXsdPath(string $xsdFile): string
    {
        return $this->xsdPath . DIRECTORY_SEPARATOR . $xsdFile;
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
