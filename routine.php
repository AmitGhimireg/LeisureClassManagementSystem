<?php
// This file displays the daily academic routine and a dynamic substitute routine.
// It checks the 'attendance' table to find absent teachers and displays their
// academic schedule as a substitute schedule.

// --- FILE INCLUSIONS ---
// Make sure these files exist and are correctly configured.
// 'config/db_connect.php' should contain your database connection logic.
include('partial-front/navbar.php');
include('partial-front/login-check.php');


// --- ROUTINE VARIABLES ---
// Define the fixed period times
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

// Set the current day and date based on the current system time
$date_to_fetch = date('Y-m-d');

// A more robust way to handle period times for comparison
$period_time_ranges = [];
foreach ($main_period_times as $time_slot) {
    list($start, $end) = explode('-', $time_slot);
    $period_time_ranges[] = [
        'start' => date('H:i:s', strtotime(trim($start))),
        'end' => date('H:i:s', strtotime(trim($end)))
    ];
}

// --- FETCH ACADEMIC ROUTINE DATA ---
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
            // Check if the routine's start time falls within the fixed period's range
            if ($db_start_time >= $range['start'] && $db_start_time < $range['end']) {
                $period_index = $index;
                break;
            }
        }

        if ($period_index !== null) {
            if (!isset($academic_routines_data[$class_label])) {
                $academic_routines_data[$class_label] = array_fill(0, count($main_period_times), [
                    'subject1' => '', 'subject2' => '', 'teacher1' => '', 'teacher2' => '', 'is_break' => 0, 'day_range1' => '', 'day_range2' => ''
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

// --- FETCH DYNAMIC SUBSTITUTE ROUTINE DATA ---
$substitute_schedule = [];
$absent_teachers_list = [];

// Step 1: Get the list of teacher IDs who are absent or on leave for today.
$sql_absent_teachers = "SELECT teacher_id FROM attendance WHERE date = '$date_to_fetch' AND status IN ('Absent', 'Leave')";
$result_absent_teachers = $conn->query($sql_absent_teachers);

$absent_teacher_ids = [];
if ($result_absent_teachers && $result_absent_teachers->num_rows > 0) {
    while ($row = $result_absent_teachers->fetch_assoc()) {
        $absent_teacher_ids[] = $row['teacher_id'];
    }
}

// Step 2: If there are any absent teachers, fetch their academic routines
// to serve as the substitute schedule.
if (!empty($absent_teacher_ids)) {
    $absent_teachers_str = implode(',', $absent_teacher_ids);

    $sql_substitute = "SELECT
                            t.full_name AS teacher_name,
                            ar.start_time,
                            ar.end_time,
                            s1.name AS subject1_name,
                            s2.name AS subject2_name,
                            c.name AS class_name,
                            c.section,
                            ar.day_range1,
                            ar.day_range2,
                            ar.is_break
                        FROM academic_routine ar
                        JOIN teachers t ON ar.teacher_id1 = t.teach_id OR ar.teacher_id2 = t.teach_id
                        LEFT JOIN subjects s1 ON ar.subject_id1 = s1.subj_id
                        LEFT JOIN subjects s2 ON ar.subject_id2 = s2.subj_id
                        LEFT JOIN classes c ON ar.class_id = c.cls_id
                        WHERE (ar.teacher_id1 IN ($absent_teachers_str) OR ar.teacher_id2 IN ($absent_teachers_str))
                        ORDER BY t.full_name, ar.start_time";

    $result_substitute = $conn->query($sql_substitute);

    if ($result_substitute) {
        while ($row = $result_substitute->fetch_assoc()) {
            $teacher_name = $row['teacher_name'];

            $db_start_time = date('H:i:s', strtotime($row['start_time']));
            $period_index = null;

            foreach ($period_time_ranges as $index => $range) {
                // Check if the routine's start time falls within the fixed period's range
                if ($db_start_time >= $range['start'] && $db_start_time < $range['end']) {
                    $period_index = $index;
                    break;
                }
            }

            if ($period_index !== null) {
                if (!isset($substitute_schedule[$teacher_name])) {
                    $substitute_schedule[$teacher_name] = array_fill(0, count($main_period_times), [
                        'subject1' => '', 'subject2' => '', 'class' => '', 'is_break' => 0, 'day_range1' => '', 'day_range2' => ''
                    ]);
                    $absent_teachers_list[] = $teacher_name;
                }

                $class = empty($row['class_name']) ? '' : $row['class_name'] . (empty($row['section']) ? '' : ' (' . $row['section'] . ')');
                
                $substitute_schedule[$teacher_name][$period_index] = [
                    'subject1' => $row['subject1_name'] ?? '',
                    'subject2' => $row['subject2_name'] ?? '',
                    'class' => $class,
                    'is_break' => $row['is_break'] ?? 0,
                    'day_range1' => $row['day_range1'] ?? '',
                    'day_range2' => $row['day_range2'] ?? ''
                ];
            }
        }
    }
}

// Close the database connection
$conn->close();
?>

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
                            <?php if (empty($academic_routines_data)) : ?>
                                <tr>
                                    <td colspan="<?php echo count($main_period_times) + 1; ?>" class="text-center">No academic routine found.</td>
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
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Daily Substitute Routine for Absent Teachers</h2>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Absent Teacher</th>
                                <?php foreach ($main_period_times as $time_slot) : ?>
                                    <th scope="col" class="text-center small-time-header">
                                        <?php echo htmlspecialchars($time_slot); ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($absent_teachers_list)) : ?>
                                <tr>
                                    <td colspan="<?php echo count($main_period_times) + 1; ?>" class="text-center">No substitute routine needed for today. All teachers are present.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($absent_teachers_list as $teacher_name) : ?>
                                    <tr>
                                        <th scope="row" class="text-start"><?php echo htmlspecialchars($teacher_name); ?></th>
                                        <?php
                                        foreach ($substitute_schedule[$teacher_name] as $period_info) {
                                            $subject1 = $period_info['subject1'];
                                            $subject2 = $period_info['subject2'];
                                            $class = $period_info['class'];
                                            $is_break = $period_info['is_break'];
                                            $day_range1 = $period_info['day_range1'];
                                            $day_range2 = $period_info['day_range2'];
                                            
                                            $cell_content = '';
                                            $cell_class = '';

                                            if ($is_break == 1) {
                                                $cell_class = 'table-warning fw-bold';
                                                $cell_content = 'BREAK';
                                            } else {
                                                $subjects = [];
                                                if (!empty($subject1)) $subjects[] = $subject1;
                                                if (!empty($subject2)) $subjects[] = $subject2;
                                                
                                                if (!empty($subjects)) {
                                                    $cell_content .= '<strong>' . implode(' / ', array_map('htmlspecialchars', $subjects)) . '</strong>';
                                                }

                                                if (!empty($class)) {
                                                    $cell_content .= '<br><small>' . htmlspecialchars($class) . '</small>';
                                                }
                                                
                                                $day_ranges = [];
                                                if (!empty($day_range1)) $day_ranges[] = $day_range1;
                                                if (!empty($day_range2)) $day_ranges[] = $day_range2;

                                                if (!empty($day_ranges)) {
                                                    $cell_content .= '<br><small class="text-muted">Day: ' . implode(' / ', array_map('htmlspecialchars', $day_ranges)) . '</small>';
                                                }
                                            }
                                            
                                            echo '<td class="text-center ' . htmlspecialchars($cell_class) . '">';
                                            echo !empty($cell_content) ? $cell_content : '-';
                                            echo '</td>';
                                        }
                                        ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mt-3 text-center">
                    This table shows the academic classes that need a substitute teacher due to a teacher's absence or leave.
                </p>
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