<?php

require_once __DIR__ ."/ParserAbstract.php";

abstract class SimpleAbstract extends ParserAbstract
{
    protected $expectedColumns = array();

    /**
     * Checks if the CSV is in valid format.
     */
    public function isValid($columns, $csv)
    {
        return ($columns == $this->expectedColumns);
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
