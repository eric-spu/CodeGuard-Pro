<?php
# index.php - Main entry point for CodeGuard Pro

# Include configuration file

# Include common functions
include('includes/functions.php');

# Start a PHP session for user authentication
session_start();

# Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    # Redirect to the dashboard if logged in
    header('Location: pages/dashboard.php');
    exit();
}

# Include header
include('includes/header.php');
?>

<!-- HTML content for the landing page -->
<body>
    <h1>Welcome to CodeGuard Pro</h1>
    <p>Secure and efficient collaboration for open-source development.</p>
    
    <a href="pages/login.php">Login</a>
    <a href="pages/register.php">Register</a>
</body>

<?php
# Include footer
include('includes/footer.php');
?>
