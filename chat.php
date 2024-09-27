<?php
session_start();

$host = "localhost";
$username = "thevirt1_useme";
$password = "1]U6jf77Sl+WMj";
$database = "thevirt1_useme";

try {
    // Create a connection to the MySQL database
    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Define your SQL query with placeholders
    $sql = "SELECT chat_url, adviser_name, explorer_name, explorer_email, adviser_email FROM advicechat";

    // Use prepared statements to add placeholders for user input
    $stmt = $conn->prepare($sql);

    // Check for SQL query preparation errors
    if (!$stmt) {
        die("SQL query preparation error: " . $conn->error);
    }

    // Execute the prepared statement
    if (!$stmt->execute()) {
        die("SQL query execution error: " . $stmt->error);
    }

    // Get the result set
    $result = $stmt->get_result();

    // Initialize variables outside the loop
    $explorer_name = "";
    $adviser_name = "";
    $explorer_email = "";
    $adviser_email = "";

    // Check if there are rows returned
    if ($result->num_rows > 0) {
        $explorerUniqueId = isset($_GET['unique_id1']) ? $_GET['unique_id1'] : null;
        $adviserUniqueId = isset($_GET['unique_id2']) ? $_GET['unique_id2'] : null;

        while ($row = $result->fetch_assoc()) {
            $chatUrlFromDB = urldecode($row["chat_url"]);
            $parts = parse_url($chatUrlFromDB);
            parse_str($parts['query'], $urlParams);

            if (
                isset($urlParams['unique_id1']) && $urlParams['unique_id1'] === $explorerUniqueId
                && isset($urlParams['unique_id2']) && $urlParams['unique_id2'] === $adviserUniqueId
            ) {
                // Handle the match here
                $adviser_name = $row["adviser_name"]; // Retrieve the adviser's name from the database
                $explorer_name = $row["explorer_name"]; // Retrieve the explorer's name from the database
                $explorer_email = $row["explorer_email"]; // Retrieve the explorer's email from the database
                $adviser_email = $row["adviser_email"];

                // Output a message when records are matched (optional)
                // echo "Match found!";
                break; // Exit the loop when a match is found
            }
        }
    } else {
        // No records found
        echo "No records found.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

$userCategory = $_SESSION['category'];

$chatStatus = ''; // Initialize chatStatus variable

// Retrieve variables from the URL
$explorerUniqueId = isset($_GET['unique_id1']) ? $_GET['unique_id1'] : null;
$adviserUniqueId = isset($_GET['unique_id2']) ? $_GET['unique_id2'] : null;
$start_time_12h = isset($_GET['start_time']) ? $_GET['start_time'] : null;
$end_time_12h = isset($_GET['end_time']) ? $_GET['end_time'] : null;
$created_at = isset($_GET['created_at']) ? $_GET['created_at'] : null;

// Check if required parameters are not empty
if (!empty($created_at) && !empty($start_time_12h) && !empty($end_time_12h)) {
    // Convert the date and time parameters to timestamps
    $created_at_timestamp = strtotime(urldecode($created_at));
    $start_time_timestamp = strtotime(date("Y-m-d", $created_at_timestamp) . " " . urldecode($start_time_12h));
    $end_time_timestamp = strtotime(date("Y-m-d", $created_at_timestamp) . " " . urldecode($end_time_12h));
    $current_time = strtotime("now");
}

// First block starts here
if ($start_time_timestamp < $created_at_timestamp) {
    // Calculate the next day based on $start_time
    $next_day = strtotime("+1 day", $start_time_timestamp);

    // Calculate the start time and end time for the session on the next day
    $new_start_time = $next_day;
    $new_end_time = strtotime("+1 day", $end_time_timestamp); // Adjusted to end on the next day

    if ($current_time < $new_start_time) {
        // Calculate the waiting time in seconds until $new_start_time
        $waiting_time = $new_start_time - $current_time;

        // Convert waiting time to hours and minutes
        $waiting_hours = floor($waiting_time / 3600);
        $waiting_minutes = floor(($waiting_time % 3600) / 60); 

        if ($userCategory === 'Adviser') {
            $chatStatus .= "Dear $adviser_name, <br> You'd need to wait for <b>{$waiting_hours} hours and {$waiting_minutes} minutes</b> before you can chat with $explorer_name.";
            $chatStatus .= ' <p>Kindly <a href="adviserdashboard.php">return to your adviser dashboard</a></p>';
            echo $chatStatus;
            exit; // Terminate the script
        } elseif ($userCategory === 'Explorer') {
            $chatStatus .= "Dear $explorer_name, <br> You'd need to wait for <b>{$waiting_hours} hours and {$waiting_minutes} minutes</b> before you can chat with $adviser_name.";
            $chatStatus .= ' <p>Kindly <a href="explorerdashboard.php">return to your explorer dashboard</a></p>';
            echo $chatStatus;
            exit; // Terminate the script
        }
    } elseif ($current_time < $new_end_time) {
        if ($userCategory === 'Adviser') {
            // $chatStatus .= "Dear $adviser_name, you can access the chat now.";
        } elseif ($userCategory === 'Explorer') {
            // $chatStatus .= "Dear $explorer_name, you can access the chat now.";
        }
    } else {
        if ($userCategory === 'Adviser') {
            $chatStatus .= "Dear $adviser_name, Hooooorah! <br> Your chat session with $explorer_name has ended.";
            $chatStatus .= ' <p>Kindly <a href="adviserdashboard.php">return to your adviser dashboard</a></p>';
            echo $chatStatus;
            exit; // Terminate the script
        } elseif ($userCategory === 'Explorer') {
            $chatStatus .= "Dear $explorer_name, Hooooorah! <br> Your chat session with $adviser_name has ended.";
            $chatStatus .= "<p><a href='Rate Adviser.php'>Rate $adviser_name</a> or <a href='explorerdashboard.php'>Return to Your Dashboard</a></p>";
            echo $chatStatus;
            exit; // Terminate the script
        }
    }

    // Second block starts here
} else {
    if ($current_time < $start_time_timestamp) {
        // Calculate the waiting time in seconds until $new_start_time
        $waiting_time = $start_time_timestamp - $current_time;

        // Convert waiting time to hours and minutes
        $waiting_hours = floor($waiting_time / 3600);
        $waiting_minutes = floor(($waiting_time % 3600) / 60);

        if ($userCategory === 'Adviser') {
            $chatStatus .= "Dear $adviser_name, <br> You'd need to wait for <b>{$waiting_hours} hours and {$waiting_minutes} minutes</b> before you can chat with $explorer_name.";
            $chatStatus .= ' <p>Kindly <a href="adviserdashboard.php">return to your adviser dashboard</a></p>';
            echo $chatStatus;
            exit; // Terminate the script
        } elseif ($userCategory === 'Explorer') {
            $chatStatus .= "Dear $explorer_name, <br> You'd need to wait for <b>{$waiting_hours} hours and {$waiting_minutes} minutes</b> before you can chat with $adviser_name.";
            $chatStatus .= ' <p>Kindly <a href="explorerdashboard.php">return to your explorer dashboard</a></p>';
            echo $chatStatus;
            exit; // Terminate the script
        }
    } elseif ($current_time >= $start_time_timestamp && $current_time <= $end_time_timestamp) {
        if ($userCategory === 'Adviser') {
            //$chatStatus .= "Dear $adviser_name, you can access the chat now.";
        } elseif ($userCategory === 'Explorer') {
            //$chatStatus .= "Dear $explorer_name, you can access the chat now.";
        }
    } else {
        if ($userCategory === 'Adviser') {
            $chatStatus .= "Dear $adviser_name, Hooooorah! <br> Your chat session with $explorer_name has ended.";
            $chatStatus .= ' <p>Kindly <a href="adviserdashboard.php">return to your adviser dashboard</a></p>';
            echo $chatStatus;
            exit; // Terminate the script
        } elseif ($userCategory === 'Explorer') {
            $chatStatus .= "Dear $explorer_name, Hooooorah! <br> Your chat session with $adviser_name has ended.";
            $chatStatus .= "<p><a href='Rate Adviser.php'>Rate $adviser_name</a> or <a href='explorerdashboard.php'>Return to Your Dashboard</a></p>";
            echo $chatStatus;
            exit; // Terminate the script
        }
    }
}

// After using the variables, verify the HMACs
$explorerUniqueIdHmac = isset($_GET['explorerUniqueIdHmac']) ? $_GET['explorerUniqueIdHmac'] : null;
$adviserUniqueIdHmac = isset($_GET['adviserUniqueIdHmac']) ? $_GET['adviserUniqueIdHmac'] : null;
$start_time_12hHmac = isset($_GET['start_time_12hHmac']) ? $_GET['start_time_12hHmac'] : null;
$end_time_12hHmac = isset($_GET['end_time_12hHmac']) ? $_GET['end_time_12hHmac'] : null;
$created_atHmac = isset($_GET['created_atHmac']) ? $_GET['created_atHmac'] : null;

// Define your secret key for HMAC
$secretKey = 'your_secret_key'; // Replace with the same secret key used for generating HMACs

// Verify the HMACs
if (
    hash_hmac('sha256', $explorerUniqueId, $secretKey) === $explorerUniqueIdHmac &&
    hash_hmac('sha256', $adviserUniqueId, $secretKey) === $adviserUniqueIdHmac &&
    hash_hmac('sha256', $start_time_12h, $secretKey) === $start_time_12hHmac &&
    hash_hmac('sha256', $end_time_12h, $secretKey) === $end_time_12hHmac &&
    hash_hmac('sha256', $created_at, $secretKey) === $created_atHmac
) {
    // HMACs are valid, you can proceed with your chat functionality
   // echo "HMACs valid.";
} else {
    // HMACs are not valid, handle the error
   // echo "HMACs are not valid. Access denied.";
    exit();
}

// Output the chatStatus
echo $chatStatus;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="vaicon.png">
    <title>Chat</title>
 <style>
    .file-container {
      margin-bottom: 10px;
      /*background-color: pink;*/
      width: auto;
      height: 20px;
      padding: 10px;
    }
    .file-info {
      display: inline-block;
      margin-right: 10px;
      font-size: 14px;
    }
    .remove-btn {
      cursor: pointer;
      color: red;
      font-size: 12px;
    }
    .thumbnail {
      max-width: 20px;
      max-height: 20px;
      margin-top: 5px;
    }
  </style>

    <style>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

.progress-container {
    width: 100%;
    height: 20px;
    background-color: #f3f3f3;
    border: 1px solid #ccc;
    margin: 10px 0;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    width: 0%;
    background-color: #4a90e2;
    transition: width 0.3s ease;
}




body {
            font-family: 'Open Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(rgba(255, 256, 256, 0.5), rgba(256, 0, 128, 0.5)), 
          url("https://images.pexels.com/photos/196645/pexels-photo-196645.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2") center no-repeat;
          background-size: cover;
                height: 100vh;
                margin: 0;
                font-family: "Open Sans", sans-serif;
    
            }

.container {
    display: flex;
    width: 80%;
    height: 90vh;
    background: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar {
    width: 30%;
    background: #ededed;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #ddd;
}

.sidebar-header {
    padding: 20px;
    background: #f6f6f6;
    border-bottom: 1px solid #ddd;
}

.sidebar-header h2 {
    margin: 0 0 10px;
}

.sidebar-header input {
    width: 97%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
}

.chat-list {
    flex: 1;
    overflow-y: auto;
}

.chat-item {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    cursor: pointer;
    gap: 40px;
    border-bottom: 1px solid #ddd;
}

.chat-item:hover {
    background: #f6f6f6;
}

.chat-avatar img {
    width: 40px;  /* Ensure width is equal to height */
    height: 40px;  /* Ensure height is equal to width */
    border-radius: 50%;  /* Make the image circular */
    object-fit: cover;  /* Ensure the image covers the entire area without stretching */
    margin-right: 20px;
    margin-top: 10px;
}


.chat-info h3 {
    margin: 0 0 0px;
}

.chat-info p {
    margin: 0;
    color: #666;
}

.chat-window {
    width: 70%;
    display: flex;
    overflow-y:auto;
    flex-direction: column;
}

.chat-header {
    display: flex;
    align-items: center;
    padding: 20px;
    background: #f6f6f6;
    border-bottom: 1px solid #ddd;
}

.chat-body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #fff;
}


.explorer img,  .adviser img{
    margin-top: 10px;
    width: 100%;
    border-radius: 10px;
}
.explorer {
    float: right;
    margin: 5px 0; /* Reduced margin */
    padding: 3px 12px; /* Reduced padding */
    border-radius: 5px;
    max-width: 60%;
    border:none;
    line-height: 1.5;
background: #d4f8e8; /* Very light green color */
    align-self: flex-start;
    word-wrap: break-word;
    clear: both;
    display: inline-block;
}

        
        
.explorer:last-of-type{
    margin-bottom: 20px; /* Add your desired margin-bottom value */
}



.adviser {
    float: left;
    margin: 5px 0; /* Reduced margin */
    padding: 3px 12px; /* Reduced padding */
    border-radius: 5px;
    max-width: 60%;
    line-height: 1.5;
    /*background: #4a90e2;*/
    background: #f5f5dc; 
    align-self: flex-start;
    clear: both;
    word-wrap: break-word;
   
}

.chat-footer {
    display: flex;
    align-items: center;
    padding: 10px;
    margin-top: 10px;
    border-top: 1px solid #ddd;
    background: #f6f6f6;
}

#chat-form input[type="text"] {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
    margin-right: 10px;
}

.fa-paperclip {
    margin: 10px;

}


.chat-footer button {
    background: #007bff;
    border: none;
    padding: 10px 20px;
    border-radius: 20px;
    color: white;
    cursor: pointer;
    transition: background 0.3s;
    margin-left: 10px;
}

.chat-footer button:hover {
    background: #0056b3;
}

        </style>
</head>
<body>
    
<?php
// Database connection
$host = "localhost";
$username = "thevirt1_useme";
$password = "1]U6jf77Sl+WMj";
$database = "thevirt1_useme";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Values from earlier in your code
$explorer_email = $explorer_email; // Replace with actual value
$adviser_email = $adviser_email; // Replace with actual value
$userCategory = $userCategory; // Replace with actual value

// Query based on userCategory
if ($userCategory == "Adviser") {
    $email_to_search = $explorer_email;
} else {
    $email_to_search = $adviser_email;
}

// SQL query
$sql = "SELECT * FROM testme WHERE email = '$email_to_search'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        $email = $row["email"];
        $photograph = $row["profile_picture"];
        $logout_time = $row["logout_time"];
        $login_status = $row["login_status"];
        $login_time_formatted = date("h:i A", strtotime($logout_time));

        // // Process fetched data as needed
        // // Example: echo or further process the retrieved fields
        // echo "First Name: " . $firstname . "<br>";
        // echo "Last Name: " . $lastname . "<br>";
        // echo "Email: " . $email . "<br>";
        // echo "Profile Picture: " . $photograph . "<br>";
        // echo "Login Time: " . $login_time . "<br>";
        // // ... fetch and display other fields as required
    }
} else {
    echo "0 results";
}

