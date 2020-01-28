<?php

/**
 * Function to read the CSV file and return an array of results
 *
 * @param array $csv_file_handle
 * @return void
 */
function parse_csv_file($csv_file_handle)
{
    $csv = array();
    $rowcount = 0;
    if (($handle = fopen($csv_file_handle, "r")) !== FALSE) {
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
        error_log("csvreader: Could not read CSV \"$csv_file_handle\"");
        return null;
    }
    return $csv;
}

/**
 * Function to rearrange the data columns to be compatible with YNAB
 *
 * @param array $array_to_change
 * @param array $array_template
 * @return void
 */
function rearrangeCSVColumns($array_to_change, $array_template)
{
    // Rearrange the CSV Data Columns
    $template = array_flip($array_template);
    foreach ($array_to_change as &$x) {
        //replace values in the template
        $x = array_replace($template, $x);
    }

    return $array_to_change;
}

/**
 * Function to modify the CSV results into the YNAB-friendly format
 *
 * @param array $csvResults
 * @param array $array_template
 * @return void
 */
function modifyCSVResults($csvResults, $array_template)
{
    $csvData = array();

    // Iterate through the CSV array
    $keys = array_keys($csvResults);

    $csvData = array();

    for ($i = 0; $i < count($csvResults); $i++) {

        //Add a memo field
        $csvResults[$i]['Memo'] = '';

        foreach ($csvResults[$keys[$i]] as $key => $value) {

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

    // New column layout template
    // $array_template = array('Date', 'Description', 'Memo', 'Amount', 'Balance');

    $csvData = rearrangeCSVColumns($csvData, $array_template);

    return $csvData;
}
