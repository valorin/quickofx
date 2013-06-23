<?php

require_once __DIR__ ."/ParserAbstract.php";

class TabCredit extends ParserAbstract
{
    public $acctype = "CREDIT";


    /**
     * Checks if the CSV is in valid St George format.
     */
    public function isValid($columns, $csv)
    {
        $columns  = implode(",", $columns);
        $expected = "Date\tDescription\tDebit\tCredit";

        return ($expected == $columns);
    }

    /**
     * Parses the CSV data into OFX format
     *
     */
    public function parse($columns, $csv)
    {
        $columns = explode("\t", strtolower(implode("", $columns)));

        $ofx = new OfxFormatter($this);

        foreach ($csv as $transaction) {
            $transaction = explode("\t", implode("", $transaction));

            if (count($transaction) == 3) {
                $transaction[] = 0;
            }

            $transaction = array_map("trim", $transaction);
            $transaction = array_combine($columns, $transaction);

            $ofx->addTransaction($transaction);
        }

        return $ofx->generate();
    }
}
