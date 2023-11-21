<?php
# dashboard.php - User Dashboard in CodeGuard Pro

# Include header
include('../includes/header.php');

# Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    # Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}

# Include common functions
include('../includes/functions.php');

# Get user information from the session
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

?>

<!-- HTML content for user dashboard page -->
<body>
    <h2>User Dashboard</h2>

    <?php if ($role === 'admin'): ?>
        <!-- Admin-specific content -->
        <p>Welcome, Admin!</p>
        <!-- Add additional admin content here -->
    <?php else: ?>
        <!-- User-specific content -->
        <p>Welcome to your dashboard!</p>
        <!-- Add additional user content here -->
    <?php endif; ?>

    <a href="logout.php">Logout</a>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
