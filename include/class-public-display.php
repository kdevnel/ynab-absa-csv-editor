<?php

/**
 * This class is responsible for displaying user-facing content
 */
class PublicDisplay
{
    /**
     * Output raw data from an array
     *
     * @param array $data
     * @return void
     */
    public function displayRawData($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
