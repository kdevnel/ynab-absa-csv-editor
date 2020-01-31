<?php

/**
 * The class that is responsible for reading and writing the CSV file data
 */
class ProcessCSVFile
{
    /**
     * The location of source CSV file
     *
     * @var string
     */
    public $fileHandle = 'csv/TransactionHistory.csv';

    /**
     * Read a CSV file and return an array of results
     *
     * @return $csvData
     */
    public function readCSVFile()
    {
        $csvData = array();
        $rowcount = 0;
        if (($handle = fopen($this->fileHandle, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            $header_colcount = count($header);
            while (($row = fgetcsv($handle)) !== FALSE) {
                $row_colcount = count($row);
                if ($row_colcount == $header_colcount) {
                    $entry = array_combine($header, $row);
                    $csvData[] = $entry;
                } else {
                    error_log("csvreader: Invalid number of columns at line " . ($rowcount + 2) . " (row " . ($rowcount + 1) . "). Expected=$header_colcount Got=$row_colcount");
                    return null;
                }
                $rowcount++;
            }
            fclose($handle);
        } else {
            error_log("csvreader: Could not read CSV \"$this->fileHandle\"");
            return null;
        }
        return $csvData;
    }

    /**
     * The function to create the newly formatted CSV file
     *
     * @param array $csvData
     * @param array $columnTemplate
     * @return void
     */
    public function createCSV($csvData, $columnTemplate)
    {
        // Create the updated CSV
        $file_handle = fopen("csv/testoutput.csv", "w");

        // Add the header row
        array_unshift($csvData, $columnTemplate);

        foreach ($csvData as $csvOutput) {
            fputcsv($file_handle, $csvOutput);
        }
    }
}
