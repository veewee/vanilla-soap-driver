<?php

use GoetasWebservices\XML\WSDLReader\DefinitionsReader;

require 'vendor/autoload.php';

$reader = new DefinitionsReader();
$definitions = $reader->readFile("test.wsdl");

$schema = $definitions->getSchema();
$ports = $definitions->getAllPortTypes();
$bindings = $definitions->getBindings();


var_dump($schema->getTypes());



