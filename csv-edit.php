<?php

// Function to read the CSV file and return an array of results
function parse_csv_file($csvfile)
{
    $csv = array();
    $rowcount = 0;
    if (($handle = fopen($csvfile, "r")) !== FALSE) {
        $header = fgetcsv($handle);
        $header_colcount = count($header);
        while (($row = fgetcsv($handle)) !== FALSE) {
            $row_colcount = count($row);
            if ($row_colcount == $header_colcount) {
                $entry = array_combine($header, $row);
                $csv[] = $entry;
            } else {
                error_log("csvreader: Invalid number of columns at line " . ($rowcount + 2) . " (row " . ($rowcount + 1) . "). Expected=$header_colcount Got=$row_colcount");
                return null;
            }
            $rowcount++;
        }
        fclose($handle);
    } else {
        error_log("csvreader: Could not read CSV \"$csvfile\"");
        return null;
    }
    return $csv;
}

$file_handle = 'csv/TransactionHistory.csv';

// Save results of parse_csv_file to a variable
$results = parse_csv_file($file_handle);

// Iterate through the CSV array
$keys = array_keys($results);
for ($i = 0; $i < count($results); $i++) {
    foreach ($results[$keys[$i]] as $key => $value) {

        // Modify the date to a compatible format
        if ($key == 'Date') {
            $value = date('d-m-Y', strtotime($value));
        }

        // Modify the descriptions to something more friendly
        if ($key == 'Description') {
            $searchString = 'POS PURCHASE';
            if (strpos($value, $searchString) !== false) {
                $description = explode(')', $value);
                echo '<pre>';
                print_r($description);
                echo '</pre>';
            }
        }

        // Output the end result
        echo $key . ' : ' . $value . '<br>';
    }
    echo '<br>';
}
