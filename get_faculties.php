<?php
require_once 'db.php'; // Assuming this file contains your database connection

// Function to call Moodle API
function call_moodle_api($url, $params) {
    // Build URL with parameters
    $url = $url . '?' . http_build_query($params);
    
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
        echo 'Error fetching data: ' . curl_error($ch);
        curl_close($ch);
        return false;
    }
    
    // Close cURL session
    curl_close($ch);
    
    // Decode JSON response
    return json_decode($response, true);
}

// Moodle API details
$apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';
$token = '414bb1e4f9b439c396b298d4f2e97463'; // Your Moodle API token

// Parameters for Moodle API call
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_course_get_categories',
    'moodlewsrestformat' => 'json'
];

$faculties = call_moodle_api($apiUrl, $params);

if ($faculties) {
    echo json_encode($faculties);
} else {
    echo json_encode([]);
}
?>
