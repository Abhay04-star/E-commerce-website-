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

// Function to sanitize inputs
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect form data
    $username = sanitizeInput($_POST["username"]);
    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["password"]);
    $phone = sanitizeInput($_POST["phone"]);
    $address = sanitizeInput($_POST["address"]);

    // Password Hashing for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password, phone, address) 
            VALUES ('$username', '$email', '$hashedPassword', '$phone', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!  ";
       
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
