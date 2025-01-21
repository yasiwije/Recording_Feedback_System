<?php
// Database configuration
define('DB_HOST', 'localhost'); // Change to your database host
define('DB_USER', 'root');      // Change to your database username
define('DB_PASS', '');          // Change to your database password
define('DB_NAME', 'school_db'); // Change to your database name

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to get principal's ID
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate required fields
    if (
        isset($_SESSION['principle-id'], $_POST['content'], $_POST['student-id']) &&
        !empty($_SESSION['principle-id']) &&
        !empty($_POST['content']) &&
        !empty($_POST['student-id'])
    ) {
        $principalId = intval($_POST['principle-id']);
        $content = trim($_POST['content']);
        $studentId = intval($_POST['student-id']); // Ensure student ID is an integer

        echo "Principal ID: $principalId<br>";
        echo "Content: $content<br>";
        echo "Student ID: $studentId<br>";

        // Insert the report into the database
        try {
            $stmt = $conn->prepare("INSERT INTO Reports (Principal_ID, Content, studentId) VALUES (?, ?, ?)");
            
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
    } else {
        echo "Error: All fields are required.";
    }
}

// Close the connection
$conn->close();
?>