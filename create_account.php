<?php
// Debugging: Print the contents of the $_POST array
echo '<pre>';
print_r($_POST);
echo '</pre>';

// Debugging: Print the full path to the database file
echo realpath(__DIR__ . '/CapstoneDBbrowserFiles/users.db');

// Connect to SQLite database (or create it if it doesn't exist)
$db = new SQLite3(__DIR__ . '/CapstoneDBbrowserFiles/users.db');

// Check if the database connection was successful
if ($db) {
    echo "Connected to the database successfully!<br>";
} else {
    echo "Failed to connect to the database.<br>";
}

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
    $name = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    // Debugging: Check if form data is being received
    echo "Email: $email<br>";
    echo "Name: $name<br>";
    echo "Password: $password<br>";

    // Validate input
    if (empty($email) || empty($name) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into the database
    $stmt = $db->prepare('INSERT INTO users (email, name, password_hash) VALUES (:email, :name, :password_hash)');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':password_hash', $hashedPassword, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Account created successfully!";
        echo '<script>
        alert("Account created successfully!");
        window.location.href = "LoginAccountPage.html";
        </script>';
        exit;
    } else {
        echo "Error saving user to database.";
    }
}
?>