<!DOCTYPE html>
<html> 
<head>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.v3/css/all.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="vaicon.png">
  <title>Chat</title>

    <style>
        .chat-message .time {
        font-size: 0.8em; /* You can adjust the font size as per your preference */
        color: #666; /* You can change the color as per your preference */
        margin-top: 5px; /* Add some space between the message text and the time */
    }
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .chat-container {
            min-width: 100px;
            margin: 0 auto;
            padding: 20px;
            background-color:#ffe227;
            border-radius: 5px;
        }

#logoutButton { 
    
        background-color:#ffe227;
        padding: 10px;
        margin: 0 auto;
        border-radius:10px;
        border:none;
}

#logoutButton:hover {
            background-color:black;
            color: white;
            cursor: pointer;
}
        .chat-box {
background-color: #ff342f;          
background-repeat: repeat; /* Repeat both horizontally and vertically */
            height: 500px;
            overflow-y: auto;
            backdrop-filter: blur(5px); /* Adjust the blur radius as needed */
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid #ccc;
            padding: 10px;
            scrollbar-face-color: #888;
            scrollbar-track-color: #f1f1f1;
            scrollbar-arrow-color: #555;
            scrollbar-shadow-color: #888;
            scrollbar-highlight-color: #f1f1f1;
            scrollbar-3dlight-color: #f1f1f1;
            scrollbar-darkshadow-color: #888;
        }

        /* Customize the scroll bar */
        .chat-box::-webkit-scrollbar {
            width: 8px;
            border-radius: 20px;
        }

        .chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background: #888;
        }

        .chat-box::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .chat-box::-ms-scrollbar-thumb {
            background-color: #888;
        }

        .chat-box::-ms-scrollbar-track {
            background-color: #f1f1f1;
        }

        .chat-box::-ms-scrollbar-arrow {
            background-color: #555;
        }

        .chat-box::-ms-scrollbar-shadow {
            background-color: #888;
        }

        .chat-box::-ms-scrollbar-highlight {
            background-color: #f1f1f1;
        }

        .chat-box::-ms-scrollbar-3dlight {
            background-color: #f1f1f1;
        }

        .chat-box::-ms-scrollbar-darkshadow {
            background-color: #888;
        }

 /* CSS style for .chat-message */
.chat-message {
  border-radius: 5px;
  padding: 8px; /* Reduce padding to make the container smaller */
  max-width: 80%;
  margin-bottom: 10px;
  clear: both;
}

