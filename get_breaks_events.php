<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test"; // Replace with your database name

try {
    // Create a database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch break events from the 'breaks' table
    $query = "SELECT bookingDay, startBreakTime FROM breaks WHERE startBreakTime <> '00:00:00'"; // Adjust table and field names as needed

    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch break events data
    $breakEvents = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Convert the 'bookingDay' to the 'yyyy-mm-dd' format
        $formattedDate = date('Y-m-d', strtotime($row['bookingDay']));

        $breakEvents[] = [
            'start' => $formattedDate, // Use the formatted date as 'start'
            'backgroundColor' => 'green' // Set the background color to 'green' for break events
        ];
    }

    // Return break events as JSON
    echo json_encode($breakEvents);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
