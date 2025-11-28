<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname  = trim($_POST['fullname'] ?? '');
    $matricule = trim($_POST['matricule'] ?? '');
    $group_id  = trim($_POST['group_id'] ?? '');
    if ($fullname && $matricule && $group_id) {
        $db = getConnection();
        if ($db) {
            //add in db
            $sql = "INSERT INTO students (fullname, matricule, group_id) VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
            try {
                $stmt->execute([$fullname, $matricule, $group_id]);
                echo "<p>Student added successfully</p>";
                //add in students.json
                $file = 'students.json';
                $students = [];
                if (file_exists($file)) {
                    $data = file_get_contents($file);
                    $students = json_decode($data, true);
                    if (!is_array($students)) {
                        $students = [];
                    }
                }
                $students[] = [
                    'fullname'  => $fullname,
                    'matricule' => $matricule,
                    'group_id'  => $group_id
                ];
                if (file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT))) {
                    echo "<p style='color:green;'> </p>"; //Student added to JSON 
                } else {
                    echo "<p style='color:red;'> </p>"; //Failed to add student in JSON
                }
            } catch (PDOException $e) {
                echo "<p style='color:red;'>Error adding student: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color:red;'>Database connection failed</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill all the fields</p>";
    }
}
?>

<form method="POST">
    <input type="text" name="fullname" placeholder="Full name"><br><br>
    <input type="text" name="matricule" placeholder="Matricule"><br><br>
    <input type="number" name="group_id" placeholder="Group ID"><br><br>
    <button type="submit">Add Student</button>
</form>
