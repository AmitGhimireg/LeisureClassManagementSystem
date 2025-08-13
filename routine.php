<?php
// Start output buffering at the very beginning of the script.
// This is essential to prevent "headers already sent" errors.
ob_start();
include('partial-front/navbar.php');

// --- HTML PAGE DISPLAY ---
// This part of the code will always run.
// All HTML content and includes must be within this block.

// We need to fetch the data for the HTML table display.

$main_period_times = [
    '10:15-11:00 AM',
    '11:00-11:45 AM',
    '11:45-12:30 PM',
    '12:30-01:15 PM',
    '01:15-02:00 PM',
    '02:00-02:40 PM',
    '02:40-03:20 PM',
    '03:20-04:00 PM'
];

$period_time_ranges = [];
foreach ($main_period_times as $time_slot) {
    list($start, $end) = explode('-', $time_slot);
    $period_time_ranges[] = [
        'start' => date('H:i:s', strtotime(trim($start))),
        'end' => date('H:i:s', strtotime(trim($end)))
    ];
}

$academic_routines_data = [];
$sql_academic = "SELECT
                     c.name AS class_name,
                     c.section,
                     ar.start_time,
                     ar.end_time,
                     s1.name AS subject1_name,
                     s2.name AS subject2_name,
                     t1.full_name AS teacher1_name,
                     t2.full_name AS teacher2_name,
                     ar.day_range1,
                     ar.day_range2,
                     ar.is_break
                 FROM academic_routine ar
                 JOIN classes c ON ar.class_id = c.cls_id
                 LEFT JOIN subjects s1 ON ar.subject_id1 = s1.subj_id
                 LEFT JOIN subjects s2 ON ar.subject_id2 = s2.subj_id
                 LEFT JOIN teachers t1 ON ar.teacher_id1 = t1.teach_id
                 LEFT JOIN teachers t2 ON ar.teacher_id2 = t2.teach_id
                 ORDER BY c.name, c.section, ar.start_time";

$result_academic = $conn->query($sql_academic);

if ($result_academic) {
    while ($row = $result_academic->fetch_assoc()) {
        $class_label = $row['class_name'] . (empty($row['section']) ? '' : ' (' . $row['section'] . ')');
        $db_start_time = date('H:i:s', strtotime($row['start_time']));
        $period_index = null;

        foreach ($period_time_ranges as $index => $range) {
            if ($db_start_time >= $range['start'] && $db_start_time < $range['end']) {
                $period_index = $index;
                break;
            }
        }
        if ($period_index !== null) {
            if (!isset($academic_routines_data[$class_label])) {
                $academic_routines_data[$class_label] = array_fill(0, count($main_period_times), [
                    'subject1' => '',
                    'subject2' => '',
                    'teacher1' => '',
                    'teacher2' => '',
                    'is_break' => 0,
                    'day_range1' => '',
                    'day_range2' => ''
                ]);
            }
            $academic_routines_data[$class_label][$period_index] = [
                'subject1' => $row['subject1_name'] ?? '',
                'subject2' => $row['subject2_name'] ?? '',
                'teacher1' => $row['teacher1_name'] ?? '',
                'teacher2' => $row['teacher2_name'] ?? '',
                'is_break' => $row['is_break'] ?? 0,
                'day_range1' => $row['day_range1'] ?? '',
                'day_range2' => $row['day_range2'] ?? ''
            ];
        }
    }
}

// --- Fetch and process data for Leisure Teacher ---
// Initialize leisure teacher data as an array for each period to hold multiple names
$leisure_teacher_data = array_fill(0, count($main_period_times), []);
// Modify the SQL query to select only the first name
$sql_leisure = "SELECT
                    SUBSTRING_INDEX(t.full_name, ' ', 1) AS teacher_first_name,
                    lt.st_time
                FROM leisure_teacher lt
                JOIN teachers t ON lt.teacher_id = t.teach_id
                ORDER BY lt.st_time";
$result_leisure = $conn->query($sql_leisure);

