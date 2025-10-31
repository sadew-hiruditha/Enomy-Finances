<?php
require_once __DIR__ . '/../../includes/functions.php';
require_login();
$user = current_user();
if (!in_array($user['role'], ['staff', 'admin'])) {
    header('Location: ../../index.php');
    exit();
}

// Handle delete action
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_investment_id'])) {
    $investment_id = (int)$_POST['delete_investment_id'];
    if (delete_investment($investment_id)) {
        $message = 'Investment deleted successfully.';
    } else {
        $error = 'Failed to delete investment.';
    }
}

$investments = get_all_investments();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investment Management - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class="modern-navbar">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="<?php echo $user['role'] === 'admin' ? '../admin/dashboard.php' : 'dashboard.php'; ?>">🏦 Enomy‑Finances <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'info'; ?> ms-2"><?php echo ucfirst($user['role']); ?> Panel</span></a>
          <div class="d-flex align-items-center gap-3">
            <a href="<?php echo $user['role'] === 'admin' ? '../admin/dashboard.php' : 'dashboard.php'; ?>" class="text-decoration-none">← Back to Dashboard</a>
            <span class="navbar-text">Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
            <a href="../../logout.php" class="btn btn-logout">Logout</a>
          </div>
        </div>
      </div>
    </nav>
    <div class="dashboard-container">
      <div class="container">
        <div class="mb-4">
          <h2 class="text-gradient fw-bold">Investment Portfolio Management</h2>
          <p class="text-muted">View and manage all client investments</p>
        </div>

        <?php if ($message): ?>
          <div class="modern-alert modern-alert-success mb-4"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="modern-alert modern-alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="row mb-4">
          <?php
            $totalInvestments = count($investments);
            $totalValue = array_sum(array_column($investments, 'amount'));
            $avgRate = $totalInvestments > 0 ? array_sum(array_column($investments, 'interest_rate')) / $totalInvestments : 0;
          ?>
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-primary">💼</div>
              <div class="stat-value"><?php echo $totalInvestments; ?></div>
              <div class="stat-label">Total Investments</div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-success">💰</div>
              <div class="stat-value">$<?php echo number_format($totalValue, 0); ?></div>
              <div class="stat-label">Total Investment Value</div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-warning">📊</div>
              <div class="stat-value"><?php echo number_format($avgRate, 2); ?>%</div>
              <div class="stat-label">Average Interest Rate</div>
            </div>
          </div>
        </div>

        <div class="modern-card fade-in">
          <div class="modern-card-header">📋 All Client Investments</div>
          <div class="p-0">
            <div class="modern-table">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Product Type</th>
                    <th>Amount</th>
                    <th>Interest Rate</th>
                    <th>Maturity Date</th>
                    <th>Created</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($investments as $inv): ?>
                    <tr>
                      <td><strong>#<?php echo $inv['investment_id']; ?></strong></td>
                      <td><?php echo htmlspecialchars($inv['full_name']); ?></td>
                      <td>
                        <span class="modern-badge badge-submitted">
                          <?php echo htmlspecialchars($inv['product_type']); ?>
                        </span>
                      </td>
                      <td><strong>$<?php echo number_format($inv['amount'], 2); ?></strong></td>
                      <td><?php echo number_format($inv['interest_rate'], 2); ?>%</td>
                      <td>
                        <?php 
                          $maturity = new DateTime($inv['maturity_date']);
                          $today = new DateTime();
                          $diff = $today->diff($maturity);
                          $daysLeft = $diff->format('%r%a');
                          
                          echo date('M d, Y', strtotime($inv['maturity_date']));
                          if ($daysLeft > 0) {
                            echo '<br><small class="text-success">(' . $daysLeft . ' days)</small>';
                          } elseif ($daysLeft == 0) {
                            echo '<br><small class="text-warning">(Today)</small>';
                          } else {
                            echo '<br><small class="text-danger">(Matured)</small>';
                          }
                        ?>
                      </td>
                      <td><?php echo date('M d, Y', strtotime($inv['created_at'])); ?></td>
                      <td>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this investment?');" style="display: inline;">
                          <input type="hidden" name="delete_investment_id" value="<?php echo $inv['investment_id']; ?>">
                          <button type="submit" class="btn btn-sm modern-btn-danger">Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <?php if (empty($investments)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-5">
                      <div class="py-4">
                        <h5>📊 No investments yet</h5>
                        <p class="mb-0">Clients haven't created any investments.</p>
                      </div>
                    </td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
