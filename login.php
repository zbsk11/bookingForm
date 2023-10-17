<?php
session_start();

// Replace with your database connection code
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace with actual SQL query to validate user credentials
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Authentication successful, set session variable
        $_SESSION['authenticated'] = true;
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        // Authentication failed, return an error message
        echo json_encode(["status" => "error", "message" => "Incorrect username or password. Please try again."]);
        exit();
    }
}

$conn->close();
?>
