<?php

// Include Access Token
include 'accessToken.php';
date_default_timezone_set('Africa/Nairobi');

// Ensure access token is set
if (!isset($access_token) || empty($access_token)) {
    die("Access token is missing or empty!");
}

// Configuration
$processrequestUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$callback_url = "https://wildlife-bloomberg-formerly-february.trycloudflare.com/callback.php";
$passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
$BusinessShortCode = '174379';
$Timestamp = date('YmdHis');

// Generate M-PESA Password
$Password = base64_encode($BusinessShortCode . $passkey . $Timestamp);

// Transaction details
$phone = '254720918537';
$money = '1';
$AccountReference = 'FIRST WE FEAST';
$TransactionDesc = 'stkpush test';

$stkpushheader = [
    'Content-Type:application/json',
    'Authorization:Bearer ' . $access_token
];

$curl_post_data = [
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $Password,
    'Timestamp' => $Timestamp,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $money,
    'PartyA' => $phone,
    'PartyB' => $BusinessShortCode, // Correct: STK Push is PayBill, not phone
    'PhoneNumber' => $phone,
    'CallBackURL' => $callback_url,
    'AccountReference' => $AccountReference,
    'TransactionDesc' => $TransactionDesc
];

$data_string = json_encode($curl_post_data);

// INITIATE cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $processrequestUrl);
curl_setopt($curl, CURLOPT_HTTPHEADER, $stkpushheader);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

// Explicitly define SSL certificate for cURL
curl_setopt($curl, CURLOPT_CAINFO, "C:/xampp/php/extras/ssl/cacert.pem");

// Execute and check for cURL errors
$curl_response = curl_exec($curl);

if ($curl_response === false) {
    echo " cURL Error: " . curl_error($curl);
    curl_close($curl);
    exit;
}
curl_close($curl);

// DEBUG raw response
echo "<pre>RAW RESPONSE:\n";
var_dump($curl_response);
echo "</pre>";

// Decode JSON
$data = json_decode($curl_response);

if (is_null($data)) {
    echo "Invalid JSON received. Please check your cURL response.";
    exit;
}

// Extract and Display
$CheckoutRequestID = $data->CheckoutRequestID ?? 'Not available';
$ResponseCode = $data->ResponseCode ?? 'Not available';

echo "CheckoutRequestID: $CheckoutRequestID<br>";
echo "ResponseCode: $ResponseCode<br>";

if ($ResponseCode == "0") {
    echo "STK Push initiated successfully.";
} else {
    echo "STK Push failed. Check response for errors.";
}

?>
