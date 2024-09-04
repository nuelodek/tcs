<?php
include 'db.php';

// Get all POST data from updateusers.php
$id = $_POST['id'];
$username = $_POST['username'];
$institutional_email = $_POST['institutional_email'];
$identification_number = $_POST['identification_number'];
$email = $_POST['email'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$user_id = $_POST['user_id'];

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
             WHERE Id = " . intval($user_id);

mysqli_query($conn, $update_users);

// Update Moodle user using core_user_update_users function
$moodle_user = [
  'id' => intval($user_id),
  'username' => $username,
  'email' => $email,
  'firstname' => $firstname,
  'lastname' => $lastname,
  'phone1' => $phone,
  'institution' => $institutional_email,
  'idnumber' => $identification_number
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

// Echo statement about Moodle update
echo "Moodle user update response: " . $response;

// Delete the record from temp_update table
$delete_temp = "DELETE FROM tempupdate WHERE Id = " . intval($id);
mysqli_query($conn, $delete_temp);

mysqli_close($conn);

// Redirect back to admin.php with countdown
?>
<!DOCTYPE html>
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
</html>