/* CSS style for img inside .chat-message */
.chat-message img {
  max-width: 100%;
  height: auto;
  border-radius: 3px; /* Slightly smaller border radius */
  display: block;
  margin: 5px 0; /* Reduce margin to make the images closer to the text */
}



        .chat-message.explorer {
            word-wrap: break-word;
            background-color: #DCF8C6;
            float: right;
        }

        .chat-message.adviser {
            word-wrap: break-word;
            background-color: #F0F0F0;
            float: left;
        }

        .chat-message .text {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .chat-message .file-info {
            font-size: 0.8em;
            color: #666;
        }

        #message-input,
        #file-input,
        button[type="submit"] {
            margin-top: 10px;
        }

#message-input {
 flex-grow: 1;
  margin-right: 10px;
  padding: 10px;
  width: 800px;
  border: none;
  border-radius: 5px;
  outline: none;
  
}



        button[type="submit"] {
            padding: 8px 16px;
            background-color: purple;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .file-input-button {
            display: inline-block;
            padding: 7px 16px;
            background-color: #128C7E;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .file-input-button input[type="file"] {
            display: none;
        }
    </style>
    
</head>
<body>
   
    <div class="chat-container">
        <div class="chat-box"></div>
        
       <form id="chat-form" action="chat.php?unique_id1=<?php echo $_GET['unique_id1']; ?>&unique_id2=<?php echo $_GET['unique_id2']; ?>" method="POST">

            <input type="text" id="message-input" placeholder="Type your message">
<button type="submit">Send</button>
        <!--   <label for="file-input" class="file-input-button">
  <i class="fas fa-file"></i> Upload File
</label>-->
<input type="file" id="file-input">
            
        </form>
    </div>
    <br>
<button id="logoutButton">Logout</button>
<div id="countdown"></div>

<script>
document.getElementById("logoutButton").onclick = function() {
    // Redirect to logout.php
    window.location.href = "logout.php";
};
</script>

    <script>
function formatChatMessage(message) {
    const chatMessage = document.createElement('div');
    chatMessage.className = `chat-message ${message.user}`;

    const textElement = document.createElement('div');
    textElement.className = 'text';

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
    timeElement.className = 'time';

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


   function loadChatMessages() {
        const chatBox = document.querySelector('.chat-box');

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const formattedMessages = JSON.parse(xhr.responseText);

                chatBox.innerHTML = '';

                // Combine both adviser and explorer messages
                const allMessages = formattedMessages.adviser.concat(formattedMessages.explorer);

                // Sort all messages by timestamp
                allMessages.sort(function (a, b) {
                    return new Date(a.text.slice(1, 20)) - new Date(b.text.slice(1, 20));
                });

                // Process and append the sorted messages to the chat box
                allMessages.forEach(function (message) {
                    const chatMessage = formatChatMessage(message);
                    chatBox.appendChild(chatMessage);
                });

            }
        };

        const urlParams = new URLSearchParams(window.location.search);
        const unique_id1 = urlParams.get('unique_id1');
        const unique_id2 = urlParams.get('unique_id2');
        xhr.open('GET', `chat.php?unique_id1=${unique_id1}&unique_id2=${unique_id2}`, true);
        xhr.send();
    }

        function submitMessage(event) {
            event.preventDefault();

            const messageInput = document.getElementById('message-input');
            const fileInput = document.getElementById('file-input');
            const formData = new FormData();

            formData.append('message', messageInput.value);
            formData.append('file', fileInput.files[0]);

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    messageInput.value = '';
                    fileInput.value = '';

                    // Update the chat box immediately after sending the message
                    loadChatMessages();
                    
                    
    // Periodically refresh the chat messages every 1 second
    setInterval(loadChatMessages, 1000);
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
             // Scroll to the bottom on page load
 
    // Periodically refresh the chat messages every 1 second
        });
        
    
    </script>
    
<!--    <script>
         function showPopup(title, message) {
            // Create the popup element
            var popup = document.createElement("div");
            popup.className = "popup";
            popup.innerHTML = `
                <h2>${title}</h2>
                <p>${message}</p>
                <button onclick="buyExtraTime()">Buy Extra Time</button>
                <button onclick="closePopup()">Close</button>
            `;

            // Append the popup to the body
            document.body.appendChild(popup);
        }

        // Function to handle buying extra time
        function buyExtraTime() {
            // Add your code here to handle buying extra time
            alert("Extra time purchased!");
            closePopup();
        }

        // Function to close the popup
        function closePopup() {
            // Remove the popup element from the DOM
            var popup = document.querySelector(".popup");
            if (popup) {
                popup.remove();
            }
            // Redirect to the exit page after 5 seconds
            setTimeout(function() {
                window.location.href = "https://thevirtualadviser.com.ng/Rate Adviser";
            }, 5000);
        }
   
        // Wait for 10 seconds before displaying the popup for the explorer
        setTimeout(function() {
            // Display the popup for the explorer
       
        }, 20000);
    </script> -->
    
 <!--// Create a delete button element
    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Delete';
    deleteButton.className = 'delete-button';

    // Attach a click event handler to the delete button
    deleteButton.addEventListener('click', function () {
        // Remove the chat message from the DOM when the delete button is clicked
        chatMessage.remove();
    }); 

    // Append the delete button to the chat message
    chatMessage.appendChild(deleteButton); -->
 
</body>
</html>