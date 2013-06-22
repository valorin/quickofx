<?php

require_once __DIR__ ."/../OfxFormatter.php";

abstract class ParserAbstract
{
    abstract public function isValid($columns, $csv);

    abstract public function parse($columns, $csv);
}
