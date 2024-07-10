<?php

// Moodle API endpoint to fetch users by field
$apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';

// Token obtained from the previous step
$token = 'aec70f7a576bdc87212663cdccc3f76a';

// Parameters for the API call
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_user_get_users_by_field',
    'moodlewsrestformat' => 'json',
    'field' => 'id',  // Specify the field to search by (e.g., 'id')
    'values' => [7]   // Replace with the actual user ID(s)
];

// Build URL with parameters
$url = $apiUrl . '?' . http_build_query($params);

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
} else {
    // Decode JSON response
    $users = json_decode($response, true);

    // Display user information or errors
    if (isset($users['errorcode'])) {
        echo 'Error: ' . $users['message'];
    } elseif (isset($users['exception'])) {
        echo 'Exception: ' . $users['exception'] . ' - ' . $users['errorcode'] . ': ' . $users['message'];
    } else {
        // Display user information
        if (!empty($users)) {
            echo '<pre>';
            print_r($users);
            echo '</pre>';
        } else {
            echo 'No users found.';
        }
    }
}

// Close cURL session
curl_close($ch);

?>
