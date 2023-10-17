<!DOCTYPE html>
<html>
<head>
    <title>Send SMS</title>
</head>
<body>
    <h2>Send SMS</h2>
    <button onclick="sendSMS()">Send SMS</button>

    <script>
        function sendSMS() {
            // Make an AJAX request to your PHP file to send the SMS
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "send_sms.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert(xhr.responseText); // Display the response from the PHP file
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
