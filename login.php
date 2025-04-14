<?php
// Start session to store user session data
session_start();

// Debugging: Print the contents of the $_POST array
echo '<pre>';
print_r($_POST);
echo '</pre>';

// Connect to SQLite database (or create it if it doesn't exist)
$db = new SQLite3(__DIR__ . '/CapstoneDBbrowserFiles/capstoneDBdatabase.db');

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
        echo '<script>
            alert("All fields are required.");
            window.location.href = "LoginAccountPage.html";
        </script>';
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
        if (password_verify($password, $user['password_hash'])) { 
            // Password is correct, start a session
            $_SESSION['user_id'] = $user['user_id']; // Store user ID in session
            $_SESSION['email'] = $user['email']; // Optionally store email

            // Redirect to the budget page
            header('Location: budget_page.html'); // Replace with actual budget page URL
            exit; // Ensure no further code is executed
        } else {
            echo '<script>
                alert("Invalid password. Please try again.");
                window.location.href = "LoginAccountPage.html";
            </script>';
            exit;
        }
    } else {
        echo '<script>
            alert("User not found. Please check your email or create an account.");
            window.location.href = "LoginAccountPage.html";
        </script>';
        exit;
    }
}
?>

