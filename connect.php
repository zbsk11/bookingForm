<?php
	$firstName = $_POST['firstName'];
	$selectedDay = $_POST['selectedDay']; // Change the variable name to selectedDay
	$timeSlot = $_POST['timeSlot'];
	$serviceName = $_POST['serviceName'];
	$email = $_POST['email'];
	$number = $_POST['number'];

	// Database connection
	$conn = new mysqli('localhost','root','','test');
	if($conn->connect_error){
		echo "$conn->connect_error";
		die("Connection Failed : ". $conn->connect_error);
	} else {
		$stmt = $conn->prepare("insert into registration(firstName, bookingDay, timeSlot, serviceName, email, number) values(?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssi", $firstName, $selectedDay, $timeSlot, $serviceName, $email, $number); // Change bookingDay to selectedDay
		$execval = $stmt->execute();
		echo $execval;
		echo "Registration successful...";
		$stmt->close();
		$conn->close();
	}
?>
