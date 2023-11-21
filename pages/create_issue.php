<?php
# create_issue.php - Page for creating a new issue in CodeGuard Pro

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
$title = $description = $status = $priority = $assigned_user = '';
$error_message = '';

# Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    # Sanitize input data
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $status = sanitize_input($_POST['status']);
    $priority = sanitize_input($_POST['priority']);
    $assigned_user = sanitize_input($_POST['assigned_user']);

    # TODO: Implement logic to validate and create a new issue
    if (validate_issue($title, $description, $status, $priority, $assigned_user)) {
        create_issue($user_id, $title, $description, $status, $priority, $assigned_user);
        header('Location: dashboard.php');
        exit();
    } else {
        $error_message = 'Invalid issue details.';
    }
}

?>

<!-- HTML content for create issue page -->
<body>
    <h2>Create New Issue</h2>

    <form method="post" action="">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo $title; ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required><?php echo $description; ?></textarea>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select>

        <label for="priority">Priority:</label>
        <select name="priority" id="priority" required>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>

        <label for="assigned_user">Assigned to:</label>
        <input type="text" name="assigned_user" id="assigned_user" value="<?php echo $assigned_user; ?>" required>

        <button type="submit">Create Issue</button>
    </form>

    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <a href="dashboard.php">Back to Dashboard</a>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
