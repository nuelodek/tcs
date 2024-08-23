    <?php
    session_start();

    // Header
    include 'header.php';

    
    // Main content
    echo '<main style="max-width: 800px; margin: 0 auto; padding: 20px;">';

    echo '<h1 style="color: #003366; text-align: center;">Welcome to TCS University</h1>';
    echo '<p style="font-size: 18px; line-height: 1.6;">TCS University is a leading institution dedicated to academic excellence and innovation.</p>';


    echo '<h2 style="color: #0066cc; margin-top: 30px;">Our Programs</h2>';
    echo '<ul style="list-style-type: disc; padding-left: 20px;">';
    include 'getlist.php';
  
    echo '</ul>';
    echo '<h2 style="color: #0066cc; margin-top: 30px;">Upcoming Events</h2>';
    echo '<ul style="list-style-type: none; padding-left: 0;">';
    echo '<li style="margin-bottom: 10px;"><strong>Open House</strong> - May 15th</li>';
    echo '<li style="margin-bottom: 10px;"><strong>Summer Session Registration</strong> - June 1st</li>';
    echo '<li style="margin-bottom: 10px;"><strong>Graduation Ceremony</strong> - July 10th</li>';
    echo '</ul>';
    echo '<h2 style="color: #0066cc; margin-top: 30px;">News and Announcements</h2>';
    echo '<p style="font-size: 16px; line-height: 1.5; background-color: #f0f5ff; padding: 15px; border-radius: 5px;">TCS University ranked in the top 50 universities nationwide. Read more about our achievements and recent research breakthroughs.</p>';


    
    // New section for account creation or login
    echo '<div style="text-align: center; margin: 30px 0;">';
    echo '<h2 style="color: #0066cc;">Join The University Community</h2>';
    echo '<p style="font-size: 16px; margin-bottom: 15px;">Create an account or log in to access exclusive resources and information.</p>';
    echo '<a href="tcs_signup.php" style="display: inline-block; margin-right: 10px; padding: 10px 20px; background-color: #003366; color: #fff; text-decoration: none; border-radius: 5px;">Create Account</a>';
    echo '<a href="tcs_login.php" style="display: inline-block; padding: 10px 20px; background-color: #0066cc; color: #fff; text-decoration: none; border-radius: 5px;">Log In</a>';
    echo '</div>';
    echo '</main>';

    // Footer
    ?>
