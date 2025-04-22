<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Get the new currency from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);
$new_currency = $data['currency'] ?? null;

if ($new_currency) {
    // Connect to the database
    $db = new SQLite3(__DIR__ . '/CapstoneDBbrowserFiles/capstoneDBdatabase.db');

    // Update the user's preferred currency
    $stmt = $db->prepare('UPDATE user_profiles SET preferred_currency = :currency WHERE user_id = :user_id');
    $stmt->bindValue(':currency', $new_currency, SQLITE3_TEXT);
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->execute();

    // Debugging: Log the currency update
    error_log("Currency updated to: $new_currency for user ID: $user_id");

    // Close the database connection
    $db->close();

    echo json_encode(['success' => true]);
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid currency value']);
}
?>

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
        }
    });

    app.mount('#app');
</script>