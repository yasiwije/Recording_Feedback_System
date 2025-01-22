<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'school_db');

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to get principal's ID
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $principalId = intval($_POST['principle-id']);
    $content = trim($_POST['content']);
    $studentId = intval($_POST['student-id']);

    // Verify Principal_ID exists
    $principalCheck = $conn->prepare("SELECT User_ID FROM principal WHERE User_ID = ?");
    if (!$principalCheck) {
        die("Error preparing principal check statement: " . $conn->error);
    }
    $principalCheck->bind_param("i", $principalId);
    $principalCheck->execute();
    if ($principalCheck->get_result()->num_rows == 0) {
        die("Error: Principal ID does not exist.");
    }

    // Verify studentId exists
    $studentCheck = $conn->prepare("SELECT studentId FROM student WHERE studentId = ?");
    if (!$studentCheck) {
        die("Error preparing student check statement: " . $conn->error);
    }
    $studentCheck->bind_param("i", $studentId);
    $studentCheck->execute();
    if ($studentCheck->get_result()->num_rows == 0) {
        die("Error: Student ID does not exist.");
    }

    // Insert the report into the database
    try {
        $stmt = $conn->prepare("INSERT INTO reports (Principal_ID, Content, studentId) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("isi", $principalId, $content, $studentId);

        if ($stmt->execute()) {
            echo "<script>alert('Report generated successfully!'); window.location.href='view_reports.php';</script>";
        } else {
            throw new Exception("Error generating report: " . $stmt->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
// Close the connection
$conn->close();
?>
