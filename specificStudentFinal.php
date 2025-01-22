<?php
    // Database configuration
    define('DB_HOST', 'localhost'); // Database host
    define('DB_USER', 'root');      // Database username
    define('DB_PASS', '');          // Database password
    define('DB_NAME', 'school_db'); // Database name


    // Connect to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Start session
    session_start();
    if (isset($_SESSION['user_id'])) {
        // Fetch all reports
        $stmt = $conn->prepare("SELECT * FROM reports WHERE studentId = 5"); // SELECT statement to fetch data
        $stmt->execute();
        $result = $stmt->get_result(); // Store the result
    } else {
        echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
        exit();
    }

    // Close the connection
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        /* Container Styling */
        .container {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1000px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }
        /* Heading Styling */
        h2 {
            margin-bottom: 20px;
            color: #0072ff;
            font-size: 2rem;
            border-bottom: 2px solid #0072ff;
            display: inline-block;
            padding-bottom: 5px;
        }
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        table th {
            background-color: #0072ff;
            color: white;
            font-size: 1.1rem;
        }
        table td {
            font-size: 0.95rem;
            word-wrap: break-word;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        /* Paragraph Styling */
        p {
            font-size: 1rem;
            margin-top: 20px;
            color: #555;
        }
        /* Button Container */
        .btn-container {
            margin-top: 20px;
        }
        /* Button Styling */
        .btn {
            background-color: #0072ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn:hover {
            background-color: #0056cc;
        }
        .btn:active {
            transform: scale(0.98);
        }
        /* Responsive Styling */
        @media (max-width: 768px) {
            table th, table td {
                font-size: 0.85rem;
                padding: 10px;
            }
            h2 {
                font-size: 1.5rem;
            }
            .btn {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }
        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>All Reports</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Report ID</th>
                    <th>Principal ID</th>
                    <th>Content</th>
                    <th>Generated At</th>
                    <th>Student ID</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['R_ID']; ?></td>
                        <td><?php echo $row['Principal_ID']; ?></td>
                        <td><?php echo $row['Content']; ?></td>
                        <td><?php echo $row['Generated_At']; ?></td>
                        <td><?php echo $row['studentId']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No reports found.</p>
        <?php endif; ?>
        <div class="btn-container">
            <a href="parent.html" class="btn">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
