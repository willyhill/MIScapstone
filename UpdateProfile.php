<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccountPage.html");
    exit();
}

// Connect to the database
$db = new SQLite3(__DIR__ . '/CapstoneDBbrowserFiles/capstoneDBdatabase.db');

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Debugging: Check if the user ID is set
error_log("User ID: $user_id");

// Handle username update
if (isset($_POST['update_username'])) {
    $new_username = trim($_POST['new_username']);

    // Debugging: Check if the new username is received
    error_log("New username: $new_username");

    if (!empty($new_username)) {
        // Prepare the SQL statement
        $stmt = $db->prepare('UPDATE user_profiles SET username = :username WHERE user_id = :user_id');
        $stmt->bindValue(':username', $new_username, SQLITE3_TEXT);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);

        // Execute the query and check for errors
        if ($stmt->execute()) {
            // Debugging: Log success
            error_log("Username updated successfully for user ID: $user_id");

            // Update the session username
            $_SESSION['username'] = $new_username;

            // Redirect back to the profile page with a success message
            header("Location: Profile.php?success=username_updated");
            exit();
        } else {
            // Debugging: Log an error if the query fails
            error_log("Failed to update username for user ID: $user_id");
            echo '<script>
                alert("Failed to update username. Please try again.");
                window.location.href = "Profile.php";
            </script>';
            exit();
        }
    } else {
        // Debugging: Log an error if the username is empty
        error_log("Empty username provided for user ID: $user_id");
        echo '<script>
            alert("Username cannot be empty.");
            window.location.href = "Profile.php";
        </script>';
        exit();
    }
}

// Handle password update
if (isset($_POST['update_password'])) {
    $new_password = trim($_POST['new_password']);

    if (!empty($new_password)) {
        // Hash the password before storing it
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $db->prepare('UPDATE user_profiles SET password = :password WHERE user_id = :user_id');
        $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->execute();

        // Redirect back to the profile page with a success message
        header("Location: Profile.php?success=password_updated");
        exit();
    }
}

// Close the database connection
$db->close();

// Redirect back to the profile page if no action was taken
header("Location: Profile.php");
exit();
?>