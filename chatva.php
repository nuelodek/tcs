<!Doctype html>
<html> 
<head>
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.v3/css/all.min.css">
<link rel="shortcut icon" type="image/x-icon" href="vaicon.png">
<link rel="stylesheet" type="text/css" href="chat.css">
<title>Chat</title>


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