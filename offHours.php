<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection code here
$conn = new mysqli('localhost', 'root', '', 'barber_booking');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the POST data includes the required fields
    if (isset($_POST['currentDate']) && isset($_POST['offStart']) && isset($_POST['offEnd'])) {
        $currentDate = $_POST['currentDate'];
        $offStart = $_POST['offStart']; // You may need to add validation and sanitation here
        $offEnd = $_POST['offEnd'];     // You may need to add validation and sanitation here

        // Insert a new record into the "breaks" table
        $insertSql = "INSERT INTO setup (currentDate, offStart, offEnd) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param('sss', $currentDate, $offStart, $offEnd);

        if ($insertStmt->execute()) {
            $response = array('success' => true);
            echo json_encode($response);
        } else {
            $response = array('error' => 'Failed to update database.');
            echo json_encode($response);
        }
    } else {
        // Handle the case where one or more required POST parameters are missing
        $response = array('error' => 'One or more required POST parameters are missing.');
        echo json_encode($response);
    }
} else {
    // Handle the case where the request method is not POST
    $response = array('error' => 'Invalid request method.');
    echo json_encode($response);
}

// Close the database connection
$conn->close();
?>
