<?php

require_once __DIR__ ."/../OfxFormatter.php";

abstract class ParserAbstract
{
    public $bankid  = "OFXFormatter";
    public $accid   = 1;
    public $acctype = "SAVINGS";
    public $balance = null;

    public function __construct($balance = null)
    {
        $this->balance = $balance;
    }

    abstract public function isValid($columns, $csv);

    abstract public function parse($columns, $csv);
}
