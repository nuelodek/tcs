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

// Initialize console log array
$consoleLog = [];

// Check if the request was successful
if ($response === false) {
    // Handle error
    $consoleLog[] = 'Error fetching data: An error occurred while fetching data.';
} else {
    // Decode JSON response
    $categories = json_decode($response, true);

    // Process categories and insert into database
    if (isset($categories['errorcode'])) {
        $consoleLog[] = 'Error: An error occurred while processing the response.';
    } elseif (isset($categories['exception'])) {
        $consoleLog[] = 'Exception: An exception occurred while processing the response.';
    } else {
        if (!empty($categories)) {
            // Assuming you have a database connection established
            include 'db.php';

            if ($conn->connect_error) {
                die('Database connection failed: A database error occurred.');
            }
            
            foreach ($categories as $category) {
                $id = $category['id'];
                $name = $conn->real_escape_string($category['name']);
                $parent = $category['parent'];
                
                if ($parent == 0) {
                    // Check if the faculty ID already exists
                    $checkQuery = "SELECT * FROM Faculties WHERE Id = $id";
                    $result = $conn->query($checkQuery);
                    
                    if ($result->num_rows == 0) {
                        // Insert new faculty
                        $insertQuery = "INSERT INTO Faculties (Id, Name) VALUES ($id, '$name')";
                        if ($conn->query($insertQuery) === TRUE) {
                            $consoleLog[] = 'New faculty inserted successfully.';
                        } else {
                            $consoleLog[] = 'Error inserting faculty: A database error occurred.';
                        }
                    } else {
                        // Update existing faculty
                        $updateQuery = "UPDATE Faculties SET Name = '$name' WHERE Id = $id";
                        if ($conn->query($updateQuery) === TRUE) {
                            $consoleLog[] = 'Faculty updated successfully.';
                        } else {
                            $consoleLog[] = 'Error updating faculty: A database error occurred.';
                        }
                    }
                } else {
                    // Check if the category ID already exists
                    $checkQuery = "SELECT * FROM Categories WHERE Id = $id";
                    $result = $conn->query($checkQuery);
                    
                    if ($result->num_rows == 0) {
                        // Insert new category
                        $insertQuery = "INSERT INTO Categories (Id, Name, FacultyId, Status) VALUES ($id, '$name', $parent, 'approved')";
                        if ($conn->query($insertQuery) === TRUE) {
                            $consoleLog[] = 'New category inserted successfully.';
                        } else {
                            $consoleLog[] = 'Error inserting category: A database error occurred.';
                        }
                    } else {
                        // Update existing category
                        $updateQuery = "UPDATE Categories SET Name = '$name', FacultyId = $parent, Status = 'approved' WHERE Id = $id";
                        if ($conn->query($updateQuery) === TRUE) {
                            $consoleLog[] = 'Category updated successfully.';
                        } else {
                            $consoleLog[] = 'Error updating category: A database error occurred.';
                        }
                    }
                }
            }
            
            // $conn->close();
        } else {
            $consoleLog[] = 'No categories found.';
        }
    }
}

// Close cURL session
curl_close($ch);

// Output console log
echo "<script>console.log(" . json_encode($consoleLog) . ");</script>";

?>
