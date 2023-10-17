<!DOCTYPE html>
<html>
<head>
    <title>Worldpay Payment Test</title>
</head>
<body>
    <h1>Worldpay Payment Test</h1>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Define your Worldpay credentials
        $username = "bwH6QHPgPl5q0D6L"; // Replace with your actual username
        $password = "ttOQeW3KcE0REFT90hDZBpVmbpmZIeGDZbJ3uBvWajiQbGFwnuLwhZ3a2AJdIgkp"; // Replace with your actual password

        // Encode credentials in Base64
        $credentials = base64_encode($username . ':' . $password);

       // Define the sale data as an associative array
        $sale_data = array(
            "transactionReference" => "testTransaction",
            "merchant" => array(
                "entity" => "PO4053958154"
            ),
            "narrative" => array(
                "line1" => "VailBookings"
            ),
            "value" => array(
                "currency" => "GBP",
                "amount" => 10
            )
        );

        // Convert the sale data to JSON format
        $sale_data_json = json_encode($sale_data);


        // Define the URL for the payment_pages:setup action
        $url = "https://try.access.worldpay.com/payment_pages:setup";

        // Initialize a cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sale_data_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/vnd.worldpay.payment_pages-v1.hal+json',
            'Accept: application/vnd.worldpay.payment_pages-v1.hal+json'
        ));

        // Execute the cURL session and retrieve the response
        $response = curl_exec($ch);
        echo "Response from Worldpay:";
        echo $response;

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close the cURL session
        curl_close($ch);

        // Check the response from Worldpay
        if ($response !== false) {
            // Decode the JSON response
            $response_data = json_decode($response, true);

            // Display the response data for debugging
            echo "Response from Worldpay:";
            echo '<pre>';
            print_r($response_data);
            echo '</pre>';

            // Check if payment was successful and there is a redirect URL
            if (isset($response_data['paymentStatus']) && $response_data['paymentStatus'] === 'SUCCESS' && isset($response_data['redirectURL'])) {
                // Perform the redirection
                header('Location: ' . $response_data['redirectURL']);
                exit; // Exit to ensure the user is redirected immediately
            } else {
                // Handle other cases, e.g., payment failure
                echo "Payment was not successful. Please try again later.";
            }
        } else {
            echo "Error sending payment request.";
        }
    }
    ?>

    <form method="POST">
        <input type="submit" value="Make Payment">
    </form>
</body>
</html>
