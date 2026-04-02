<?php
// Database connection details
$host = "localhost";  // Change to your database host
$dbname = "projectregistration";  // Change to your database name
$username = "root";  // Change to your database username
$password = "";  // Change to your database password

// Create a connection to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Process the form if it's submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // Simple validation for required fields
    if (empty($name) || empty($email) || empty($message)) {
        echo "All fields are required.";
    } else {
        try {
            // Prepare SQL insert statement
            $sql = "INSERT INTO contact_form (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $pdo->prepare($sql);

            // Bind the form data to the SQL statement
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);

            // Execute the statement
            $stmt->execute();

            // Redirect to a "Thank You" page after successful submission
            header("Location: thank-you.php");  // Redirect to thank-you.php
            exit();  // Stop further script execution
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
