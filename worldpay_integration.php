<?php

// Define your credentials (replace with your actual credentials)
$username = 'bDEV20YPjPLJq1qR';
$password = 'EEMqunygTdzF2D5AZ34S8GMhENEumetrPTCy4sfYhLVszVmPIRMcf0CPgOj9O8jN';

// Encode credentials in base64
$base64Credentials = base64_encode($username . ':' . $password);

// API endpoint URL
$apiUrl = 'https://try.access.worldpay.com/payment_pages';

// Get payment card details from the POST request
$cardNumber = $_POST['cardNumber']; // Replace with the actual form field names
$expirationDate = $_POST['expirationDate'];
$cvv = $_POST['cvv'];
$billingAddress = $_POST['billingAddress'];
$amount = 10.00; // Set the payment amount

// Prepare the JSON payload for the POST request
$postData = json_encode([
    "transactionReference" => "transactionTest",
    "merchant" => [
        "entity" => "PO4053958154"
    ],
    "narrative" => [
        "line1" => "Vail Bookings"
    ],
    "value" => [
        "currency" => "GBP",
        "amount" => $amount
    ],
    "paymentCard" => [
        "cardNumber" => $cardNumber,
        "expirationDate" => $expirationDate,
        "cvv" => $cvv,
        "billingAddress" => $billingAddress
    ]
]);

// Initialize cURL session for the POST request
$ch = curl_init($apiUrl);

// Set cURL options for the POST request
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true); // Set to POST request
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Set the JSON payload
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . $base64Credentials,
    'Content-Type: application/vnd.worldpay.payment_pages-v1.hal+json',
    'Accept: application/vnd.worldpay.payment_pages-v1.hal+json',
]);

// Execute the cURL POST request and get the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Parse the JSON response
    $responseData = json_decode($response, true);

    // Check if the response contains the payment pages URL
    if (isset($responseData['_links']['payment:paymentPages']['href'])) {
        // Extract the payment pages URL
        $paymentPagesUrl = $responseData['_links']['payment:paymentPages']['href'];

        // Redirect the customer to the payment pages URL
        header("Location: $paymentPagesUrl");
        exit; // Ensure that the script exits after the redirect
    } else {
        echo 'Payment pages URL not found in the API response.';
    }
}

// Close cURL session
curl_close($ch);
