<?php
# admin_view_issue.php - Page for admin to view details of a specific issue in CodeGuard Pro

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
$issue_id = '';
$error_message = '';

# Check if the issue id is provided in the query parameters
if (isset($_GET['id'])) {
    $issue_id = sanitize_input($_GET['id']);

    # Fetch the specific issue details
    $issue_details = get_issue_details($issue_id, $user_id);

    # Check if the issue exists
    if (!$issue_details) {
        $error_message = 'Issue not found.';
    }
} else {
    # Redirect to the admin dashboard if no issue id is provided
    header('Location: admin_dashboard.php');
    exit();
}

?>

<!-- HTML content for admin view issue page -->
<body>
    <h2>Admin View Issue</h2>

    <?php if ($issue_details): ?>
        <h3><?php echo $issue_details['title']; ?></h3>
        <p>Description: <?php echo $issue_details['description']; ?></p>
        <p>Status: <?php echo $issue_details['status']; ?></p>
        <p>Priority: <?php echo $issue_details['priority']; ?></p>
        <p>Assigned to: <?php echo $issue_details['assigned_user']; ?></p>

        <!-- Admin-specific actions -->
        <form method="post" action="">
            <button type="submit" name="mark_resolved">Mark Resolved</button>
        </form>

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
