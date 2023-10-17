<?php
// Database connection code (adjust as needed)
$conn = new mysqli('localhost', 'root', '', 'test');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['bookingDay']) && isset($_POST['breakStartTime']) && isset($_POST['breakEndTime'])) {
    $bookingDay = $_POST['bookingDay'];
    $breakStartTime = $_POST['breakStartTime'];
    $breakEndTime = $_POST['breakEndTime'];

    // Check if a record with the given bookingDay already exists
    $checkSql = "SELECT * FROM registration WHERE bookingDay = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $bookingDay);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // If a record exists, update the 'startTime' and 'endTime' fields
        $updateSql = "UPDATE registration SET breakStartTime = ?, breakEndTime = ? WHERE bookingDay = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param('sss', $breakStartTime, $breakEndTime, $bookingDay);
        
        if ($updateStmt->execute()) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('error' => 'Failed to update database.'));
        }
    } else {
        // If no record exists, insert a new record with 'startTime' and 'endTime'
        $insertSql = "INSERT INTO registration (firstName, bookingDay, timeSlot, serviceName, email, number, isWorking, breakStartTime, breakEndTime) VALUES ('off', ?, 'break', 'off', 'off', '0', 'y', ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param('sss', $bookingDay, $breakStartTime, $breakEndTime);
        
        if ($insertStmt->execute()) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('error' => 'Failed to update database.'));
        }
    }

    // Close the database connections
    $checkStmt->close();
    $updateStmt->close();
    $insertStmt->close();
} else {
    echo json_encode(array('error' => 'Invalid data.'));
}

// Close the database connection
$conn->close();
?>
