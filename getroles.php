<?php
require_once 'db.php'; // Assuming this file contains your database connection

// Function to call Moodle API
function call_moodle_api($url, $params) {
    // Initialize cURL session
    $ch = curl_init();
    
    // Build URL with parameters
    $url = $url . '?' . http_build_query($params);
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Execute the request
    $response = curl_exec($ch);
    
    // Check if the request was successful
    if ($response === false) {
        // Handle cURL error
        echo 'cURL Error: ' . curl_error($ch);
        curl_close($ch);
        return false;
    }
    
    // Close cURL session
    curl_close($ch);
    
    // Decode JSON response
    $decoded_response = json_decode($response, true);
    
    // Check for Moodle API exception
    if (isset($decoded_response['exception'])) {
        echo 'Moodle API Error: ' . $decoded_response['message'];
        return false;
    }
    
    return $decoded_response;
}

// Moodle API details
$apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';
$token = '414bb1e4f9b439c396b298d4f2e97463'; // Your Moodle API token

// Parameters for Moodle API call to assign roles (example)
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_role_assign_roles',
    'moodlewsrestformat' => 'json',
    // Add additional parameters as needed for role assignment
    'roleid' => 5, // Example role ID
    'userid' => 10, // Example user ID
    'contextid' => 15 // Example context ID
];

// Call Moodle API to assign roles
$assign_result = call_moodle_api($apiUrl, $params);

// Check if roles were assigned successfully
if ($assign_result !== false) {
    echo json_encode($assign_result);
} else {
    echo json_encode(['error' => 'Failed to assign roles using Moodle API.']);
}
?>
