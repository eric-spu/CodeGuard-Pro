<?php
# edit_issue_details.php - Page for editing details of a specific issue in CodeGuard Pro

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
        $error_message = 'Issue not found or you do not have permission to edit.';
    }
} else {
    # Redirect to the dashboard if no issue id is provided
    header('Location: dashboard.php');
    exit();
}

# Initialize form variables with current issue details
$title = $issue_details['title'];
$description = $issue_details['description'];
$status = $issue_details['status'];
$priority = $issue_details['priority'];
$assigned_user = $issue_details['assigned_user'];
$error_message = '';

# Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    # Sanitize input data
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $status = sanitize_input($_POST['status']);
    $priority = sanitize_input($_POST['priority']);
    $assigned_user = sanitize_input($_POST['assigned_user']);

    # TODO: Implement logic to validate and update the issue
    if (validate_issue($title, $description, $status, $priority, $assigned_user)) {
        update_issue($issue_id, $title, $description, $status, $priority, $assigned_user, $user_id);
        header('Location: issue_details.php?id=' . $issue_id);
        exit();
    } else {
        $error_message = 'Invalid issue details.';
    }
}

?>

<!-- HTML content for edit issue details page -->
<body>
    <h2>Edit Issue Details</h2>

    <?php if ($issue_details): ?>
        <form method="post" action="">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo $title; ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required><?php echo $description; ?></textarea>

            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="open" <?php echo ($status === 'open') ? 'selected' : ''; ?>>Open</option>
                <option value="closed" <?php echo ($status === 'closed') ? 'selected' : ''; ?>>Closed</option>
            </select>

            <label for="priority">Priority:</label>
            <select name="priority" id="priority" required>
                <option value="low" <?php echo ($priority === 'low') ? 'selected' : ''; ?>>Low</option>
                <option value="medium" <?php echo ($priority === 'medium') ? 'selected' : ''; ?>>Medium</option>
                <option value="high" <?php echo ($priority === 'high') ? 'selected' : ''; ?>>High</option>
            </select>

            <label for="assigned_user">Assigned to:</label>
            <input type="text" name="assigned_user" id="assigned_user" value="<?php echo $assigned_user; ?>" required>

            <button type="submit">Update Issue</button>
        </form>

        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <a href="dashboard.php">Back to Dashboard</a>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