$conn->close();
?>


    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header" style="display: flex; flex-direction: column; align-items: center;">
                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                    <h2>Chats</h2>
                    <i class="fas fa-sign-out-alt" id="logoutButton" style="margin-top: -10px;"></i>
                </div>
                
                <input type="text" placeholder="Search or start a new chat" style="margin-top: 0px; width: 94%;">
                        </form>

            </div>
            <div class="chat-list">
                <div class="chat-item">
                    <div class="chatty" style="display: flex;">
                    <div class="chat-avatar"> <img src="<?php echo $photograph; ?>"> </div>
                    <div class="chat-info" style="margin-top: 10px;">
                    <h3 style="font-size: 14px;"><?php echo $firstname . " " . $lastname; ?></h3>
                        <small class="lastmessage"></small>
                    </div>
                    </div>
                    <div class="chat-button">
                        <button id="messagepending"> </button>
                    </div>
                </div>
            
            </div>
        </div>
        
        <div class="chat-window">
            <div class="chat-header">
                <div class="chat-avatar"><img src="<?php echo $photograph; ?>"></div>
                <div class="chat-info">
                    <h3><?php echo $firstname . " " . $lastname; ?></h3>

 <p style="font-size: 12px;">
        <?php 
        if ($login_status == 'login') {
            echo 'is active';
        } else {
            echo "last seen today at $login_time_formatted";
        }
        ?>
    </p>
    <p class="istyping" style="display: none;">
        <?php 
        if ($userCategory == 'Adviser') {
            echo "$explorer_name is typing...";
        } else {
            echo "$adviser_name is typing...";
        }
        ?>
    </p>


 
                    </div>

                <div class="chat-header-icons" style="margin-left: auto; display: flex; border: 1px solid #ccc; border-radius: 5px; transition: all 0.3s ease;">
                     <i class="fa fa-phone fa-rotate-90" style="padding: 5px; color: #666; transition: all 0.3s ease; border-right: 1px solid #ccc;"></i> 
                    <i class="fa fa-video" style="padding: 5px; color: #666; transition: all 0.3s ease; border-right: 1px solid #ccc;"></i>
                    <i class="fa fa-search" style="padding: 5px; color: #666; transition: all 0.3s ease;"></i>
                </div>
            </div>
            

                <style>
                    .timeman {
                        font-size: 8px; 
                        color: #ccc;
                    }
                    
                    .seen {
                        
                    }
                    
                </style>
                        <div class="file-container"></div>
                        <div class="chat-box chat-body" id="chatWindow"> </div>


            
            
                        <form id="chat-form" action="chat.php?unique_id1=<?php echo $_GET['unique_id1']; ?>&unique_id2=<?php echo $_GET['unique_id2']; ?>" method="POST">

            <div class="chat-footer">
            <!-- this is the form for submitting and attaching messages -->
              
            <label for="file-input" class="attachment-label" >
                                <i class="fa fa-paperclip"></i>
                                <input type="file" id="file-input" style="display:none;" multiple>
                            </label>
            <input type="text" placeholder="Type a message" id="message-input" style="margin-top: 0px; width: 94%;" class="typemessage">
            <button id="sendButton" type="submit"><i class="fa fa-paper-plane"></i></button>

            </div>
                        </form>

        </div>
    </div>
    
    
    <script>
