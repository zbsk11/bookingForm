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

// Retrieve the entries from the POST request
$entriesJson = $_POST['entries'];
$entries = json_decode($entriesJson, true);

if ($entries) {
    // Start a transaction to ensure data consistency
    $conn->begin_transaction();

    // Delete all existing entries from the 'shiftstartend' table
    $deleteQuery = "DELETE FROM shiftstartend";
    if ($conn->query($deleteQuery)) {
        // Prepare a SQL statement to insert data into the 'shiftstartend' table
        $insertQuery = "INSERT INTO shiftstartend (currentDate, shiftStart, shiftEnd) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sss", $currentDate, $shiftStart, $shiftEnd);

            foreach ($entries as $entry) {
                // Extract values from the entry
                $currentDate = $entry['currentDate'];
                $shiftStart = $entry['shiftStart'];
                $shiftEnd = $entry['shiftEnd'];

                // Execute the statement
                if ($stmt->execute()) {
                    // Entry successfully inserted
                    echo "Entry inserted for Date: $currentDate, Shift Start: $shiftStart, Shift End: $shiftEnd<br>";
                } else {
                    // Error in executing the statement
                    echo "Error inserting entry for Date: $currentDate, Shift Start: $shiftStart, Shift End: $shiftEnd. Error: " . $stmt->error . "<br>";
                }
            }

            // Close the statement
            $stmt->close();

            // Commit the transaction if all operations were successful
            $conn->commit();
        } else {
            // Error in preparing the SQL statement
            echo "Error preparing the SQL statement: " . $conn->error . "<br>";
        }
    } else {
        // Error in executing the DELETE query
        echo "Error deleting existing entries: " . $conn->error . "<br>";
    }

    // Close the database connection
    $conn->close();
} else {
    // Invalid or missing input data
    echo "Invalid or missing input data.<br>";
}
?>
