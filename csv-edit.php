<?php
require_once 'include/class-process-csv-file.php';
require_once 'include/class-modify-csv-data.php';
require_once 'include/class-public-display.php';

$processCSV = new ProcessCSVFile();

// Modify data ready for output
$modifyData = new ModifyCSVData();
$finalisedData = $modifyData->modifyCSVResults($processCSV->readCSVFile());

// Render the user interface
$publicOutput = new PublicDisplay();

// Output the final CSV file
$processCSV->createCSV($finalisedData, $modifyData->getColumnTemplate());