if ($result_leisure) {
    while ($row = $result_leisure->fetch_assoc()) {
        $db_st_time = date('H:i:s', strtotime($row['st_time']));
        $period_index = null;

        foreach ($period_time_ranges as $index => $range) {
            if ($db_st_time >= $range['start'] && $db_st_time < $range['end']) {
                $period_index = $index;
                break;
            }
        }
        if ($period_index !== null) {
            // Append the teacher's first name to the array for the corresponding period
            $leisure_teacher_data[$period_index][] = $row['teacher_first_name'];
        }
    }
}

// Close the database connection after fetching all data.
$conn->close();
?>

<?php include('partial-front/login-check.php'); ?>

<div class="main-content">
    <div class="container mt-4">
        <h2 class="text-center mb-4">Yearly Class Routine</h2>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Class / Time</th>
                                <?php foreach ($main_period_times as $time_slot) : ?>
                                    <th scope="col" class="text-center small-time-header">
                                        <?php echo htmlspecialchars($time_slot); ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($academic_routines_data) && empty($leisure_teacher_data)) : ?>
                                <tr>
                                    <td colspan="<?php echo count($main_period_times) + 1; ?>" class="text-center">No academic routine or leisure teacher found.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($academic_routines_data as $class_label => $routine_periods) : ?>
                                    <tr>
                                        <th scope="row" class="text-center"><?php echo htmlspecialchars($class_label); ?></th>
                                        <?php foreach ($routine_periods as $period_info) : ?>
                                            <?php
                                            $subject1 = $period_info['subject1'];
                                            $subject2 = $period_info['subject2'];
                                            $teacher1 = $period_info['teacher1'];
                                            $teacher2 = $period_info['teacher2'];
                                            $is_break = $period_info['is_break'];
                                            $day_range1 = $period_info['day_range1'];
                                            $day_range2 = $period_info['day_range2'];
                                            $cell_content = '';
                                            $cell_class = '';

                                            if ($is_break == 1) {
                                                $cell_content = 'BREAK';
                                                $cell_class = 'table-warning fw-bold text-center';
                                            } else {
                                                $subjects = [];
                                                if (!empty($subject1)) $subjects[] = $subject1;
                                                if (!empty($subject2)) $subjects[] = $subject2;

                                                if (!empty($subjects)) {
                                                    $cell_content .= '<strong>' . implode(' / ', array_map('htmlspecialchars', $subjects)) . '</strong>';
                                                }

                                                $teachers = [];
                                                if (!empty($teacher1)) $teachers[] = $teacher1;
                                                if (!empty($teacher2)) $teachers[] = $teacher2;

                                                if (!empty($teachers)) {
                                                    $cell_content .= '<br><small>' . implode(' / ', array_map('htmlspecialchars', $teachers)) . '</small>';
                                                }

                                                $day_ranges = [];
                                                if (!empty($day_range1)) $day_ranges[] = $day_range1;
                                                if (!empty($day_range2)) $day_ranges[] = $day_range2;

                                                if (!empty($day_ranges)) {
                                                    $cell_content .= '<br><small class="text-muted">Day: ' . implode(' / ', array_map('htmlspecialchars', $day_ranges)) . '</small>';
                                                }
                                            }
                                            ?>
                                            <td class="text-center <?php echo htmlspecialchars($cell_class); ?>">
                                                <?php echo !empty($cell_content) ? $cell_content : '-'; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th scope="row" class="text-center">Leisure Teacher</th>
                                    <?php foreach ($leisure_teacher_data as $period_teachers) : ?>
                                        <td class="text-center"> <b>
                                            <?php
                                            if (!empty($period_teachers)) {
                                                // Display first names separated by a comma and space
                                                echo implode(', ', array_map('htmlspecialchars', $period_teachers));
                                            } else {
                                                echo '-';
                                            }
                                            ?> </b>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <br> <center>
                <a href="download-routine.php" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download Routine
                </a>
                </center>
            </div>
        </div>
    </div>
</div>

<style>
    .small-time-header {
        font-size: 0.8rem;
        white-space: nowrap;
        padding-left: 0.3rem;
        padding-right: 0.3rem;
    }

    .table td,
    .table th {
        padding: 0.4rem;
        vertical-align: middle;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table td small {
        font-size: 0.75rem;
        white-space: nowrap;
    }
</style>

<?php include('partial-front/footer.php'); ?>
<?php
// Clean and send the buffer at the very end of the script.
ob_end_flush();
?>