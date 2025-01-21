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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate required fields
    if (isset($_POST['name'], $_POST['password']) && !empty($_POST['name']) && !empty($_POST['password'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];

        // Use a try-catch block for error handling
        try {
            // Fetch the user from the database
            $stmt = $conn->prepare("SELECT User_ID, Role, Password FROM User WHERE Name = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }

            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch the user data
                $row = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $row['Password'])) {
                    // Start a session
                    session_start();
                    $_SESSION['user_id'] = $row['User_ID'];
                    $_SESSION['role'] = $row['Role'];
                    $_SESSION['name'] = $name;

                    // Redirect based on role
                    if ($row['Role'] == 'Principal') {
                        header("Location: principal.html");
                    } elseif ($row['Role'] == 'Teacher') {
                        header("Location: teacher.html");
                    } elseif ($row['Role'] == 'Parent') {
                        header("Location: parent.html");
                    } else {
                        throw new Exception("Unknown role detected.");
                    }
                    exit();
                } else {
                    echo "<script>alert('Invalid password. Please try again.');</script>";
                }
            } else {
                echo "<script>alert('No user found with this name.');</script>";
            }
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
