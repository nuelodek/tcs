<?php

// Moodle API endpoint to fetch course categories (faculties)
$apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';

// Token obtained from the previous step
$token = 'aec70f7a576bdc87212663cdccc3f76a';

// Parameters for the API call
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_course_get_categories',
    'moodlewsrestformat' => 'json'
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
    $categories = json_decode($response, true);

    // Display categories in a select form
    if (isset($categories['errorcode'])) {
        echo 'Error: ' . $categories['message'];
    } elseif (isset($categories['exception'])) {
        echo 'Exception: ' . $categories['exception'] . ' - ' . $categories['errorcode'] . ': ' . $categories['message'];
    } else {
        if (!empty($categories)) {
            echo '<select name="faculty">';
            foreach ($categories as $category) {
                if ($category['parent'] == 0) {
                    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                }
            }
            echo '</select>';
        } else {
            echo 'No faculties found.';
        }
    }
}

// Close cURL session
curl_close($ch);

?>
