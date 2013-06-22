<?php

require_once __DIR__ ."/SimpleAbstract.php";

class SimpleSavings extends SimpleAbstract
{
    protected $expectedColumns = array('Date', 'Description', 'Debit', 'Credit', 'Balance');
}
