<?php
# admin_dashboard.php - Admin Dashboard in CodeGuard Pro

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

# Get user information from the session
$user_id = $_SESSION['user_id'];

# Get all issues
$all_issues = get_all_issues();

# Get all users
$all_users = get_all_users();

?>

<!-- HTML content for admin dashboard page -->
<body>
    <h2>Admin Dashboard</h2>

    <!-- Admin-specific content -->
    <p>Welcome, Admin!</p>

    <h3>All Issues</h3>
    <ul>
        <?php foreach ($all_issues as $issue): ?>
            <li>
                <?php echo $issue['title']; ?> -
                Status: <?php echo $issue['status']; ?>,
                Priority: <?php echo $issue['priority']; ?>,
                Assigned to: <?php echo $issue['assigned_user']; ?>,
                <a href="admin_view_issue.php?id=<?php echo $issue['id']; ?>">View Details</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>All Users</h3>
    <ul>
        <?php foreach ($all_users as $user): ?>
            <li>
                <?php echo $user['username']; ?> -
                Role: <?php echo $user['role']; ?>,
                <a href="admin_view_user.php?id=<?php echo $user['id']; ?>">View Details</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="logout.php">Logout</a>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
