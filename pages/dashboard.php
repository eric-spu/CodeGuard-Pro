<?php
# dashboard.php - Main dashboard page for CodeGuard Pro

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

# Pagination variables
$issues_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $issues_per_page;

# Fetch user-specific issues with pagination
$issues = get_paginated_user_issues($user_id, $issues_per_page, $offset);

# Total number of issues for pagination
$total_issues = get_total_user_issues($user_id);
$total_pages = ceil($total_issues / $issues_per_page);

# Search functionality
$search_term = '';
if (isset($_GET['search'])) {
    $search_term = sanitize_input($_GET['search']);
    $issues = search_paginated_issues($user_id, $search_term, $issues_per_page, $offset);
    $total_issues = count_searched_issues($user_id, $search_term);
    $total_pages = ceil($total_issues / $issues_per_page);
}

?>

<!-- HTML content for dashboard -->
<body>
    <h2>Welcome to CodeGuard Pro</h2>

    <!-- Search form -->
    <form method="get" action="">
        <label for="search">Search Issues:</label>
        <input type="text" name="search" id="search" value="<?php echo $search_term; ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Display user-specific issues with pagination -->
    <?php if (!empty($issues)): ?>
        <ul>
            <?php foreach ($issues as $issue): ?>
                <li>
                    <h3><?php echo $issue['title']; ?></h3>
                    <p><?php echo $issue['description']; ?></p>
                    <p>Status: <?php echo $issue['status']; ?></p>
                    <p>Priority: <?php echo $issue['priority']; ?></p>
                    <p>Assigned to: <?php echo $issue['assigned_user']; ?></p>
                    <a href="edit_issue_details.php?id=<?php echo $issue['id']; ?>">Edit Issue</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i . '&search=' . $search_term; ?>" <?php echo ($i === $current_page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p>No matching issues found.</p>
    <?php endif; ?>

    <a href="create_issue.php">Create New Issue</a>
    <a href="logout.php">Logout</a>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
