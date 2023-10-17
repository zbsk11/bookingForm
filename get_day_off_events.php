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

    // Get the selected day and calculate the next date it falls under
    $selectedDay = $_POST["selectedDay"];
    $currentDate = date('Y-m-d');
    $nextDate = strtotime("next $selectedDay", strtotime($currentDate));
    $nextDate = date('Y-m-d', $nextDate);

    // Query to check if the next date is a day off
    $query = "SELECT COUNT(*) as count FROM daysoff WHERE bookingDay = :nextDate";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nextDate', $nextDate, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Determine if the next date is a day off
    $isDayOff = ($row["count"] > 0);

    // Return the result as JSON
    echo json_encode(['isDayOff' => $isDayOff]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
