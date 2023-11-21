<?php
# delete_issue.php - Page for deleting a specific issue in CodeGuard Pro

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

# Initialize variables
$user_id = $_SESSION['user_id'];
$issue_id = '';

# Check if the issue id is provided in the query parameters
if (isset($_GET['id'])) {
    $issue_id = sanitize_input($_GET['id']);

    # Fetch the specific issue details
    $issue_details = get_issue_details($issue_id, $user_id);

    # Check if the issue exists and belongs to the user
    if (!$issue_details) {
        $error_message = 'Issue not found or you do not have permission to delete.';
    }
} else {
    # Redirect to the dashboard if no issue id is provided
    header('Location: dashboard.php');
    exit();
}

# Check if the delete confirmation is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
 

    delete_issue($issue_id, $user_id);
    header('Location: dashboard.php');
    exit();
}

?>

<!-- HTML content for delete issue page -->
<body>
    <h2>Delete Issue</h2>

    <?php if ($issue_details): ?>
        <p>Are you sure you want to delete the following issue?</p>
        <h3><?php echo $issue_details['title']; ?></h3>
        <p>Description: <?php echo $issue_details['description']; ?></p>
        <p>Status: <?php echo $issue_details['status']; ?></p>
        <p>Priority: <?php echo $issue_details['priority']; ?></p>
        <p>Assigned to: <?php echo $issue_details['assigned_user']; ?></p>

        <form method="post" action="">
            <button type="submit" name="confirm_delete">Confirm Delete</button>
        </form>
    <?php else: ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <a href="dashboard.php">Cancel</a>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
