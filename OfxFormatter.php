<?php

class OfxFormatter
{
    protected $parser;
    protected $transactions = array();

    /**
     * Constructor
     */
    public function __construct($parser)
    {
        $this->parser = $parser;
    }


    /**
     * Adds a new transaction to the OFX file
     *
     */
    public function addTransaction($raw)
    {
        $transaction = array(
            'date'        => null,
            'description' => null,
            'type'        => 'DEBIT',
            'amount'      => 0,
            'balance'     => 0,
        );


        if (isset($raw['date']) && !empty($raw['date'])) {
            $transaction['date'] = $this->parseDate($raw['date']);
        }

        if (isset($raw['description']) && !empty($raw['description'])) {
            $transaction['description'] = $raw['description'];
        }

        if (isset($raw['debit']) && !empty($raw['debit'])) {
            $transaction['type']  = 'DEBIT';
            $transaction['amount'] = 0 - str_replace("$", "", $raw['debit']);

        } elseif (isset($raw['credit']) && !empty($raw['credit'])) {
            $transaction['type']  = 'CREDIT';
            $transaction['amount'] = str_replace("$", "", $raw['credit']);
        }

        if (isset($raw['balance']) && !empty($raw['balance'])) {
            $transaction['balance'] = $raw['balance'];
        }

        $this->transactions[] = $transaction;
    }


    /**
     * Generates the OFX file
     *
     */
    public function generate()
    {
        $transactions = "";
        foreach ($this->transactions as $row) {
            $transactions .= <<<OFX
<STMTTRN>
    <TRNTYPE>{$row['type']}
    <DTPOSTED>{$row['date']}
    <DTUSER>{$row['date']}
    <TRNAMT>{$row['amount']}
    <FITID>
    <MEMO>{$row['description']}
</STMTTRN>
OFX;
        }

        $datetime = date("YmdHis");
        $last     = count($this->transactions) - 1;

        $output = <<<OFX
OFXHEADER:100
DATA:OFXSGML
VERSION:102
SECURITY:NONE
ENCODING:USASCII
CHARSET:1252
COMPRESSION:NONE
OLDFILEUID:NONE
NEWFILEUID:NONE
<OFX>
    <SIGNONMSGSRSV1>
        <SONRS>
            <STATUS>
                <CODE>0
                <SEVERITY>INFO
            </STATUS>
            <DTSERVER>{$datetime}
            <LANGUAGE>ENG
        </SONRS>
    </SIGNONMSGSRSV1>
    <BANKMSGSRSV1>
        <STMTTRNRS>
            <TRNUID>1
            <STATUS>
                <CODE>0
                <SEVERITY>INFO
            </STATUS>
            <STMTRS>
                <CURDEF>AUD
                <BANKACCTFROM>
                    <BANKID>{$this->parser->bankid}
                    <ACCTID>{$this->parser->accid}
                    <ACCTTYPE>{$this->parser->acctype}
                </BANKACCTFROM>
                <BANKTRANLIST>
                    <DTSTART>{$this->transactions[0]['date']}000000
                    <DTEND>{$this->transactions[$last]['date']}000000
                    {$transactions}
                </BANKTRANLIST>
                <LEDGERBAL>
                    <BALAMT>{$this->transactions[$last]['balance']}
                    <DTASOF>{$this->transactions[$last]['date']}000000
                </LEDGERBAL>
                <AVAILBAL>
                    <BALAMT>{$this->transactions[$last]['balance']}
                    <DTASOF>{$this->transactions[$last]['date']}000000
                </AVAILBAL>
            </STMTRS>
        </STMTTRNRS>
    </BANKMSGSRSV1>
</OFX>
OFX;

        return $output;
    }

    /**
     * Parses dates into a useful format
     *
     */
    protected function parseDate($date)
    {
        // DD/MM/YYYY
        if (preg_match("%\d\d/\d\d/\d\d\d\d%", $date)) {
            return substr($date, 6).substr($date, 3, 2).substr($date, 0, 2);
        }

        return $date;
    }
}
