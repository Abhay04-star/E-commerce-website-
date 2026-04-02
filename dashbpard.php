<?php
// Start session to check if the user is logged in
session_start();

// Check if the user is logged in (i.e., session variable 'user_id' is set)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection details
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "projectregistration";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // This is correct for debugging
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Query to fetch user data
$sql = "SELECT username, email, phone, address FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

// Error checking for prepared statement
if (!$stmt) {
    die("SQL error: " . $conn->error);  // Show SQL errors if the prepare fails
}

// Bind the user ID parameter
$stmt->bind_param("i", $user_id);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if user data is found
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();  // Fetch user data
} else {
    echo "No user found!";
    exit();
}

// Close the connection
$stmt->close(); // Close prepared statement
$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        .dashboard-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .dashboard-container p {
            margin: 10px 0;
        }
        .dashboard-container a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
            background-color: #ccc;
            padding: 10px;
            border-radius: 5px;
        }
        .dashboard-container a:hover {
            background-color: #bbb;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>

    <a href="logout.php">Logout</a>
</div>

</body>
</html>
