<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barber_booking"; // Replace with your database name

try {
    // Create a database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch break events from the 'setup' table with currentDate
    $query = "SELECT currentDate, offStart, offEnd FROM setup"; // Adjust table and field names as needed

    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch break events data
    $breakEvents = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extract the date portion from 'currentDate' field
        $eventDate = date('Y-m-d', strtotime($row['currentDate']));

        // Format the 'offStart' and 'offEnd' times with the extracted date
        $formattedStart = $eventDate . 'T' . date('H:i:s', strtotime($row['offStart']));
        $formattedEnd = $eventDate . 'T' . date('H:i:s', strtotime($row['offEnd']));

        $breakEvents[] = [
            'title' => 'Break', // You can set a title for the break event
            'start' => $formattedStart, // Use the formatted start time as 'start'
            'end' => $formattedEnd,     // Use the formatted end time as 'end'
            'backgroundColor' => 'blue'
        ];
    }

    // Return break events as JSON
    echo json_encode($breakEvents);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>