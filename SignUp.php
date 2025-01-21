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

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ensure the required fields are set and not empty
        if (isset($_POST['name']) && isset($_POST['role']) && isset($_POST['password'])) {
            $name = $_POST['name'];
            $role = $_POST['role'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            try {
                // Insert into User table
                $stmt = $conn->prepare("INSERT INTO User (Name, Role, Password) VALUES (?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Error preparing statement: " . $conn->error);
                }

                $stmt->bind_param("sss", $name, $role, $password);

                if ($stmt->execute()) {
                    // Get the last inserted ID
                    $userId = $conn->insert_id;

                    // Insert into the specific role table
                    if ($role == 'Principal') {
                        $stmt = $conn->prepare("INSERT INTO Principal (User_ID) VALUES (?)");
                        echo "<script>alert('Signup successful!'); window.location.href='login.html';</script>";
                    } elseif ($role == 'Teacher') {
                        $stmt = $conn->prepare("INSERT INTO Teacher (User_ID) VALUES (?)");
                        echo "<script>alert('Signup successful!'); window.location.href='login.html';</script>";
                    } elseif ($role == 'Parent') {
                        $stmt = $conn->prepare("INSERT INTO Parent (User_ID) VALUES (?)");
                        echo "<script>alert('Signup successful!'); window.location.href='login.html';</script>";
                    }

                    if (!$stmt) {
                        throw new Exception("Error preparing statement: " . $conn->error);
                    }

                    $stmt->bind_param("i", $userId);
                    $stmt->execute();

                    
                } else {
                    throw new Exception("Error executing query: " . $stmt->error);
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