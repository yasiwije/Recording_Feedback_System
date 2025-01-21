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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $student_name = $conn->real_escape_string($_POST['student-name']);
    $student_id = $conn->real_escape_string($_POST['student-id']);
    $academics = $conn->real_escape_string($_POST['academics']);
    $behavior = $conn->real_escape_string($_POST['behavior']);
    $performance_rating = $conn->real_escape_string($_POST['performance-rating']);

    // SQL query to insert data
    $sql = "INSERT INTO student_feedback (student_name, student_id, academics, behavior, performance_rating) 
            VALUES ('$student_name', '$student_id', '$academics', '$behavior', '$performance_rating')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='studentdetails.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
