<?php
require 'db_connect.php';

$session_id = $_GET['id'] ?? null;

if ($session_id) {
    $db = getConnection();
    if (!$db) die("Database connection failed!");

    $sql = "UPDATE attendance_sessions SET status='closed' WHERE id=?";
    $stmt = $db->prepare($sql);

    try {
        $stmt->execute([$session_id]);
        echo "Session closed successfully.";

    } catch (PDOException $e) {
        echo "Error closing session: " . $e->getMessage();
    }
} else {
    echo "Missing session ID!";
}
?>

<br><a href="list_session.php">Back to sessions</a>