document.getElementById("logoutButton").onclick = function() {
    // Redirect to logout.php
    window.location.href = "logout.php";
};
</script>
    

    <script>
function formatChatMessage(message) {
    const chatMessage = document.createElement('div');
    chatMessage.className = `${message.user}`;

    const textElement = document.createElement('div');
    textElement.className = '';

    const fileInfoElement = document.createElement('div');
    fileInfoElement.className = 'file-info';
    
    // Remove the date part if present and get the message text
    const dateStartIndex = message.text.indexOf('[');
    const dateEndIndex = message.text.indexOf(']');
    let messageText = message.text;
    if (dateStartIndex !== -1 && dateEndIndex !== -1) {
        messageText = message.text.substring(dateEndIndex + 1).trim();
    }

    // Check if the message contains a file attachment
    const fileStartIndex = messageText.indexOf('[File:');
    if (fileStartIndex !== -1) {
        const fileEndIndex = messageText.indexOf(']', fileStartIndex);
        if (fileEndIndex !== -1) {
            const fileAttachmentInfo = messageText.substring(fileStartIndex + 6, fileEndIndex).split('.');
            const fileName = fileAttachmentInfo[0].trim();
            const fileExtension = fileAttachmentInfo[1].trim().toLowerCase();
            const fileUrl = `https://thevirtualadviser.com.ng/uploads/${fileName}.${fileExtension}`;

            const fileType = getFileType(fileExtension);

            // Check the file type and display accordingly
            if (fileType === 'image') {
                const imgElement = document.createElement('img');
                imgElement.src = fileUrl;
                fileInfoElement.appendChild(imgElement);
            } else if (fileType === 'pdf') {
                const pdfLink = document.createElement('a');
                pdfLink.href = fileUrl;
                pdfLink.textContent = 'View PDF';
                fileInfoElement.appendChild(pdfLink);
            }

            textElement.textContent = messageText.substring(fileEndIndex + 1).trim(); // Trim the text after the file attachment
        }
    } else {
        // If it's a regular text message, display the message text as it is
        textElement.textContent = messageText;
    }

    const timeElement = document.createElement('div');
    timeElement.className = 'timeman';

    // Extract the timestamp from the message text and convert it to the desired format
    const timestampString = message.text.substring(1, 20);
    const timestamp = new Date(timestampString);
    const formattedTime = timestamp.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

    timeElement.textContent = formattedTime;

    chatMessage.appendChild(textElement);
    if (fileInfoElement.children.length > 0) {
        chatMessage.appendChild(fileInfoElement);
    }
    chatMessage.appendChild(timeElement);

    return chatMessage;
}

