<?php
# login.php - Page for user login in CodeGuard Pro

# Include header
include('../includes/header.php');

# Include common functions
include('../includes/functions.php');

# Initialize variables
$username = $password = '';
$error_message = '';

# Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    # Sanitize input data
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    # TODO: Implement logic to authenticate the user
    $auth_result = authenticate_user($username, $password);

    if ($auth_result) {
        # Start a new session and store user information
        session_start();
        $_SESSION['user_id'] = $auth_result['user_id'];
        $_SESSION['role'] = $auth_result['role'];

        # Redirect to the dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        $error_message = 'Invalid username or password.';
    }
}

?>

<!-- HTML content for user login page -->
<body>
    <h2>User Login</h2>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo $username; ?>" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>

    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <h4>Don't have an account?<a href="register.php"> Register here</a></h4>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
