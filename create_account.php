<?php
// Debugging: Print the contents of the $_POST array
echo '<pre>';
print_r($_POST);
echo '</pre>';

// Connect to SQLite database (or create it if it doesn't exist)
$db = new SQLite3('users.db');

// Create the `users` table if it doesn't exist
$db->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL,
    username TEXT NOT NULL,
    password TEXT NOT NULL
)');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = $_POST['email'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    // Debugging: Check if form data is being received
    echo "Email: $email<br>";
    echo "Username: $username<br>";
    echo "Password: $password<br>";

    // Validate input
    if (empty($email) || empty($username) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into the database
    $stmt = $db->prepare('INSERT INTO users (email, username, password) VALUES (:email, :username, :password)');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Account created successfully!";
    } else {
        echo "Error saving user to database.";
    }
}
?>