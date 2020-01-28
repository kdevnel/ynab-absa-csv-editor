<?php
require_once 'functions.php';

$csv_file_handle = 'csv/TransactionHistory.csv';

// Save results of parse_csv_file to a variable
$results = parse_csv_file($csv_file_handle);

// New column layout template
$array_template = array('Date', 'Description', 'Memo', 'Amount', 'Balance');

// Change the CSV data
$csvData = modifyCSVResults($results, $array_template);

// Rearrange CSV columns based on $array_template
// $csvData = rearrangeCSVColumns($csvData, $array_template);

echo '<pre>';
print_r($csvData);
echo '</pre>';

// Create the updated CSV
$file_handle = fopen("csv/testoutput.csv", "w");

// Add the header row
array_unshift($csvData, $array_template);

foreach ($csvData as $csvOutput) {
    fputcsv($file_handle, $csvOutput);
}
