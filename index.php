<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JCW Financials</title>
    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            background-color: #3fb888; /* Green background for the nav */
            padding: 10px;
            width: 100%;
        }

        /* Styling for navigation buttons */
        .nav-buttons {
            display: flex;
            gap: 20px; /* Space between buttons */
        }

        .nav-button {
            padding: 10px 20px;
            border: 2px solid white;
            background-color: #1abc9c; /* Green background for buttons */
            color: white;
            font-size: 1rem;
            border-radius: 4px; /* Rounded corners */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-align: center;
            display: inline-block;
        }

        .nav-button a {
            color: white; /* White text for the links */
            text-decoration: none; /* No underline */
        }

        .nav-button:hover {
            background-color: #1c9f85; /* Darker green on hover */
            transform: scale(1.05); /* Slight scaling to emphasize it's clickable */
        }

        /* Container for the page content */
        .container {
            width: 100%;
            height: 100vh;
            background: linear-gradient(to right, #3fb888, #95e2a1);
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        h1 {
            font-size: 4.5em;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        p {
            font-style: italic;
            margin-top: 20px;
        }

        footer {
            background-color: #3fb888;
            color: white;
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            font-size: 1em;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Loader Animation */
        .loader-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
        }

        .loader {
            position: relative;
            width: 75px;
            height: 100px;
        }

        .loader__bar {
            position: absolute;
            bottom: 0;
            width: 10px;
            height: 50%;
            background: rgb(0, 128, 128); /* Teal for the bars */
            transform-origin: center bottom;
            box-shadow: 1px 1px 0 rgba(0, 0, 0, 0.2);
        }

        /* Add individual animations for loader bars */
        .loader__bar:nth-child(1) { left: 0px; transform: scale(1, 0.2); animation: barUp1 4s infinite; }
        .loader__bar:nth-child(2) { left: 15px; transform: scale(1, 0.4); animation: barUp2 4s infinite; }
        .loader__bar:nth-child(3) { left: 30px; transform: scale(1, 0.6); animation: barUp3 4s infinite; }
        .loader__bar:nth-child(4) { left: 45px; transform: scale(1, 0.8); animation: barUp4 4s infinite; }
        .loader__bar:nth-child(5) { left: 60px; transform: scale(1, 1); animation: barUp5 4s infinite; }

        .loader__ball {
            position: absolute;
            bottom: 10px;
            left: 0;
            width: 10px;
            height: 10px;
            background: rgb(255, 255, 255); /* White for the ball */
            border-radius: 50%;
            animation: ball624 4s infinite;
        }

        /* Keyframes for loader animations */
        @keyframes ball624 {
            0% { transform: translate(0, 0); }
            50% { transform: translate(60px, 0); }
            100% { transform: translate(0, 0); }
        }

        @keyframes barUp1 {
            0%, 40% { transform: scale(1, 0.2); }
            50%, 90% { transform: scale(1, 1); }
            100% { transform: scale(1, 0.2); }
        }

        @keyframes barUp2 {
            0%, 40% { transform: scale(1, 0.4); }
            50%, 90% { transform: scale(1, 0.8); }
            100% { transform: scale(1, 0.4); }
        }

        @keyframes barUp3 {
            0%, 100% { transform: scale(1, 0.6); }
        }

        @keyframes barUp4 {
            0%, 40% { transform: scale(1, 0.8); }
            50%, 90% { transform: scale(1, 0.4); }
            100% { transform: scale(1, 0.8); }
        }

        @keyframes barUp5 {
            0%, 40% { transform: scale(1, 1); }
            50%, 90% { transform: scale(1, 0.2); }
            100% { transform: scale(1, 1); }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-buttons">
            <?php if ($is_logged_in): ?>
                <button class="nav-button"><a href="budget.php">Budget Page</a></button>
                <button class="nav-button"><a href="logout.php">Logout</a></button>
            <?php else: ?>
                <button class="nav-button"><a href="CreateAccountPage.html">Create Account</a></button>
                <button class="nav-button"><a href="LoginAccountPage.html">Login</a></button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <h1>JCW</h1>
        <h1>Finance</h1>

        <!-- Loader Wrapper to center the loader -->
        <div class="loader-wrapper">
            <div class="loader">
                <div class="loader__bar"></div>
                <div class="loader__bar"></div>
                <div class="loader__bar"></div>
                <div class="loader__bar"></div>
                <div class="loader__bar"></div>
                <div class="loader__ball"></div>
            </div>
        </div>

        <p>We're here to help you with the finances of life</p>
    </div>

    <footer>
        <p>Contact Us: <a href="mailto:help@JCWFinance.com" style="color: white;">help@JCWFinance.com</a></p>
    </footer>

    <div class="profile-menu">
        <img src="profile.jpg" alt="Profile" class="profile-pic" />
        <div class="dropdown-menu">
            <a href="temp.php" style="color: #1abc9c; padding: 12px 16px; text-decoration: none; display: block;">Account</a>
            <a href="settings.html" style="color: #1abc9c; padding: 12px 16px; text-decoration: none; display: block;">Settings</a>
            <a href="logout.php" style="color: #1abc9c; padding: 12px 16px; text-decoration: none; display: block;">Logout</a>
        </div>
    </div>
</body>
</html>

