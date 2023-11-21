<?php
# register.php - Page for user registration in CodeGuard Pro

# Include header
include('../includes/header.php');

# Include common functions
include('../includes/functions.php');

# Initialize variables
$username = $password = $confirm_password = '';
$error_message = '';

# Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    # Sanitize input data
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $confirm_password = sanitize_input($_POST['confirm_password']);

    # Validate input
    if (validate_registration($username, $password, $confirm_password)) {
        # Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        # TODO: Implement logic to create a new user and assign a role
        create_user($username, $hashed_password, 'user');

        header('Location: login.php');
        exit();
    } else {
        $error_message = 'Invalid registration details.';
    }
}

?>

<!-- HTML content for user registration page -->
<body>
    <h2>User Registration</h2>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo $username; ?>" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <button type="submit">Register</button>
    </form>

    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <a href="login.php">Already have an account? Login here</a>
</body>

<?php
# Include footer
include('../includes/footer.php');
?>
