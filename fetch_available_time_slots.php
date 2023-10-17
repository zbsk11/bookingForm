<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection (use your own database credentials)
$mysqli = new mysqli("localhost", "root", "", "barber_booking");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDay = $_POST["selectedDay"];

    // Check if the selected day is in the "daysoff" table
    $sql = "SELECT COUNT(*) as count FROM daysoff WHERE bookingDay = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $selectedDay);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row["count"] > 0) {
        // If the selected day is in the "daysoff" table, no slots are available
        echo json_encode(array());
    } else {
        // Get the current date in "yyyy-mm-dd" format
        $currentDate = date("Y-m-d");

        // Fetch shift start and end times for the selected date
        $sql = "SELECT shiftStart, shiftEnd FROM shiftstartend WHERE currentDate = ?";
        $stmt = $mysqli->prepare($sql);

        // Use "s" parameter type for binding the date
        $stmt->bind_param("s", $currentDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $shiftStart = strtotime($row["shiftStart"]);
            $shiftEnd = strtotime($row["shiftEnd"]);

            // Create time slots between shiftStart and shiftEnd
            $interval = 30 * 60; // 30 minutes in seconds
            $currentTimestamp = $shiftStart;
            $availableTimeSlots = array();

            while ($currentTimestamp < $shiftEnd) {
                $timeSlot = date("H:i", $currentTimestamp);
                $availableTimeSlots[] = $timeSlot;
                $currentTimestamp += $interval;
            }

            // Fetch breaks from the "setup" table
            $sql = "SELECT offStart, offEnd FROM setup WHERE currentDate = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $currentDate);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $offStart = strtotime($row["offStart"]);
                $offEnd = strtotime($row["offEnd"]);

                // Remove time slots that fall within breaks
                foreach ($availableTimeSlots as $key => $timeSlot) {
                    $timeSlotTimestamp = strtotime($timeSlot);
                    if ($timeSlotTimestamp >= $offStart && $timeSlotTimestamp < $offEnd) {
                        unset($availableTimeSlots[$key]);
                    }
                }
            }

            // Fetch booked time slots from the "registration" table in the "test" database
            $mysqli->select_db("test");
            $sql = "SELECT DISTINCT timeSlot FROM registration WHERE bookingDay = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $selectedDay);
            $stmt->execute();
            $result = $stmt->get_result();

            $bookedTimeSlots = array();
            while ($row = $result->fetch_assoc()) {
                $bookedTimeSlots[] = $row["timeSlot"];
            }

            // Remove booked time slots from the available slots
            $availableTimeSlots = array_diff($availableTimeSlots, $bookedTimeSlots);

            // Return the available time slots as a JSON response
            echo json_encode(array_values($availableTimeSlots));
        } else {
            // Handle case where no shift data was found for the selected day
            echo json_encode(array());
        }
    }
}

// Close the database connection
$mysqli->close();
?>