// Helper function to determine the file type based on the file extension
function getFileType(extension) {
    if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
        return 'image';
    } else if (extension === 'pdf') {
        return 'pdf';
    } else {
        return 'unknown';
    }
}


let userScrolled = false;
let previousMessageCount = 0;

function loadChatMessages() {
    const chatBox = document.querySelector('.chat-box');
    const lastMessageElement = document.querySelector('.lastmessage'); // Select the <small> element
    const userCategory = '<?php echo $userCategory; ?>';
    const messagepending = document.getElementById('messagepending');
    let adviserMessageCount = 0;
    let explorerMessageCount = 0;
    
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const formattedMessages = JSON.parse(xhr.responseText);

            chatBox.innerHTML = '';

            // Separate adviser and explorer messages into different arrays
            const adviserMessages = formattedMessages.adviser;
            const explorerMessages = formattedMessages.explorer;

            // Count messages
            adviserMessageCount = adviserMessages.length;
            explorerMessageCount = explorerMessages.length;

            // Determine which last message to display based on userCategory
            let lastMessageText = '';
            if (userCategory === 'Explorer') {
                if (adviserMessageCount > 0) {
                    const lastAdviserMessage = adviserMessages[adviserMessageCount - 1];
                    const timestampEndIndex = lastAdviserMessage.text.indexOf(']') + 1;
                    lastMessageText = lastAdviserMessage.text.substring(timestampEndIndex).trim();
                } else {
                    lastMessageText = 'No adviser messages found';
                }
            } else if (userCategory === 'Adviser') {
                if (explorerMessageCount > 0) {
                    const lastExplorerMessage = explorerMessages[explorerMessageCount - 1];
                    const timestampEndIndex = lastExplorerMessage.text.indexOf(']') + 1;
                    lastMessageText = lastExplorerMessage.text.substring(timestampEndIndex).trim();
                } else {
                    lastMessageText = 'No explorer messages found';
                }
            }

            // Display the last message text
            lastMessageElement.textContent = lastMessageText;

            // Count unread messages
            const unreadAdviserMessages = adviserMessages.filter(message => !message.read);
            const unreadExplorerMessages = explorerMessages.filter(message => !message.read);
            const unreadMessagesCount = (userCategory === 'Explorer') ? unreadAdviserMessages.length : unreadExplorerMessages.length;
            messagepending.textContent = unreadMessagesCount.toString();

            // Combine all messages for sorting and display
            const allMessages = [...adviserMessages, ...explorerMessages];

            // Sort all messages by timestamp
            allMessages.sort(function (a, b) {
                return new Date(a.text.slice(1, 20)) - new Date(b.text.slice(1, 20));
            });

            // Process and append the sorted messages to the chat box
            allMessages.forEach(function (message) {
                const chatMessage = formatChatMessage(message);
                chatBox.appendChild(chatMessage);
            });

            // Scroll to the bottom if new messages are added or user hasn't scrolled manually
            if (!userScrolled || allMessages.length > previousMessageCount) {
                scrollToBottom(chatBox);
            }

            // Update the previous message count
            previousMessageCount = allMessages.length;
        }
    };

    const urlParams = new URLSearchParams(window.location.search);
    const unique_id1 = urlParams.get('unique_id1');
    const unique_id2 = urlParams.get('unique_id2');
    xhr.open('GET', `chat.php?unique_id1=${unique_id1}&unique_id2=${unique_id2}`, true);
    xhr.send();
}



