#!/usr/bin/env php
<?php

require dirname(__DIR__).'/vendor/autoload.php';

$application = new \Symfony\Component\Console\Application('wsdl-tools', '1.0.0');
$application->addCommands([
    new \WsdlTools\Console\Command\ImportWsdlCommand(),
    new \WsdlTools\Console\Command\ListDuplicateTypesCommand(),
    new \WsdlTools\Console\Command\ListIteratorsTypesCommand(),
    new \WsdlTools\Console\Command\ParseMetadataCommand(),
    new \WsdlTools\Console\Command\ValidateWsdlCommand(),
]);

$application->run();
