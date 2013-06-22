<?php

require_once __DIR__ ."/SimpleAbstract.php";

class SimpleCredit extends SimpleAbstract
{
    public $acctype = "CREDIT";

    protected $expectedColumns = array('Date', 'Description', 'Debit', 'Credit');
}
