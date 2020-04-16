<?php

$reader = new XMLReader();
$reader->setParserProperty(XMLReader::VALIDATE, true);
$reader->open('test.xml');

while ($reader->read()) {
    switch ($reader->nodeType) {
        case XMLReader::ELEMENT:
        case XMLReader::ELEMENT:
        case XMLReader::END_ELEMENT:
    }
}

$reader->close();
