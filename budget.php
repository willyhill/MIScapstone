<?php
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginAccountPage.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect to DB
$db = new SQLite3(__DIR__ . '/CapstoneDBbrowserFiles/capstoneDBdatabase.db');

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_income'])) {
        $amount = $_POST['income_amount'];
        $category = $_POST['income_category'];
        $stmt = $db->prepare("INSERT INTO Income (user_id, category, amount, date, created_at, updated_at) 
                              VALUES (:user_id, :category, :amount, DATE('now'), CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':amount', $amount);
        $stmt->execute();
    } elseif (isset($_POST['submit_expense'])) {
        $amount = $_POST['expense_amount'];
        $category = $_POST['expense_category'];
        $stmt = $db->prepare("INSERT INTO Expenses (user_id, category, amount, date, created_at, updated_at) 
                              VALUES (:user_id, :category, :amount, DATE('now'), CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':amount', $amount);
        $stmt->execute();
    } elseif (isset($_POST['submit_savings'])) {
        $goal = $_POST['savings_goal'];
        $stmt = $db->prepare("INSERT INTO Savings (user_id, goal_name, target_amount, current_amount, due_date, created_at, updated_at) 
                              VALUES (:user_id, 'General Savings', :goal, 0, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':goal', $goal);
        $stmt->execute();
    }
}

// Fetch Transactions
$transactions = [];

$incomes = $db->query("SELECT amount, category FROM Income WHERE user_id = $user_id");
while ($row = $incomes->fetchArray(SQLITE3_ASSOC)) {
    $transactions[] = ['type' => 'income', 'amount' => $row['amount'], 'category' => $row['category']];
}

$expenses = $db->query("SELECT amount, category FROM Expenses WHERE user_id = $user_id");
while ($row = $expenses->fetchArray(SQLITE3_ASSOC)) {
    $transactions[] = ['type' => 'expense', 'amount' => $row['amount'], 'category' => $row['category']];
}

// Totals
$total_income = $db->querySingle("SELECT IFNULL(SUM(amount), 0) FROM Income WHERE user_id = $user_id");
$total_expense = $db->querySingle("SELECT IFNULL(SUM(amount), 0) FROM Expenses WHERE user_id = $user_id");
$savings_goal = $db->querySingle("SELECT IFNULL(MAX(target_amount), 0) FROM Savings WHERE user_id = $user_id");

$current_savings = $total_income - $total_expense;
$savings_needed = max(0, $savings_goal - $current_savings);

// Fetch savings history from the Savings table
$savingsData = $db->query("SELECT created_at, current_amount FROM Savings WHERE user_id = $user_id ORDER BY created_at ASC");

$dates = $amounts = [];
while ($row = $savingsData->fetchArray(SQLITE3_ASSOC)) {
    $dates[] = $row['created_at'];
    $amounts[] = $row['current_amount'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="css/budget.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>JCW Financials - My Budget</title>
</head>
<body>
  <div class="budget-page">
    <header>
      <nav class="nav-bar">
        <a href="index.html" class="logo">JCW Financials</a>
        <ul class="nav-links">
          <li><a href="index.html" class="nav-link">Home</a></li>
        </ul>
        <div class="profile-menu">
          <img src="https://via.placeholder.com/40" alt="Profile" class="profile-pic" />
          <div class="dropdown-menu">
            <a href="account.html">Account</a>
            <a href="settings.html">Settings</a>
            <a href="logout.html">Logout</a>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <div class="input-sections-container">
        <!-- Income Section -->
        <section class="income-section">
          <form method="POST">
            <h2>Submit Income</h2>
            <label>Amount ($)
              <input type="number" name="income_amount" step="0.01" required />
            </label>
            <label>Category
              <input type="text" name="income_category" required />
            </label>
            <button type="submit" name="submit_income">Submit Income</button>
          </form>
        </section>

        <!-- Expense Section -->
        <section class="expense-section">
          <form method="POST">
            <h2>Submit Expense</h2>
            <label>Amount ($)
              <input type="number" name="expense_amount" step="0.01" required />
            </label>
            <label>Category
              <input type="text" name="expense_category" required />
            </label>
            <button type="submit" name="submit_expense">Submit Expense</button>
          </form>
        </section>

        <!-- Savings Section -->
        <section class="savings-section">
          <form method="POST">
            <h2>Savings Goal</h2>
            <label>Set Savings Goal ($)
              <input type="number" name="savings_goal" step="0.01" required />
            </label>
            <button type="submit" name="submit_savings">Set Goal</button>
          </form>
          <div class="goal-display">Your goal: $<?= number_format($savings_goal, 2) ?></div>
          <div class="savings-needed-display">You need to save: $<?= number_format($savings_needed, 2) ?> more</div>
        </section>
      </div>

      <!-- Money Log Section -->
      <section class="money-log-section">
        <h3>Money Saved Over Time</h3>
        <?php if (empty($transactions)): ?>
          <p>No transactions yet. Add income or expenses to see them here.</p>
        <?php else: ?>
          <?php foreach ($transactions as $t): ?>
            <div class="log-entry <?= $t['type'] ?>">
              <div class="entry-details">
                <span class="amount"><?= $t['type'] === 'income' ? '+' : '-' ?> $<?= number_format($t['amount'], 2) ?></span>
                <span class="category"><?= htmlspecialchars($t['category']) ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>

      <!-- Charts Section -->
      <section class="charts-section" style="margin-top: 40px;">
        <h3>Financial Overview</h3>
        <canvas id="incomeExpenseChart" height="100"></canvas>
        <canvas id="savingsProgressChart" height="100" style="margin-top: 40px;"></canvas>
        <canvas id="savingsTrendChart" height="100" style="margin-top: 40px;"></canvas>
      </section>
    </main>

    <footer>
      <div class="footer-links">
        <a href="About Us Wireframe.html" class="footer-link">About Us </a>
        <a href="contact.html" class="footer-link">Contact</a>
      </div>
      <p>&copy; 2023 JCW Financials. All rights reserved.</p>
    </footer>
  </div>

  <script>
    // Income vs Expenses Doughnut Chart
    const incomeExpenseChart = new Chart(document.getElementById('incomeExpenseChart'), {
      type: 'doughnut',
      data: {
        labels: <?= json_encode(array_merge($incomeLabels, $expenseLabels)) ?>,
        datasets: [{
          label: 'Income',
          data: <?= json_encode(array_merge($incomeAmounts, $expenseAmounts)) ?>,
          backgroundColor: [
            '#2ecc71', '#27ae60', '#1abc9c', '#16a085',
            '#e74c3c', '#c0392b', '#f39c12', '#d35400'
          ],
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Income & Expenses by Category'
          }
        }
      }
    });

    // Savings Goal Progress Chart
    const savingsProgressChart = new Chart(document.getElementById('savingsProgressChart'), {
      type: 'bar',
      data: {
        labels: ['Goal', 'Saved'],
        datasets: [{
          label: 'Savings Progress',
          data: [<?= $savings_goal ?>, <?= $current_savings ?>],
          backgroundColor: ['#f1c40f', '#2ecc71']
        }]
      },
      options: {
        indexAxis: 'y',
        plugins: {
          title: {
            display: true,
            text: 'Savings Goal Progress'
          }
        },
        scales: {
          x: {
            beginAtZero: true
          }
        }
      }
    });

    // Line chart for savings over time
    const savingsTrendChart = new Chart(document.getElementById('savingsTrendChart'), {
      type: 'line',
      data: {
        labels: <?= json_encode($dates) ?>,
        datasets: [{
          label: 'Savings Over Time',
          data: <?= json_encode($amounts) ?>,
          fill: true,
          backgroundColor: 'rgba(26, 188, 156, 0.2)',
          borderColor: '#1abc9c',
          tension: 0.3
        }]
      },
      options: {
        plugins: {
          title: {
            display: true,
            text: 'Your Savings Trend'
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</body>
</html>


