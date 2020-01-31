<?php
require_once 'include/class-process-csv-file.php';
require_once 'include/class-modify-csv-data.php';
require_once 'include/class-public-display.php';

$processCSV = new ProcessCSVFile();

// New column layout template
$array_template = array('Date', 'Description', 'Memo', 'Amount', 'Balance');

// Modify data ready for output
$modifyData = new ModifyCSVData();
$finalisedData = $modifyData->modifyCSVResults($processCSV->readCSVFile(), $array_template);

// Render the user interface
$publicOutput = new PublicDisplay();
$publicOutput->displayRawData($finalisedData);

// Output the final CSV file
$processCSV->createCSV($finalisedData, $array_template);
