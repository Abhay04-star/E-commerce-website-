<?php
// Database connection details
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "projectregistration";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to handle user login
session_start();

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = sanitizeInput($_POST["username"]);
    $password = $_POST["password"];

    // Prepare and execute SQL query to fetch user details based on the provided username
    $sql = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    // Check if username exists in the database
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to a protected page (e.g., dashboard)
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password
            $loginError = "Incorrect username or password.";
        }
    } else {
        // Username doesn't exist
        $loginError = "Incorrect username or password.";
    }

    // Close prepared statement
    $sql->close();
}

// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Close connection
$conn->close();
?>
