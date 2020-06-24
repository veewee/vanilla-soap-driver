<?php

declare(strict_types=1);

namespace WsdlTools\Driver;

use Phpro\SoapClient\Soap\Engine\DecoderInterface;
use Phpro\SoapClient\Soap\Engine\DriverInterface;
use Phpro\SoapClient\Soap\Engine\EncoderInterface;
use Phpro\SoapClient\Soap\Engine\Metadata\MetadataInterface;
use Phpro\SoapClient\Soap\HttpBinding\SoapRequest;
use Phpro\SoapClient\Soap\HttpBinding\SoapResponse;

class ConfigurableDriver implements DriverInterface
{

    /**
     * @var DecoderInterface
     */
    private DecoderInterface $decoder;
    /**
     * @var EncoderInterface
     */
    private EncoderInterface $encoder;
    /**
     * @var MetadataInterface
     */
    private MetadataInterface $metadata;

    public function __construct(EncoderInterface $encoder, DecoderInterface $decoder, MetadataInterface $metadata)
    {
        $this->decoder = $decoder;
        $this->encoder = $encoder;
        $this->metadata = $metadata;
    }


    public function decode(string $method, SoapResponse $response)
    {
        return $this->decoder->decode($method, $response);
    }

    public function encode(string $method, array $arguments): SoapRequest
    {
        return $this->encoder->encode($method, $arguments);
    }

    public function getMetadata(): MetadataInterface
    {
        return $this->metadata;
    }
}
