<?php
$file_handle = fopen('csv/widgets.csv', 'r') ;
$html = '<div>';

while (!feof($file_handle)) {
    $line_of_text = fgetcsv($file_handle, 1024);

    //print_r($line_of_text);
    $html .= $line_of_text[0].$line_of_text[1].$line_of_text[2]."<br>";
}
fclose($file_handle);

print $html;
