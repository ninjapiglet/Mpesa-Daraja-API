<?php
//YOUR MPESA API KEYS
$consumerKey = "fCTqcGPEXzzgjZiEPwoFwRECVy81IUFpUBQ6Q1ZvJAL099mL";
$consumerSecret = "KwZAnEtnfBOMJ2bXsQkqCi8EQmcku0R5UXciz8quzbdWwvwiUVVfuWf4zVrOEZqs";

//ACCESS TOKEN URL
$access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$headers = ['Content-Type:application/json; charset=utf8'];

// cURL setup
$curl = curl_init($access_token_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HEADER, FALSE);

// Authentication credentials
curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);

// Set the certificate path explicitly
curl_setopt($curl, CURLOPT_CAINFO, "C:/xampp/php/extras/ssl/cacert.pem");

// Execute cURL
$result = curl_exec($curl);

// Decode the result
$result = json_decode($result);

// Access the access token
echo $access_token = $result->access_token;

// Close cURL
curl_close($curl);
?>
