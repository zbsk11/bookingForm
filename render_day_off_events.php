<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barber_booking"; // Replace with your database name

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname); // Corrected $dbname

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve day off events from your database
$sql = "SELECT bookingDay FROM daysoff"; // Adjust this query to match your database schema

$result = $conn->query($sql);

$dayOffEvents = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming your 'bookingDay' column stores dates in 'YYYY-MM-DD' format
        $start = $row['bookingDay'];
        
        // Create an array for each day off event
        $event = array(
            'start' => $start
        );
        
        // Add the event to the list
        $dayOffEvents[] = $event;
    }
}

// Close the database connection
$conn->close();

// Return the day off events as JSON
header('Content-Type: application/json');
echo json_encode($dayOffEvents);
?>
