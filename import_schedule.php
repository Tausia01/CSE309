<?php
// Include PHPSpreadsheet library
require 'vendor/autoload.php';
include 'db_config.php'; // Include your database connection file

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

ini_set('memory_limit', '2G'); // Set to 2GB

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $fileTmpPath = $_FILES['excel_file']['tmp_name'];
        $semesterStart = $_POST['semester_start'];
        $semesterEnd = $_POST['semester_end'];

        try {
            // Load the spreadsheet
            $reader = IOFactory::createReaderForFile($fileTmpPath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fileTmpPath);
            $sheet = $spreadsheet->getActiveSheet();

            if (!$sheet) {
                die('Error: Could not load the active sheet. Please check the file.');
            }

            $rowIndex = 5; // Start from row 5
            $conn->autocommit(false); // Disable autocommit for batch processing

            while ($rowIndex <= 10) {
                $roomID = $sheet->getCell('H' . $rowIndex)->getValue();
                $roomCapacity = $sheet->getCell('I' . $rowIndex)->getValue();

                $facultyData = $sheet->getCell('L' . $rowIndex)->getValue();
                if (strpos($facultyData, '-') !== false) {
                    list($facultyID, $facultyName) = array_map('trim', explode('-', $facultyData, 2));
                } else {
                    $facultyID = null;
                    $facultyName = $facultyData;
                }

                $startTime = $sheet->getCell('M' . $rowIndex)->getValue();
                $endTime = $sheet->getCell('N' . $rowIndex)->getValue();
                $dayPattern = $sheet->getCell('O' . $rowIndex)->getValue(); // 'MW' or 'ST'

                try {
                    // Convert Excel time to H:i:s format
                    $startTimeFormatted = Date::excelToDateTimeObject($startTime)->format('H:i:00');
                    $endTimeFormatted = Date::excelToDateTimeObject($endTime)->format('H:i:00');
                } catch (Exception $e) {
                    echo "Skipping invalid time format at row $rowIndex: " . $e->getMessage() . "<br>";
                    $rowIndex++;
                    continue;
                }

                // Skip invalid rows
                if (!$roomID || !$facultyID || strtotime($startTimeFormatted) >= strtotime($endTimeFormatted)) {
                    echo "Skipping invalid row: $rowIndex<br>";
                    $rowIndex++;
                    continue;
                }

                // Insert room if not exists
                $stmt = $conn->prepare("INSERT IGNORE INTO rooms (name, capacity) VALUES (?, ?)");
                $stmt->bind_param('si', $roomID, $roomCapacity);
                $stmt->execute();

                // Retrieve room ID for booking insertion
                $roomIDQuery = $conn->prepare("SELECT id FROM rooms WHERE name = ?");
                $roomIDQuery->bind_param('s', $roomID);
                $roomIDQuery->execute();
                $roomResult = $roomIDQuery->get_result();

                if ($roomResult->num_rows > 0) {
                    $roomRow = $roomResult->fetch_assoc();
                    $roomDBID = $roomRow['id']; // Use this as room_id
                } else {
                    echo "Error: Room '$roomID' could not be found or inserted.<br>";
                    $rowIndex++;
                    continue;
                }

                // Insert faculty if not exists
                $stmt = $conn->prepare("INSERT IGNORE INTO faculty (id, name) VALUES (?, ?)");
                $stmt->bind_param('is', $facultyID, $facultyName);
                $stmt->execute();

                // Verify faculty ID exists
                $facultyIDQuery = $conn->prepare("SELECT id FROM faculty WHERE id = ?");
                $facultyIDQuery->bind_param('i', $facultyID);
                $facultyIDQuery->execute();
                $facultyResult = $facultyIDQuery->get_result();

                if ($facultyResult->num_rows === 0) {
                    echo "Error: Faculty ID '$facultyID' does not exist or was not inserted.<br>";
                    $rowIndex++;
                    continue; // Skip this row
                }

                // Define the day mapping
                $dayMapping = [
                    'MW' => ['Monday', 'Wednesday'],
                    'ST' => ['Sunday', 'Tuesday']
                ];

                // Start with the semester start date
                $currentDate = new DateTime($semesterStart);
                $semesterEndDate = new DateTime($semesterEnd);

                while ($currentDate <= $semesterEndDate) {
                    $currentDayName = $currentDate->format('l'); // Get the day name (e.g., 'Monday')

                    // Check if the current day matches the pattern
                    if (in_array($currentDayName, $dayMapping[$dayPattern])) {
                        // Create booking for this date
                        $startTimeForDate = $currentDate->format('Y-m-d') . ' ' . $startTimeFormatted; // Add time
                        $endTimeForDate = $currentDate->format('Y-m-d') . ' ' . $endTimeFormatted;     // Add time

                        // Insert booking
                        $stmt = $conn->prepare("INSERT INTO bookings (room_id, faculty_id, start_time, end_time, status) VALUES (?, ?, ?, ?, 'Confirmed')");
                        $stmt->bind_param('iiss', $roomDBID, $facultyID, $startTimeForDate, $endTimeForDate);
                        $stmt->execute();
                    }

                    // Move to the next day
                    $currentDate->modify('+1 day');
                }

                $rowIndex++;
            }

            $conn->commit(); // Commit all changes
            echo "Data imported and processed successfully!";
        } catch (Exception $e) {
            $conn->rollback(); // Rollback changes on error
            echo 'Error processing file: ' . $e->getMessage();
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
