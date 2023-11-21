<?php
# admin_view_user.php - Page for admin to view details of a specific user in CodeGuard Pro

# Include header
include('../includes/header.php');

# Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    # Redirect to the login page if not logged in or not an admin
    header('Location: login.php');
    exit();
}

# Include common functions
include('../includes/functions.php');

# Initialize variables
$user_id = $_SESSION['user_id'];
$viewed_user_id = '';
$error_message = '';

# Check if the user id is provided in the query parameters
if (isset($_GET['id'])) {
    $viewed_user_id = sanitize_input($_GET['id']);

    # Fetch the specific user details
    $viewed_user_details = get_user_details($viewed_user_id);

    # Check if the user exists
    if (!$viewed_user_details) {
        $error_message = 'User not found.';
    }
} else {
    # Redirect to the admin dashboard if no user id is provided
    header('Location: admin_dashboard.php');
    exit();
}

?>

<!-- HTML content for admin view user page -->
<body>
    <h2>Admin View User</h2>

    <?php if ($viewed_user_details): ?>
        <h3><?php echo $viewed_user_details['username']; ?></h3>
        <p>Role: <?php echo $viewed_user_details['role']; ?></p>

        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <a href="admin_dashboard.php">Back to Admin Dashboard</a>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
