<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JCW Financials - Profile</title>
    <link rel="stylesheet" href="css\profile.css">
</head>
<body>
    <?php
        // Start the session
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect to the login page if not logged in
            header("Location: LoginAccountPage.html");
            exit();
        }

        // Fetch the username from the session
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

        // Fetch additional user profile details from the database
        $user_id = $_SESSION['user_id'];
        $preferred_currency = 'USD'; // Default value
        $financial_preferences = '';
        $goal_preference = '';

        // SQLite database connection
        $db = new SQLite3(__DIR__ . '/CapstoneDBbrowserFiles/capstoneDBdatabase.db');

        // Query to fetch user profile details
        $stmt = $db->prepare('SELECT preferred_currency, financial_preferences, goal_preference 
                              FROM user_profiles 
                              WHERE user_id = :user_id');
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $result = $stmt->execute();

        // Fetch the result
        $user_profile = $result->fetchArray(SQLITE3_ASSOC);
        if ($user_profile) {
            $preferred_currency = $user_profile['preferred_currency'];
            $financial_preferences = $user_profile['financial_preferences'];
            $goal_preference = $user_profile['goal_preference'];
        }

        // Close the database connection
        $db->close();
    ?>
    <div class="container">
        <header>
            <nav class="nav-bar">
                <!-- JCW Financials Logo/Button -->
                <a href="index.html" class="logo">JCW Financials</a>
                
                <!-- Home Button -->
                <ul class="nav-links">
                    <li><a href="index.html" class="nav-link nav-button">Home</a></li>
                </ul>
                
                <!-- Profile Picture with Dropdown Menu -->
                <div class="profile-menu">
                    <img src="profile.jpg" alt="Profile" class="profile-pic" id="profile-button" />
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="account.html">Account</a>
                        <a href="settings.html">Settings</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </nav>
        </header>
        <div class="profile-section">
            <div class="profile-photo">
                <!-- Placeholder for profile picture -->
                <div class="profile-placeholder">
                    Placeholder
                </div>
                <p>Change Profile Photo</p>
            </div>
            <!-- Dynamically display the username -->
            <h2>Hello, <?php echo htmlspecialchars($username); ?>!</h2>
            <div class="account-details">
                <label for="preferred_currency">Preferred Currency:</label>
                <input type="text" id="preferred_currency" name="preferred_currency" value="<?php echo htmlspecialchars($preferred_currency); ?>" readonly>
                
                <label for="financial_preferences">Financial Preferences:</label>
                <textarea id="financial_preferences" name="financial_preferences" readonly><?php echo htmlspecialchars($financial_preferences); ?></textarea>
                
                <label for="goal_preference">Goal Preference:</label>
                <textarea id="goal_preference" name="goal_preference" readonly><?php echo htmlspecialchars($goal_preference); ?></textarea>
            </div>
            <div class="advanced-settings">
                <p>Advanced Settings</p>
            </div>
        </div>
    </div>
</body>
</html>