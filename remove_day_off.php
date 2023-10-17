<?php
// Replace these database connection details with your own
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barber_booking";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected date from the POST request
if (isset($_POST['bookingDay'])) {
    $selectedDate = date('Y-m-d', strtotime($_POST['bookingDay'])); // Convert to yyyy-mm-dd format

    // SQL to delete the day off entry from the database
    $sql = "DELETE FROM daysoff WHERE bookingDay = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedDate);

    if ($stmt->execute()) {
        // Success: Day off entry deleted from the database
        $response = array('success' => true);
    } else {
        // Error: Failed to delete day off entry
        $response = array('success' => false, 'error' => $conn->error);
    }

    $stmt->close();
} else {
    // Error: Invalid POST request
    $response = array('success' => false, 'error' => 'Invalid POST request');
}

$conn->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
