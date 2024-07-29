<?php
require_once 'db.php'; // Assuming this file contains your database connection

header('Content-Type: application/json');

// Function to call Moodle API
function call_moodle_api($url, $params) {
    $url = $url . '?' . http_build_query($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if ($response === false) {
        echo 'Error fetching data: ' . curl_error($ch);
        curl_close($ch);
        return false;
    }
    curl_close($ch);
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

$categories = call_moodle_api($apiUrl, $params);

$result = [];
if ($categories) {
    foreach ($categories as $category) {
        $result[] = [
            'Id' => $category['id'],
            'Name' => $category['name']
        ];
    }
}

echo json_encode($result);
