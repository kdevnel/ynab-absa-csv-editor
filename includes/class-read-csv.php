<?php
class Read_CSV
{
    private $file_handle;

    public function __construct($file_handle)
    {
        $this->file_handle = $file_handle;
    }

    public function get_file_handle()
    {
        return $this->file_handle;
    }

    public function set_file_handle($file_handle)
    {
        $this->file_handle = $file_handle;
    }

    public function read_csv($file_handle)
    {
        while (!feof($file_handle)) {
            $line_of_text = fgetcsv($file_handle);

            print_r($line_of_text);

            $html .= $line_of_text[0] . $line_of_text[1] . $line_of_text[2] . $line_of_text[3] . "<br>";
        }
        fclose($file_handle);

        return $html;
    }
}
