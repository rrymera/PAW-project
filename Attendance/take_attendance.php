<?php
date_default_timezone_set('UTC'); // Mettre le fuseau horaire souhaité

// 1. Charger les étudiants depuis students.json
$studentsFile = 'students.json';
$students = [];

if (file_exists($studentsFile)) {
    $data = file_get_contents($studentsFile);
    $students = json_decode($data, true);
    if (!is_array($students)) {
        $students = [];
    }
} else {
    echo "<p style='color:red;'>No students found. Please add students first.</p>";
    exit;
}

// Nom du fichier d'aujourd'hui
$today = date('Y-m-d');
$attendanceFile = "attendance_$today.json";

// 3. Vérifier si la présence a déjà été prise
if (file_exists($attendanceFile)) {
    echo "<p style='color:red;'>Attendance for today has already been taken.</p>";
    exit;
}

// 2. Si le formulaire est soumis, enregistrer la présence
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

    // Sauvegarder dans le fichier JSON
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
