<?php

// Moodle API endpoint for generating token
$tokenUrl = 'https://informaticajv.net/prueba/login/token.php';

// Parameters for the token request
$params = [
    'service' => 'moodle_mobile_app',
    'username' => 'admin',
    'password' => 'OSh4@3sbZ'
];

// Build URL with parameters
$url = $tokenUrl . '?' . http_build_query($params);

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request
$response = curl_exec($ch);

// Check if the request was successful
if ($response === false) {
    // Handle error
    echo 'Error fetching token: ' . curl_error($ch);
} else {
    // Decode JSON response
    $tokenData = json_decode($response, true);

    // Check for errors in the response
    if (isset($tokenData['error'])) {
        echo 'Error: ' . $tokenData['error'];
    } else {
        // Extract the token
        $token = $tokenData['token'];
        echo 'Token: ' . $token;
    }
}

// Close cURL session
curl_close($ch);

?>
