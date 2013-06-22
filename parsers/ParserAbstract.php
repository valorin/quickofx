<?php

require_once __DIR__ ."/../OfxFormatter.php";

abstract class ParserAbstract
{
    public $bankid  = "OFXFormatter";
    public $accid   = 1;
    public $acctype = "SAVINGS";

    abstract public function isValid($columns, $csv);

    abstract public function parse($columns, $csv);
}
