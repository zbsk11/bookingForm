<?php
// Database connection code (adjust as needed)
$conn = new mysqli('localhost','root','','test');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events data from the database
$sql = "SELECT firstName, serviceName, bookingDay, timeSlot, email, number FROM registration"; // Change 'your_table_name' to the actual table name
$result = $conn->query($sql);

// Initialize an empty array to store the events
$events = array();
$bookedTimeSlots = array(); // Store booked time slots

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Format the date and time in ISO8601 format
        $start = date('Y-m-d', strtotime($row['bookingDay'])) . 'T' . $row['timeSlot'];
        // Calculate the end time by adding 1 hour to the start time
        $end = date('Y-m-d H:i:s', strtotime($start . '+1 hour'));

        // Create an event object
        $event = array(
            'title' => $row['firstName'] . ' - ' . $row['serviceName'],
            'start' => $start,
            'end' => $end, // Set the end time 
            'email' => $row['email'],
            'number' => $row['number']
        );

        // Add the event to the events array
        $events[] = $event;

        // Store the booked time slots
        $bookedTimeSlots[] = $start;
    }
}

// Close the database connection
$conn->close();

// Output the events data and booked time slots as JSON
$response = array(
    'events' => $events,
    'bookedTimeSlots' => $bookedTimeSlots
);

// Output the events data as JSON
echo json_encode($events);
?>