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
    private $fileHandle = '';

    public function setFileHandle($location)
    {
        $this->fileHandle = $location;
    }

    public function getFileHandle()
    {
        return $this->fileHandle;
    }

    /**
     * Read a CSV file and return an array of results
     *
     * @return $csvData
     */
    public function readCSVFile()
    {
        $this->setFileHandle('csv/TransactionHistory.csv');
        $csvData = array();
        $rowcount = 0;
        if (($handle = fopen($this->getFileHandle(), "r")) !== FALSE) {
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
            error_log("csvreader: Could not read CSV \"$this->getFileHandle()\"");
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
        $this->setFileHandle('csv/testoutput.csv');

        // Create the updated CSV
        $outputFileHandle = fopen($this->getFileHandle(), "w");

        // Add the header row
        array_unshift($csvData, $columnTemplate);

        foreach ($csvData as $csvOutput) {
            fputcsv($outputFileHandle, $csvOutput);
        }
    }
}
