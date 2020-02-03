<?php
require_once 'csv-edit.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>YNAB CSV Converter</title>
</head>

<body>
    <header>
        <h1>YNAB CSV Converter</h1>
    </header>
    <div class="file-uploader">
        <form action="upload.php" method="post" enctype="multipart/form-data">
            Select CSV file to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload CSV" name="submit">
        </form>
    </div>
    <?php $publicOutput->displayRawData($finalisedData); ?>
</body>

</html>