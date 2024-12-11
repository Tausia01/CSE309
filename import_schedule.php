<?php
// Include PHPSpreadsheet library
require 'vendor/autoload.php'; // Make sure this is the correct path to your autoload file

use PhpOffice\PhpSpreadsheet\IOFactory;
ini_set('memory_limit', '2G'); // Set to 2GB

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file is uploaded
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        // Get the uploaded file's temp path
        $fileTmpPath = $_FILES['excel_file']['tmp_name'];
        
        // Get the semester start and end dates from the form
        $semesterStart = $_POST['semester_start'];
        $semesterEnd = $_POST['semester_end'];
        
        // Try to load the spreadsheet
        try {
            // Load the spreadsheet

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileTmpPath);
            $reader->setReadDataOnly(true); // Disable loading styles and unnecessary metadata
            $spreadsheet = $reader->load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();
            if (!$sheet) {
                die('Error: Could not load the active sheet. Please check the file.');
            }


            // Example: Extracting data from the first sheet
            $rowIndex = 5; // Starting from the 5th row (adjust as needed)
            $bookingData = [];

            // Get all merged cells
            $mergedCells = $sheet->getMergeCells();

            // Loop through the rows to get the data
                while ($rowIndex <= 160){ //&& $sheet->getCell('A' . $rowIndex)->getValue() !== '') {

                // Extract the data from the sheet
                $roomID = $sheet->getCell('H' . $rowIndex)->getValue();
                $roomCapacity = $sheet->getCell('I' . $rowIndex)->getValue();
                $faculty = $sheet->getCell('L' . $rowIndex)->getValue();
                $startTime = $sheet->getCell('M' . $rowIndex)->getValue();
                $endTime = $sheet->getCell('N' . $rowIndex)->getValue();
                    

                $day = $sheet->getCell('O' . $rowIndex)->getValue();

                // Store the data in an array
                $bookingData[] = [
                    'roomID' => $roomID,
                    'roomCapacity' => $roomCapacity,
                    'faculty' => $faculty,
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'day' => $day
                ];

                // Move to the next row
                $rowIndex++;
            }

            // Display extracted data (for testing purposes)
            echo "<h3>Extracted Schedule Data:</h3>";
            echo "<pre>";
            print_r($bookingData);
            echo "</pre>"; // Closing the <pre> tag for formatted output

        } catch (Exception $e) {
            echo 'Error loading file: ' . $e->getMessage();
        }
    } else {
        echo 'Error uploading file. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Schedule</title>
</head>
<body>
        <!-- Include Navbar -->
        <?php include 'admin_header.php'; ?>
    <h2>Upload an Excel File to Import Schedule</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="file">Select Excel File:</label>
        <input type="file" name="excel_file" id="file" accept=".xlsx, .xls" required>

        <label for="semester_start">Semester Start:</label>
        <input type="date" name="semester_start" id="semester_start" required>

        <label for="semester_end">Semester End:</label>
        <input type="date" name="semester_end" id="semester_end" required>

        <button type="submit">Upload and Process</button>
    </form>
</body>
</html>
