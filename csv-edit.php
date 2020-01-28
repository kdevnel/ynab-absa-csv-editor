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

$csvData = array();

for ($i = 0; $i < count($results); $i++) {

    //Add a memo field
    $results[$i]['Memo'] = '';

    foreach ($results[$keys[$i]] as $key => $value) {

        $value = trim(preg_replace('/\s+/', ' ', $value));
        switch ($key) {
            case 'Date':
                // Modify the date to a compatible format
                $value = date('d-m-Y', strtotime($value));
                break;
            case 'Description':
                // $searchString = 'POS PURCHASE';
                $searchStrings = array(
                    'POS PURCHASE',
                    'OVERSEAS PURCHASE'
                );
                foreach ($searchStrings as $searchString) {
                    switch ($searchString) {
                        case 'POS PURCHASE':
                            if (strpos($value, $searchString) !== false) {
                                $description = explode(')', $value);
                                $value = trim($description[1]);
                                $memo = $description[0] . ')';
                            }
                            break;
                        case 'OVERSEAS PURCHASE':
                            if (strpos($value, $searchString) !== false) {
                                $description = explode(')', $value);
                                $value = trim($description[2]);
                                $memo = $description[0] . ')' . $description[1] . ')';
                            }
                            break;
                    }
                }
                break;
            case 'Memo':
                $value = trim($memo);
                break;
        }



        // Output the end result
        // echo $key . ' : ' . $value . '<br>';
        $csvData[$i][$key] = $value;
    }
    // echo '<br>';
}

// Rearrange the CSV Data Columns
$array_template = array('Date', 'Description', 'Memo', 'Amount', 'Balance');
$template = array_flip($array_template);
foreach ($csvData as &$x) {
    //replace values in the template
    $x = array_replace($template, $x);
}

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
