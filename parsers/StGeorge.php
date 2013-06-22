<?php

require_once __DIR__ ."/ParserAbstract.php";

class StGeorge extends ParserAbstract
{
    public $bankid  = "stgeorge";
    public $accid   = 1;
    public $acctype = "SAVINGS";


    /**
     * Checks if the CSV is in valid St George format.
     */
    public function isValid($columns, $csv)
    {
        $expected = array('Date', 'Description', 'Debit', 'Credit', 'Balance');

        return ($expected == $columns);
    }

    /**
     * Parses the CSV data into OFX format
     *
     */
    public function parse($columns, $csv)
    {
        $columns = array_map('strtolower', $columns);

        $ofx = new OfxFormatter($this);

        foreach ($csv as $transaction) {
            $transaction = array_combine($columns, $transaction);
            $ofx->addTransaction($transaction);
        }

        return $ofx->generate();
    }
}
