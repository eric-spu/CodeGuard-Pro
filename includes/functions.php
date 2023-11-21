<?php
# functions.php - Common functions for CodeGuard Pro application

# Include database connection
require_once('../../CodeGuard-Pro/config/config.php');

# function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

# Function to validate user login
function validate_login($username, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_username, $db_password);
    $stmt->fetch();
    $stmt->close();

    if ($db_username && password_verify($password, $db_password)) {
        session_start();
        $_SESSION['user_id'] = $user_id;
        return true;
    } else {
        return false;
    }
}

# Function to create a new user
function create_user($username, $password) {
    global $conn;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $stmt->close();
}

# Function to fetch user-specific issues
function get_user_issues($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, title, description, status, priority, assigned_user FROM issues WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $issues = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $issues;
}

# Function to create a new issue
function create_issue($user_id, $title, $description, $status, $priority, $assigned_user) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO issues (user_id, title, description, status, priority, assigned_user) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $title, $description, $status, $priority, $assigned_user);
    $stmt->execute();
    $stmt->close();
}

# Function to fetch specific issue details
function get_issue_details($issue_id, $user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, title, description, status, priority, assigned_user FROM issues WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $issue_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $issue_details = $result->fetch_assoc();
    $stmt->close();

    return $issue_details;
}

# Function to update issue details
function update_issue($issue_id, $title, $description, $status, $priority, $assigned_user, $user_id) {
    global $conn;

    $stmt = $conn->prepare("UPDATE issues SET title = ?, description = ?, status = ?, priority = ?, assigned_user = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $status, $priority, $assigned_user, $issue_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

# Function to delete an issue
function delete_issue($issue_id, $user_id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM issues WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $issue_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

# Function to search for issues
function search_issues($user_id, $search_term) {
    global $conn;

    $search_term = '%' . $search_term . '%';

    $stmt = $conn->prepare("SELECT id, title, description, status, priority, assigned_user FROM issues WHERE user_id = ? AND (title LIKE ? OR description LIKE ?)");
    $stmt->bind_param("iss", $user_id, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    $issues = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $issues;
}

# Function to fetch paginated user-specific issues
function get_paginated_user_issues($user_id, $issues_per_page, $offset) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, title, description, status, priority, assigned_user FROM issues WHERE user_id = ? LIMIT ?, ?");
    $stmt->bind_param("iii", $user_id, $offset, $issues_per_page);
    $stmt->execute();
    $result = $stmt->get_result();
    $issues = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $issues;
}

# Function to fetch total number of user-specific issues for pagination
function get_total_user_issues($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT COUNT(id) FROM issues WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($total_issues);
    $stmt->fetch();
    $stmt->close();

    return $total_issues;
}

# Function to search for paginated issues
function search_paginated_issues($user_id, $search_term, $issues_per_page, $offset) {
    global $conn;

    $search_term = '%' . $search_term . '%';

    $stmt = $conn->prepare("SELECT id, title, description, status, priority, assigned_user FROM issues WHERE user_id = ? AND (title LIKE ? OR description LIKE ?) LIMIT ?, ?");
    $stmt->bind_param("issii", $user_id, $search_term, $search_term, $offset, $issues_per_page);
    $stmt->execute();
    $result = $stmt->get_result();
    $issues = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $issues;
}

# Function to count total number of searched issues for pagination
function count_searched_issues($user_id, $search_term) {
    global $conn;

    $search_term = '%' . $search_term . '%';

    $stmt = $conn->prepare("SELECT COUNT(id) FROM issues WHERE user_id = ? AND (title LIKE ? OR description LIKE ?)");
    $stmt->bind_param("iss", $user_id, $search_term, $search_term);
    $stmt->execute();
    $stmt->bind_result($total_issues);
    $stmt->fetch();
    $stmt->close();

    return $total_issues;
}


# Function to validate issue details
function validate_issue($title, $description, $status, $priority, $assigned_user) {

    # Placeholder validation - Replace with actual validation rules
    if (empty($title) || empty($description) || empty($status) || empty($priority) || empty($assigned_user)) {
        return false;
    }

    return true;
}


/**
# Function to update issue details
function update_issue($issue_id, $title, $description, $status, $priority, $assigned_user, $user_id) {
    global $conn;

    $stmt = $conn->prepare("UPDATE issues SET title = ?, description = ?, status = ?, priority = ?, assigned_user = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $status, $priority, $assigned_user, $issue_id, $user_id);
    $stmt->execute();
    $stmt->close();
}



# Function to delete an issue
function delete_issue($issue_id, $user_id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM issues WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $issue_id, $user_id);
    $stmt->execute();
    $stmt->close();
}
**/


# Function to validate user registration details
function validate_registration($username, $password, $confirm_password) {
    # TODO: Implement your validation logic here
    # Placeholder validation - Replace with actual validation rules
    if (empty($username) || empty($password) || empty($confirm_password) || $password !== $confirm_password) {
        return false;
    }

    return true;
}

/**
# Function to create a new user and assign a role
function create_user($username, $hashed_password, $role) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);
    $stmt->execute();
    $stmt->close();
} 

**/

# Function to check if a username already exists
function username_exists($username) {
    global $conn;

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result->num_rows > 0;
}

# Function to authenticate a user
function authenticate_user($username, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password, $role);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password, $hashed_password)) {
        return ['user_id' => $user_id, 'role' => $role];
    } else {
        return false;
    }
}


# Function to mark an issue as resolved
function mark_resolved($issue_id) {
    global $conn;

    $stmt = $conn->prepare("UPDATE issues SET status = 'resolved' WHERE id = ?");
    $stmt->bind_param("i", $issue_id);
    $stmt->execute();
    $stmt->close();
}

# Function to get user details
function get_user_details($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_details = $result->fetch_assoc();
    $stmt->close();

    return $user_details;
}



?>
