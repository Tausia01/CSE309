

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
