<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Send Email</title> 
</head>
<body> 
    <form class="" action="emailIndex.php" method="post"> 
        Email <input type="email" name="email" value=""> <br>
        Number <input type="text" name="number" value=""> <br>
        Selected Day <input type="text" name="selectedDay" value=""> <br>
        Time Slot <input type="text" name="timeSlot" value=""> <br>
        First Name <input type="text" name="firstName" value=""> <br>
        Service Name <input type="text" name="serviceName" value=""> <br> <!-- Added input field for serviceName -->
        <button type="submit" name="send">Send</button>
    </form>
</body>
</html>
