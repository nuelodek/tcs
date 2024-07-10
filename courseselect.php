<?php

// Moodle API endpoint to fetch courses
$apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';

// Token obtained from the previous step
$token = 'aec70f7a576bdc87212663cdccc3f76a';

// Parameters for the API call
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_course_get_courses',
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
    $courses = json_decode($response, true);

    // Display courses in a select form
    if (isset($courses['errorcode'])) {
        echo 'Error: ' . $courses['message'];
    } else {
        echo '<select name="course">';
        foreach ($courses as $course) {
            echo '<option value="' . $course['id'] . '">' . $course['fullname'] . '</option>';
        }
        echo '</select>';
    }
}

// Close cURL session
curl_close($ch);

?>
<?php

// Moodle API endpoint to fetch categories (faculties)
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

    // Display faculties in a select form
    if (isset($categories['errorcode'])) {
        echo 'Error: ' . $categories['message'];
    } else {
        echo '<select name="faculty">';
        foreach ($categories as $category) {
            echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
        }
        echo '</select>';
    }
}

// Close cURL session
curl_close($ch);

?>
<?php

// Moodle API endpoint to fetch users
$apiUrl = 'https://informaticajv.net/prueba/webservice/rest/server.php';

// Token obtained from the previous step
$token = '414bb1e4f9b439c396b298d4f2e97463';

// Parameters for the API call
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_user_get_users',
    'moodlewsrestformat' => 'json',
    'criteria' => [
        [
            'key' => 'username',
            'value' => '%'
        ]
    ]
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

    if (isset($data['errorcode'])) {
        echo 'Error: ' . $data['message'];
    } else {
        // Display users in a select form
        $users = $data['users'];
        echo '<select name="user">';
        foreach ($users as $user) {
            echo '<option value="' . $user['id'] . '">' . $user['fullname'] . '</option>';
        }
        echo '</select>';
    }
}

// Close cURL session
curl_close($ch);

?>

<?php

// Moodle API endpoint to fetch user grades
$apiUrl = 'https://yourmoodlesite.com/webservice/rest/server.php';

// Token obtained from the previous step
$token = 'your_access_token';

// Parameters for the API call
$params = [
    'wstoken' => $token,
    'wsfunction' => 'core_grades_get_grades',
    'moodlewsrestformat' => 'json',
    'courseid' => 2 // Replace with the actual course ID
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
    $grades = json_decode($response, true);

    // Display grades
    if (isset($grades['errorcode'])) {
        echo 'Error: ' . $grades['message'];
    } else {
        echo '<pre>';
        print_r($grades);
        echo '</pre>';
    }
}

// Close cURL session
curl_close($ch);

?>
