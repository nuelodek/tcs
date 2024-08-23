    <?php
    // index.php for TCS University

    // Start the session
    session_start();

    // Include necessary files
    require_once 'config.php';
    require_once 'functions.php';

    // Check if the user is logged in
    $loggedIn = isset($_SESSION['user_id']);

    // Header
    include 'header.php';

    // Navigation
    include 'navigation.php';

    // Main content
    echo '<main>';

    echo '<h1>Welcome to TCS University</h1>';
    echo '<p>TCS University is a leading institution dedicated to academic excellence and innovation.</p>';
    echo '<h2>Our Programs</h2>';
    echo '<ul>';
    echo '<li>Computer Science</li>';
    echo '<li>Engineering</li>';
    echo '<li>Business Administration</li>';
    echo '<li>Liberal Arts</li>';
    echo '</ul>';
    echo '<h2>Upcoming Events</h2>';
    echo '<ul>';
    echo '<li>Open House - May 15th</li>';
    echo '<li>Summer Session Registration - June 1st</li>';
    echo '<li>Graduation Ceremony - July 10th</li>';
    echo '</ul>';
    echo '<h2>News and Announcements</h2>';
    echo '<p>TCS University ranked in the top 50 universities nationwide. Read more about our achievements and recent research breakthroughs.</p>';

    echo '</main>';

    // Footer
    include 'footer.php';
    ?>
