<?php

use Phpro\SoapClient\CodeGenerator\Config\Config;
use Phpro\SoapClient\CodeGenerator\Rules;
use Phpro\SoapClient\CodeGenerator\Assembler;
use Phpro\SoapClient\Soap\Driver\ExtSoap\AbusedClient;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapDecoder;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEncoder;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;
use Phpro\SoapClient\Soap\Driver\ExtSoap\Generator\DummyMethodArgumentsGenerator;
use Phpro\SoapClient\Soap\Driver\ExtSoap\Handler\ExtSoapClientHandle;
use Phpro\SoapClient\Soap\Engine\Engine;
use Phpro\SoapClient\Soap\Engine\Metadata\LazyInMemoryMetadata;
use WsdlTools\Driver\ConfigurableDriver;
use WsdlTools\Metadata\WsdlMetadataProvider;
use WsdlTools\Wsdl;


$options = ExtSoapOptions::defaults($wsdlFile = __DIR__.'/../dumpservices.test.wsdl')->disableWsdlCache();
$wsdl = Wsdl::fromFile($wsdlFile);
$metadata = new LazyInMemoryMetadata(new WsdlMetadataProvider($wsdl));
$client = AbusedClient::createFromOptions($options);

$driver = new ConfigurableDriver(
    new ExtSoapEncoder($client),
    new ExtSoapDecoder($client, new DummyMethodArgumentsGenerator($metadata)),
    $metadata
);
$engine = new Engine($driver, new ExtSoapClientHandle($client));

return Config::create()
     ->setEngine($engine)
     ->setTypeDestination('generated/type')
     ->setTypeNamespace('App\\Type')
     ->setClientDestination('generated/client')
     ->setClientName('Client')
     ->setClientNamespace('App\\Client')
     ->setClassmapDestination('generated/classmap')
     ->setClassmapName('Classmap')
     ->setClassmapNamespace('App\\Classmap')
     ->addRule(new Rules\AssembleRule(new Assembler\GetterAssembler(new Assembler\GetterAssemblerOptions())))
     ->addRule(new Rules\AssembleRule(new Assembler\ImmutableSetterAssembler()))
;
