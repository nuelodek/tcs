<?php

// Moodle API endpoint to fetch users
$apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';

// Token obtained from the previous step
$token = '414bb1e4f9b439c396b298d4f2e97463';

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

// Fetch all users
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_user_get_users',
    'moodlewsrestformat' => 'json',
    'criteria' => []
];

$users = call_moodle_api($apiUrl, $params);

// Check for errors
if (isset($users['errorcode'])) {
    echo 'Error: ' . $users['message'];
} elseif (isset($users['exception'])) {
    echo 'Exception: ' . $users['exception'] . ' - ' . $users['errorcode'] . ': ' . $users['message'];
} else {
    // Collect user IDs
    $userIds = array_column($users, 'id');
    
    if (!empty($userIds)) {
        // Parameters to fetch user details by ID
        $params = [
            'wstoken' => $token,
            'wsfunction' => 'core_user_get_users_by_field',
            'moodlewsrestformat' => 'json',
            'field' => 'id',
            'values' => $userIds
        ];

        // Fetch user details
        $userDetails = call_moodle_api($apiUrl, $params);

        // Check for errors
        if (isset($userDetails['errorcode'])) {
            echo 'Error: ' . $userDetails['message'];
        } elseif (isset($userDetails['exception'])) {
            echo 'Exception: ' . $userDetails['exception'] . ' - ' . $userDetails['errorcode'] . ': ' . $userDetails['message'];
        } else {
            // Display user information
            if (!empty($userDetails)) {
                echo '<pre>';
                foreach ($userDetails as $user) {
                    print_r($user);
                }
                echo '</pre>';
            } else {
                echo 'No user details found.';
            }
        }
    } else {
        echo 'No users found.';
    }
}
