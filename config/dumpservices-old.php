<?php

use Phpro\SoapClient\CodeGenerator\Config\Config;
use Phpro\SoapClient\CodeGenerator\Rules;
use Phpro\SoapClient\CodeGenerator\Assembler;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;

return Config::create()
     ->setEngine($engine = \Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory::fromOptions(
         ExtSoapOptions::defaults($wsdlFile = __DIR__.'/../dumpservices.test.wsdl')->disableWsdlCache()
     ))
     ->setTypeDestination('generated-old/type')
     ->setTypeNamespace('App\\Type')
     ->setClientDestination('generated-old/client')
     ->setClientName('Client')
     ->setClientNamespace('App\\Client')
     ->setClassmapDestination('generated-old/classmap')
     ->setClassmapName('Classmap')
     ->setClassmapNamespace('App\\Classmap')
     ->addRule(new Rules\AssembleRule(new Assembler\GetterAssembler(new Assembler\GetterAssemblerOptions())))
     ->addRule(new Rules\AssembleRule(new Assembler\ImmutableSetterAssembler()))
;
