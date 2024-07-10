<?php

$url = 'https://informaticajv.net/prueba/user/profile.php?id=5';

// Make the API call
$response = file_get_contents($url);

// Check if the request was successful
if ($response === false) {
    // Handle error
    echo "Error fetching data.";
} else {
    // Process the response
    echo $response;
}
?>
