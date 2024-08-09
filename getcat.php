<?php
// db.php should include database connection

header('Content-Type: application/json');

$moodle_url = 'https://informaticajv.net/prueba/webservice/rest/server.php';
$token = 'aaa9b3ecc791044b0bd74c009882b074';
$function = 'core_course_get_categories';

// Prepare the request parameters
$params = array(
    'wstoken' => $token,
    'wsfunction' => $function,
    'moodlewsrestformat' => 'json'
);

// Make the API request
$ch = curl_init($moodle_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
$response = curl_exec($ch);
curl_close($ch);

// Decode the JSON response
$categories = json_decode($response, true);

// Generate the select element
echo '<select name="category" id="category">';
foreach ($categories as $category) {
    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
}
echo '</select>';

$conn->close();