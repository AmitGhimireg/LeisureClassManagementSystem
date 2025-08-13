<?php
// Set up error reporting to catch any potential issues early
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Enable mysqli to throw exceptions on errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Correct the path to the vendor directory
require __DIR__ . '/../vendor/autoload.php';

// Import PhpSpreadsheet classes
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Include necessary files and database connection
// Assuming partial-admin/navbar.php includes the database connection ($conn)
include('partial-admin/navbar.php');
include('partial-admin/login-check.php');

try {
    // Get start and end dates from URL, sanitize, and validate
    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

    // Validate date format to prevent SQL injection and errors
    if (!DateTime::createFromFormat('Y-m-d', $start_date) || !DateTime::createFromFormat('Y-m-d', $end_date)) {
        throw new Exception("Invalid date format provided.");
    }

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator("Your Application")
        ->setLastModifiedBy("Your Application")
        ->setTitle("Teacher Attendance Report")
        ->setSubject("Teacher Attendance Report")
        ->setDescription("Attendance report generated for a specific period.");

    // Set the title of the spreadsheet
    $sheet->setCellValue('B1', 'Teacher Attendance Report');
    $sheet->mergeCells('B1:G1');
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Set the date range
    $sheet->setCellValue('B2', 'Report generated for the period: ' . $start_date . ' to ' . $end_date);
    $sheet->mergeCells('B2:G2');
    $sheet->getStyle('B2')->getFont()->setBold(true);
    $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $currentRow = 4; // Start writing data from row 4

    // Use prepared statements for safer queries
    $sql_teachers = "SELECT teach_id, full_name FROM teachers WHERE role='teacher' ORDER BY full_name ASC";
    $res_teachers = mysqli_query($conn, $sql_teachers);

    if (mysqli_num_rows($res_teachers) > 0) {
        while ($teacher = mysqli_fetch_assoc($res_teachers)) {
            $teacher_id = $teacher['teach_id'];
            $teacher_name = htmlspecialchars($teacher['full_name']);

            // Add teacher name as a sub-heading
            $sheet->setCellValue('B' . $currentRow, 'Teacher Name: ' . $teacher_name);
            $sheet->mergeCells('B' . $currentRow . ':F' . $currentRow);
            $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('B' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF0F0F0');
            $currentRow++;

            // Calculate attendance summary
            $stmt_count = $conn->prepare("SELECT COUNT(*) AS total_days,
                                                SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) AS present_count,
                                                SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) AS absent_count
                                         FROM attendance
                                         WHERE teacher_id = ? AND date BETWEEN ? AND ?");
            $stmt_count->bind_param("sss", $teacher_id, $start_date, $end_date);
            $stmt_count->execute();
            $result_count = $stmt_count->get_result();
            $summary = $result_count->fetch_assoc();

            // Set summary row
            $summaryText = 'Summary: Present - ' . ($summary['present_count'] ?? 0) . ', Absent - ' . ($summary['absent_count'] ?? 0) . ', Total Days - ' . ($summary['total_days'] ?? 0);
            $sheet->setCellValue('B' . $currentRow, $summaryText);
            $sheet->mergeCells('B' . $currentRow . ':H' . $currentRow); // Merge cells to accommodate the full text
            $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
            $currentRow++;

            $currentRow++; // Add a blank row for spacing

            // Set table headers
            $headerStartRow = $currentRow;
            $sheet->setCellValue('B' . $currentRow, 'Date');
            $sheet->setCellValue('C' . $currentRow, 'Status');
            $sheet->getStyle('B' . $currentRow . ':D' . $currentRow)->getFont()->setBold(true);
            $sheet->getStyle('B' . $currentRow . ':D' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9E1F2');
            $currentRow++;

            // Use prepared statements for the attendance query to prevent SQL injection
            $stmt = $conn->prepare("SELECT date, status, reason FROM attendance WHERE teacher_id = ? AND date BETWEEN ? AND ? ORDER BY date ASC");
            $stmt->bind_param("sss", $teacher_id, $start_date, $end_date);
            $stmt->execute();
            $res_attendance = $stmt->get_result();

            // Fetch all attendance records into an associative array for easy lookup
            $attendance_records = [];
            while ($record = $res_attendance->fetch_assoc()) {
                $attendance_records[$record['date']] = [
                    'status' => $record['status'],
                    'reason' => $record['reason']
                ];
            }

            // Loop through the date range to display all days
            $current_date = new DateTime($start_date);
            $end_date_obj = new DateTime($end_date);

            while ($current_date <= $end_date_obj) {
                $date_str = $current_date->format('Y-m-d');
                $status = isset($attendance_records[$date_str]) ? $attendance_records[$date_str]['status'] : 'N/A';

                $sheet->setCellValue('B' . $currentRow, $date_str);
                $sheet->setCellValue('C' . $currentRow, $status);

                // Apply color based on status
                if ($status == 'Present') {
                    $sheet->getStyle('C' . $currentRow)->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
                } elseif ($status == 'Absent') {
                    $sheet->getStyle('C' . $currentRow)->getFont()->setColor(new Color(Color::COLOR_RED));
                }

                $currentRow++;

                $current_date->modify('+1 day');
            }

            // Add table borders
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle('B' . $headerStartRow . ':C' . ($currentRow - 1))->applyFromArray($styleArray);

            $currentRow++; // Add a blank row for spacing between teachers
        }
    } else {
        $sheet->setCellValue('B' . $currentRow, 'No teachers found.');
        $sheet->getStyle('B' . $currentRow)->getFont()->setColor(new Color(Color::COLOR_RED));
    }

    // Auto-size columns to fit the content
    foreach (range('B', 'H') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // --- DOWNLOAD THE EXCEL FILE ---
    $writer = new Xlsx($spreadsheet);
    $fileName = "teacher_attendance_{$start_date}_to_{$end_date}.xlsx";

    // Clear any previous output to prevent corruption
    ob_clean();

    // Set headers for XLSX download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    // Save the file to the output stream
    $writer->save('php://output');
    exit();
} catch (Exception $e) {
    // Catch any exception and display a user-friendly error message
    header('Content-Type: text/html');
    echo "<h1>An error occurred while generating the Excel file.</h1>";
    echo "<p>Please contact support with the following information:</p>";
    echo "<p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    // Optional: Log the detailed error message for developers
    // error_log($e->getMessage());
    exit();
}