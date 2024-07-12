     <?php
            // Check if the user is an explorer
            $userCategory = $_SESSION['category'];
            if ($userCategory === 'Explorer') {
                echo 'showPopup("Chat Closing Soon", "The chat will close in 5 secs");';
            }
            ?>