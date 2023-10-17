<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'zbookingagency@gmail.com';
        $mail->Password = 'akxgautebdzkadcr';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('zbookingagency@gmail.com');

        $firstName = $_POST['firstName'];
        $selectedDay = $_POST['selectedDay']; // Change the variable name to selectedDay
        $timeSlot = $_POST['timeSlot'];
        $serviceName = $_POST['serviceName'];
        $email = $_POST['email'];
        $number = $_POST['number'];

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = 'Booking Confirmation'; // Set subject to 'Booking Confirmation'

        // Create the email body using form input values
        $mail->Body = "Hello $firstName,<br><br>Your booking details:<br>";
        $mail->Body .= "Selected Day: $selectedDay<br>";
        $mail->Body .= "Time Slot: $timeSlot<br>";
        $mail->Body .= "Service Name: $serviceName<br>";
        $mail->Body .= "Phone Number: $number<br><br>Thank you for your booking.";

        if ($mail->send()) {
            echo "
                <script>
                    alert('Booking Confirmation Email Sent Successfully');
                    document.location.href = 'emailIndex.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');
                    document.location.href = 'emailIndex.php';
                </script>
            ";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
