<?php
include 'db.php';

// Get all POST data from updateusers.php

$temp_id = isset($_POST['temp_id']) ? $_POST['temp_id'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$institutional_email = isset($_POST['institutional_email']) ? $_POST['institutional_email'] : '';
$identification_number = isset($_POST['identification_number']) ? $_POST['identification_number'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$module_id = isset($_POST['module_id']) ? $_POST['module_id'] : '';

// Debug: Print all POST data
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Check if required fields are not empty
if (empty($id) || empty($username) || empty($email) || empty($firstname) || empty($lastname)) {
    die("Error: Required fields are missing.");
}

// Update users table
$update_users = "UPDATE users SET 
             Username = '" . mysqli_real_escape_string($conn, $username) . "',
             Institutional_Email = '" . mysqli_real_escape_string($conn, $institutional_email) . "',
             Identification_Number = '" . mysqli_real_escape_string($conn, $identification_number) . "',
             Email = '" . mysqli_real_escape_string($conn, $email) . "',
             First_Name = '" . mysqli_real_escape_string($conn, $firstname) . "',
             Last_Name = '" . mysqli_real_escape_string($conn, $lastname) . "',
             Phone = '" . mysqli_real_escape_string($conn, $phone) . "',
             Password = '" . mysqli_real_escape_string($conn, $password) . "',
             Updated_at = NOW()
             WHERE Id = " . intval($id);

$result = mysqli_query($conn, $update_users);

if (!$result) {
    die("Error updating user in database: " . mysqli_error($conn));
}

// Update Moodle user using core_user_update_users function
$moodle_user = [
  'id' => intval($module_id),
  'username' => $username,
  'email' => $email,
  'firstname' => $firstname,
  'lastname' => $lastname,
  'phone1' => $phone,
  'institution' => $institutional_email,
  'idnumber' => $identification_number,
  'password' => $password
];

$moodle_url = 'https://informaticajv.net/prueba/webservice/rest/server.php';
$token = 'aaa9b3ecc791044b0bd74c009882b074';
$function = 'core_user_update_users';

$moodle_params = ['users' => [$moodle_user]];

// Call the Moodle web service function
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $moodle_url . '?wstoken=' . $token . '&wsfunction=' . $function . '&moodlewsrestformat=json');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($moodle_params));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

// Check Moodle update response
$response_data = json_decode($response, true);
if (isset($response_data['warnings']) && !empty($response_data['warnings'])) {
    echo "Warning: Moodle user update failed. " . $response_data['warnings'][0]['message'];
} else {
    echo "Moodle user update successful.";
}

// Delete the record from temp_update table
if (!empty($temp_id)) {
    $delete_temp = "DELETE FROM tempupdate WHERE Id = " . intval($temp_id);
    $delete_result = mysqli_query($conn, $delete_temp);
    if (!$delete_result) {
        echo "Error deleting record from tempupdate: " . mysqli_error($conn);
    } else {
        echo "Record deleted successfully from tempupdate.";
    }
}

mysqli_close($conn);

// Redirect back to admin.php with countdown
?>
<!-- <!DOCTYPE html>
<html>
<head>
  <title>Redirecting...</title>
  <script>
      var seconds = 5;
      function countdown() {
          document.getElementById('countdown').innerHTML = seconds;
          if (seconds > 0) {
              seconds--;
              setTimeout(countdown, 1000);
          } else {
              window.location.href = 'admin.php';
          }
      }
  </script>
</head>
<body onload="countdown()">
  <p>Update successful. Redirecting to admin page in <span id="countdown">5</span> seconds...</p>
  <p>Moodle update response: <span id="moodleResponse"></span></p>
  <script>
      document.getElementById('moodleResponse').innerHTML = <?php echo json_encode($response); ?>;
  </script>
</body>
</html> -->