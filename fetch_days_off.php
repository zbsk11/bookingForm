<?php
// Database connection code (adjust as needed)
$conn = new mysqli('localhost', 'root', '', 'barber_booking');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookingDay'])) {
    $bookingDay = $_POST['bookingDay'];

    // Check if a record with the given bookingDay already exists
    $checkSql = "SELECT * FROM daysoff WHERE bookingDay = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $bookingDay);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        // If the record does not exist, insert it
        $insertSql = "INSERT INTO daysoff (bookingDay) VALUES (?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param('s', $bookingDay);

        if ($insertStmt->execute()) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('error' => 'Failed to update database.'));
        }

        $insertStmt->close();
    } else {
        echo json_encode(array('error' => 'Record with bookingDay already exists.'));
    }

    $checkStmt->close();
} else {
    echo json_encode(array('error' => 'Invalid data.'));
}

// Close the database connection
$conn->close();
?>
