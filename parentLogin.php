<?php
// Database configuration
define('DB_HOST', 'localhost'); // Replace with your database host
define('DB_USER', 'root');      // Replace with your database username
define('DB_PASS', '');          // Replace with your database password
define('DB_NAME', 'school_db'); // Replace with your database name

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to track login
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $studentId = trim($_POST['student']);

    // Validate inputs
    if (empty($username) || empty($password) || empty($studentId)) {
        die("Error: All fields are required.");
    }

    // Fetch user from database with correct JOIN logic
    $stmt = $conn->prepare("
        SELECT p.Parent_ID, p.username, p.password 
        FROM parent p
        JOIN user u ON p.User_ID = u.User_ID
        JOIN student s ON s.studentId = ?
        WHERE p.username = ?
    ");

    // Error handling for prepare() failure
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("is", $studentId, $username);

    // Execute statement and fetch results
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['parent_id'] = $user['Parent_ID']; // Store parent ID in session
            $_SESSION['username'] = $user['username'];  // Store username in session
            echo "<script>alert('Login successful!'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Invalid password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Invalid username or student ID.'); window.history.back();</script>";
    }
    $stmt->close();
}

// Close the connection
$conn->close();
?>