function submitMessage(event) {
    event.preventDefault(); // Prevents the default form submission behavior
  
    const fileContainer = document.querySelector('.file-container');
    fileContainer.innerHTML = ''; // Clear the file container after submission
    const messageInput = document.getElementById('message-input');
    const fileInput = document.getElementById('file-input');
    const formData = new FormData();

    formData.append('message', messageInput.value);
    formData.append('file', fileInput.files[0]);

    const xhr = new XMLHttpRequest();

    // Progress bar elements
    const progressBar = document.createElement('div');
    progressBar.classList.add('progress-bar');

    const progressContainer = document.createElement('div');
    progressContainer.classList.add('progress-container');
    progressContainer.appendChild(progressBar);

    const chatBox = document.querySelector('.chat-box');
    chatBox.appendChild(progressContainer);

    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            const percentComplete = (event.loaded / event.total) * 100;
            progressBar.style.width = percentComplete + '%';
        }
    };

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            progressContainer.remove(); // Remove progress bar after upload completes

            if (xhr.status === 200) {
                messageInput.value = '';
                fileInput.value = '';

                // Update the chat box immediately after sending the message
                loadChatMessages();
            } else {
                // Handle error cases
                console.error('Error uploading file:', xhr.status, xhr.statusText);
            }
        }
    };

    const urlParams = new URLSearchParams(window.location.search);
    const unique_id1 = urlParams.get('unique_id1');
    const unique_id2 = urlParams.get('unique_id2');
    xhr.open('POST', `chat.php?unique_id1=${unique_id1}&unique_id2=${unique_id2}`, true);
    xhr.send(formData);
}

