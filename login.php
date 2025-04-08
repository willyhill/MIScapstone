<?php
session_start(); // Start the session to store login data if the user is authenticated

// Include database connection file
$db = new SQLite3(__DIR__ . '/CapstoneDBbrowserFiles/users.db');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data (email and password)
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Escape the input to prevent SQL Injection
    $email = $db->escapeString($email);
    $password = $db->escapeString($password);

    // Check if email and password are not empty
    if (!empty($email) && !empty($password)) {
        // Query to check if the user exists in the database
        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = $db->query($query);

        if ($user = $result->fetchArray(SQLITE3_ASSOC)) {
            // User exists, verify the password
            if (password_verify($password, $user['password'])) {
                // Password matches, store user info in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['username'];

                // Redirect to homepage (or any other protected page)
                header("Location: homepage_wireframe.html");
                exit;
            } else {
                // Password is incorrect
                $error_message = "Invalid email or password.";
            }
        } else {
            // User not found
            $error_message = "No account found with that email.";
        }
    } else {
        // Email or password is empty
        $error_message = "Please enter both email and password.";
    }
}
?>
