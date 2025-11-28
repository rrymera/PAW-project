<?php
$studentsFile = 'students.json';
$students = [];

if (file_exists($studentsFile)) {
    $data = file_get_contents($studentsFile);
    $students = json_decode($data, true);
    if (!is_array($students)) {
        $students = [];
    }
} else {
    echo "<p style='color:red;'>No students found, please add students first.</p>";
    exit;
}
//file name
$today = date('Y-m-d');
$attendanceFile = "attendance_$today.json";
if (file_exists($attendanceFile)) {
    echo "<p style='color:red;'>Attendance for today has already been taken</p>";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance = [];
    foreach ($students as $student) {
        $student_id = $student['matricule'] ?? $student['student_id'] ?? '';
        $status = $_POST['attendance'][$student_id] ?? 'absent';
        $attendance[] = [
            'student_id' => $student_id,
            'status'     => $status
        ];
    }
    //save in json
    if (file_put_contents($attendanceFile, json_encode($attendance, JSON_PRETTY_PRINT))) {
        echo "<p style='color:green;'>Attendance saved successfully in $attendanceFile.</p>";
    } else {
        echo "<p style='color:red;'>Failed to save attendance.</p>";
    }
    exit;
}
?>

<h2>Take Attendance for <?php echo $today; ?></h2>
<form method="POST">
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Attendance</th>
        </tr>
        <?php foreach ($students as $student): 
            $student_id = $student['matricule'] ?? $student['student_id'] ?? '';
            $name = $student['fullname'] ?? $student['name'] ?? '';
        ?>
        <tr>
            <td><?php echo htmlspecialchars($student_id); ?></td>
            <td><?php echo htmlspecialchars($name); ?></td>
            <td>
                <select name="attendance[<?php echo $student_id; ?>]">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                </select>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <button type="submit">Submit Attendance</button>
</form>
