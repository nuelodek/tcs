<?php

// Moodle API endpoint to fetch users
$apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';

// Token obtained from the previous step
$token = '414bb1e4f9b439c396b298d4f2e97463';

// Parameters for the API call to fetch all users
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_user_get_users',
    'moodlewsrestformat' => 'json',
    'criteria' => [] // Empty criteria to fetch all users
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
    $data = json_decode($response, true);

    // Display user information or errors
    if (isset($data['errorcode'])) {
        echo 'Error: ' . $data['message'];
    } elseif (isset($data['exception'])) {
        echo 'Exception: ' . $data['exception'] . ' - ' . $data['errorcode'] . ': ' . $data['message'];
    } else {
        // Display user information
        if (!empty($data['users'])) {
            // Store users in a variable for later use
            $users = $data['users'];
            
            // Echo user information
            foreach ($users as $user) {
                echo 'ID: ' . $user['id'] . '<br>';
                echo 'Username: ' . $user['username'] . '<br>';
                echo 'Full Name: ' . $user['fullname'] . '<br>';
                echo 'Email: ' . $user['email'] . '<br>';
                echo '<hr>';
            }
        } else {
            echo 'No users found.';
        }
    }
}

// Close cURL session
curl_close($ch);

