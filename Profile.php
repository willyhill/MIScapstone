<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"="width=device-width, initial-scale=1.0">
    <title>JCW Financials - Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
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
    <div class="container" id="app">
        <header>
            <nav class="nav-bar">
                <a href="index.php" class="logo">JCW Financials</a>
                <ul class="nav-links">
                    <li><button class="nav-button"><a href="index.php">Home</a></button></li>
                    <li><button class="nav-button"><a href="budget.php">Budget Page</a></button></li>
                </ul>
                <div class="profile-menu">
                    <img src="profile.jpg" alt="Profile" class="profile-pic" id="profile-button" />
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="Profile.php" style="color: #1abc9c; padding: 12px 16px; text-decoration: none; display: block;">Account</a>
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
                    <i class="fas fa-camera camera-icon"></i> <!-- Camera icon -->
                    <div class="hover-text">Change Photo</div>
                </div>
            </div>
            <!-- Dynamically display the username -->
            <h2>Hello, <?php echo htmlspecialchars($username); ?>!</h2>
            <div class="account-details">
                <label for="preferred_currency">Preferred Currency:</label>
                <span id="current-currency">{{ currency }}</span>
                
                <!-- Form for Currency Toggle -->
                <form method="post" action="">
                    <label class="label">
                        <div class="toggle">
                            <input class="toggle-state" type="checkbox" @change="toggleCurrency" :checked="currency === 'CAD'">
                            <div class="indicator"></div>
                        </div>
                        <span class="label-text">Switch Currency</span>
                    </label>
                </form>

                <!-- Change Username Section -->
                <label for="change_username">Change Username:</label>
                <form method="post" action="UpdateProfile.php">
                    <input type="text" id="change_username" name="new_username" placeholder="Enter new username" required>
                    <button type="submit" name="update_username">Update Username</button>
                </form>

                <!-- Change Password Section -->
                <label for="change_password">Change Password:</label>
                <form method="post" action="UpdateProfile.php">
                    <input type="password" id="change_password" name="new_password" placeholder="Enter new password" required>
                    <button type="submit" name="update_password">Update Password</button>
                </form>
            </div>
            <div class="advanced-settings">
                <p>Advanced Settings</p>
            </div>
        </div>
    </div>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    currency: '<?php echo $preferred_currency; ?>' // Initialize with PHP value
                };
            },
            methods: {
                toggleCurrency() {
                    this.currency = this.currency === 'USD' ? 'CAD' : 'USD';

                    // Send the updated currency to the server via an AJAX request
                    fetch('UpdateCurrency.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ currency: this.currency })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Currency updated successfully');
                        } else {
                            console.error('Error updating currency:', data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            },
            mounted() {
                console.log('Current currency:', this.currency);
            }
        });

        app.mount('#app');
    </script>
</body>
</html>