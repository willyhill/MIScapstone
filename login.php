<?php
// Start session to store user session data
session_start();

// Debugging: Print the contents of the $_POST array
echo '<pre>';
print_r($_POST);
echo '</pre>';

// Connect to SQLite database (or create it if it doesn't exist)
$db = new SQLite3(__DIR__ . '/CapstoneDBbrowserFiles/users.db');

// Check if the database connection was successful
if ($db) {
    echo "Connected to the database successfully!<br>";
} else {
    echo "Failed to connect to the database.<br>";
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Validate input
    if (empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Query the database for the user by email
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    // Check if user exists
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['email'] = $user['email']; // Optionally store email

            // Redirect to the budget page
            header('Location: budget_page.php'); // Replace with actual budget page URL
            exit; // Ensure no further code is executed
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>

