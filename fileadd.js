<script>
      function formatChatMessage(message) {
          const chatMessage = document.createElement('div');
          chatMessage.className = `${message.user_type}`;

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
                  const baseUrl = "https://simplr.com.ng/employees/work";
                  const fileUrl = `${baseUrl}/uploads/${fileName}.${fileExtension}`;

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
          const lastMessageElement = document.querySelector('.lastmessage');
          const userCategory = '<?php echo $userCategory; ?>';
          console.log("userCategory:", userCategory);
          const messagepending = document.getElementById('messagepending');
          let recruiterMessageCount = 0;
          let talentMessageCount = 0;

          const xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function () {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  const formattedMessages = JSON.parse(xhr.responseText);

                  chatBox.innerHTML = '';

                  // Separate recruiter and talent messages into different arrays
                  const recruiterMessages = formattedMessages.recruiter;
                  const talentMessages = formattedMessages.talent;

                  // Count messages
                  recruiterMessageCount = recruiterMessages.length;
                  talentMessageCount = talentMessages.length;

                  // Determine which last message to display based on userCategory
                  let lastMessageText = '';
                  if (userCategory === 'talent') {
                      if (recruiterMessageCount > 0) {
                          const lastrecruiterMessage = recruiterMessages[recruiterMessageCount - 1];
                          const timestampEndIndex = lastrecruiterMessage.text.indexOf(']') + 1;
                          lastMessageText = lastrecruiterMessage.text.substring(timestampEndIndex).trim();
                      } else {
                          lastMessageText = 'No recruiter messages found';
                      }
                  } else if (userCategory === 'recruiter') {
                      if (talentMessageCount > 0) {
                          const lasttalentMessage = talentMessages[talentMessageCount - 1];
                          const timestampEndIndex = lasttalentMessage.text.indexOf(']') + 1;
                          lastMessageText = lasttalentMessage.text.substring(timestampEndIndex).trim();
                      } else {
                          lastMessageText = 'No talent messages found';
                      }
                  }

                  // Count unread messages
                  const unreadrecruiterMessages = recruiterMessages.filter(message => !message.read);
                  const unreadtalentMessages = talentMessages.filter(message => !message.read);
                  const unreadMessagesCount = (userCategory === 'talent') ? unreadrecruiterMessages.length : unreadtalentMessages.length;

                  // Combine all messages for sorting and display
                  const allMessages = [...recruiterMessages, ...talentMessages];

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
          xhr.open('GET', `talentchat.php?unique_id1=${unique_id1}&unique_id2=${unique_id2}`, true);
          xhr.send();
      }

      function submitMessage(event) {
          event.preventDefault();

          const fileContainer = document.querySelector('.file-container');
          fileContainer.innerHTML = '';
          const messageInput = document.getElementById('message-input');
          const fileInput = document.getElementById('file-input');
          const formData = new FormData();

          formData.append('message', messageInput.value);
          formData.append('file', fileInput.files[0]);

          const xhr = new XMLHttpRequest();

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
                  progressContainer.remove();

                  if (xhr.status === 200) {
                      messageInput.value = '';
                      fileInput.value = '';

                      loadChatMessages();
                  } else {
                      console.error('Error uploading file:', xhr.status, xhr.statusText);
                  }
              }
          };

          const urlParams = new URLSearchParams(window.location.search);
          const unique_id1 = urlParams.get('unique_id1');
          const unique_id2 = urlParams.get('unique_id2');
          xhr.open('POST', `talentchat.php?unique_id1=${unique_id1}&unique_id2=${unique_id2}`, true);
          xhr.send(formData);
      }

      document.addEventListener('DOMContentLoaded', function () {
          const chatForm = document.getElementById('chat-form');
          chatForm.addEventListener('submit', submitMessage);

          loadChatMessages();

          setInterval(loadChatMessages, 5000);

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
          const fileContainer = document.querySelector('.file-container');
          const fileItem = document.createElement('div');
          fileItem.classList.add('file-item');

          const fileInfo = document.createElement('span');
          fileInfo.classList.add('file-info');
          fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;

          const thumbnail = document.createElement('img');
          thumbnail.classList.add('thumbnail');

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
              fileItem.remove();
              if (fileContainer.childElementCount === 0) {
                  fileContainer.style.display = 'none';
              }
          });

          fileItem.appendChild(fileInfo);
          fileItem.appendChild(removeBtn);
          fileContainer.appendChild(fileItem);     
          fileContainer.style.display = 'block';
      }

      function formatFileSize(bytes) {
          if (bytes === 0) return '0 Bytes';
          const k = 1024;
          const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
          const i = Math.floor(Math.log(bytes) / Math.log(k));
          return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
      }
  </script>
