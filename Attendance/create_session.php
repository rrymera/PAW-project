<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? null;
    $group_id = $_POST['group_id'] ?? null;
    $opened_by = $_POST['opened_by'] ?? null;
    if ($course_id && $group_id && $opened_by) {
        $db = getConnection();
        if (!$db) die("Database connection failed");
        $sql = "INSERT INTO attendance_sessions (course_id, group_id, date, opened_by, status)
                VALUES (?, ?, NOW(), ?, 'open')";
        $stmt = $db->prepare($sql);
        try {
            $stmt->execute([$course_id, $group_id, $opened_by]);
            $session_id = $db->lastInsertId();
            echo "Session created successfully. Session ID: " . $session_id;
        } catch (PDOException $e) {
            echo "Error creating session: " . $e->getMessage();
        }
    } else {
        echo "Please fill all fields";
    }
}
?>

<h2>Create Attendance Session</h2>
<form method="POST">
    <input type="number" name="course_id" placeholder="Course ID"><br><br>
    <input type="number" name="group_id" placeholder="Group ID"><br><br>
    <input type="number" name="opened_by" placeholder="Professor ID"><br><br>
    <button type="submit">Create Session</button>
</form>