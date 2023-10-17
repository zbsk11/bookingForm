<?php
// Database configuration (update with your own credentials)
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

// Get the current date
$currentDate = date('Y-m-d');

// Remove entries older than the current date
$sql = "DELETE FROM shiftstartend WHERE currentDate < ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();

// Check the number of entries in the table
$sql = "SELECT COUNT(*) AS count FROM shiftstartend";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$entryCount = $row['count'];

// Calculate how many new entries need to be added
$entriesToAdd = 7 - $entryCount;

if ($entriesToAdd > 0) {
    // Get the shift start and end times from the first entry in the table
    $sql = "SELECT shiftStart, shiftEnd FROM shiftstartend ORDER BY currentDate ASC LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $shiftStart = $row['shiftStart'];
    $shiftEnd = $row['shiftEnd'];

    // Insert new entries starting from the current date
    for ($i = 0; $i < $entriesToAdd; $i++) {
        $sql = "INSERT INTO shiftstartend (currentDate, shiftStart, shiftEnd) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $currentDate, $shiftStart, $shiftEnd);
        $stmt->execute();

        // Increment the current date for the next entry
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }
}

// Close the database connection
$conn->close();
?>
