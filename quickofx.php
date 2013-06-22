#!/usr/bin/env php
<?php
/**
 * Define parsers
 */
$parsers = array('SimpleSavings', 'SimpleCredit', 'TabCredit');

foreach ($parsers as $key => $parser) {
    require_once __DIR__ ."/parsers/{$parser}.php";
}


/**
 * Check file exists
 */
if (!isset($_SERVER['argv'][1])
    || !is_file($_SERVER['argv'][1])
    || substr($_SERVER['argv'][1], -4) != ".csv") {
    die("Please specify a CSV file to parse.\n\n");
}


/**
 * Parse CSV
 */
$rawcsv  = explode("\n", file_get_contents($_SERVER['argv'][1]));
$columns = str_getcsv(array_shift($rawcsv));
$csv     = array();

foreach ($rawcsv as $row) {
    if (!empty($row)) {
        $csv[] = str_getcsv($row);
    }
}


/**
 * Loop parsers
 */
foreach ($parsers as $parserName) {

    /**
     * Create parser
     */
    $parser = new $parserName();


    /**
     * Check if valid CSV format and skip if not
     */
    if (!$parser->isValid($columns, $csv)) {
        continue;
    }


    /**
     * Parse CSV
     */
    echo "Format identified as '{$parserName}' format.\n";
    $ofx = $parser->parse($columns, $csv);


    /**
     * Save file
     */
    $file = substr($_SERVER['argv'][1], 0, -4).".ofx";
    file_put_contents($file, $ofx);


    /**
     * End script here.
     */
    echo "OFX created as: {$file}\n\n";
    exit;
}


/**
 * Error message
 */
echo "Unable to find compatible parser...\n\n";
