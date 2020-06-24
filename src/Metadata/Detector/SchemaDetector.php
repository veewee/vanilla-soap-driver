<?php

declare(strict_types=1);

namespace WsdlTools\Metadata\Detector;

use GoetasWebservices\XML\XSDReader\Schema\Schema;
use GoetasWebservices\XML\XSDReader\SchemaReader;
use WsdlTools\Iterator\Schema\SchemaIterator;
use WsdlTools\Wsdl;

class SchemaDetector
{
    public function detect(Wsdl $wsdl): Schema
    {
        $reader = new SchemaReader();

        return $reader->readNodes([...new SchemaIterator($wsdl)]);
    }
}