document.addEventListener('DOMContentLoaded', function () {
    const chatForm = document.getElementById('chat-form');
    chatForm.addEventListener('submit', submitMessage);

    // Load chat messages initially
    loadChatMessages();

    // Periodically refresh the chat messages every 1 second
    setInterval(loadChatMessages, 1000);

    // Track if the user has manually scrolled
    const chatBox = document.querySelector('.chat-box');
    chatBox.addEventListener('scroll', function () {
        userScrolled = chatBox.scrollTop + chatBox.clientHeight < chatBox.scrollHeight;
    });
});

function scrollToBottom(chatBox) {
    chatBox.scrollTop = chatBox.scrollHeight;
}

    </script>
<script>
function displayFile(file) {
  const fileItem = document.createElement('div');
  fileItem.classList.add('file-item');

  const fileInfo = document.createElement('span');
  fileInfo.classList.add('file-info');
  fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;

  const thumbnail = document.createElement('img');
  thumbnail.classList.add('thumbnail');
  
  // Check if the file is an image
  if (file.type.startsWith('image')) {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(event) {
      thumbnail.src = event.target.result;
    }
    fileItem.appendChild(thumbnail);
  }

  const removeBtn = document.createElement('span');
  removeBtn.classList.add('remove-btn');
  removeBtn.textContent = 'x';
  removeBtn.addEventListener('click', () => {
    fileItem.remove(); // Remove the file container when remove button is clicked
    // Hide file container if there are no files left
    if (fileContainer.childElementCount === 0) {
      fileContainer.style.display = 'none';
    }
  });

  fileItem.appendChild(fileInfo);
  fileItem.appendChild(removeBtn);
  fileContainer.appendChild(fileItem);
}

</script>

     <script>
        let typingTimeout = null;
        const userCategory = '<?php echo $userCategory; ?>';
        const explorerName = '<?php echo $explorer_name; ?>';
        const adviserName = '<?php echo $adviser_name; ?>';

        // Function to send AJAX request when user starts typing
        function notifyTyping(typing) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'notify_typing.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send(`typing=${typing}`);
        }

        // Function to handle input event
        function handleInput() {
            clearTimeout(typingTimeout); // Clear previous timeout if exists
            notifyTyping(true); // Notify typing status
            typingTimeout = setTimeout(function() {
                notifyTyping(false); // Notify typing stopped after timeout
            }, 1000); // Timeout after 1 second of no input
        }

        // Event listener for input field
        document.getElementById('message-input').addEventListener('input', handleInput);

        // Function to handle AJAX response indicating typing status
        function handleTypingNotification(isTyping) {
            const typingIndicator = document.querySelector('.istyping');
            if (isTyping) {
                typingIndicator.style.display = 'block';
                if (userCategory === 'Adviser') {
                    typingIndicator.textContent = `${explorerName} is typing...`;
                } else {
                    typingIndicator.textContent = `${adviserName} is typing...`;
                }
            } else {
                typingIndicator.style.display = 'none';
            }
        }

        // Function to periodically check typing status
        function checkTypingStatus() {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    handleTypingNotification(response.typing);
                }
            };
            xhr.open('GET', 'check_typing.php', true);
            xhr.send();
        }

        // Periodically check typing status (adjust interval as needed)
        setInterval(checkTypingStatus, 1000); // Check every second
    </script>
</body>
</html>
