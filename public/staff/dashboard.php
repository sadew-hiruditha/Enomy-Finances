<?php
require_once __DIR__ . '/../../includes/functions.php';
require_login();
$user = current_user();
if ($user['role'] !== 'staff') {
    header('Location: ../../index.php');
    exit();
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mortgage_id'], $_POST['action'])) {
    $mortgage_id = (int)$_POST['mortgage_id'];
    $action      = $_POST['action'];
    if (in_array($action, ['under_review','approved','rejected'])) {
        update_mortgage_status($mortgage_id, $action);
    }
}

$pending = get_pending_mortgages();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Dashboard - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class="modern-navbar">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="dashboard.php">🏦 Enomy‑Finances <span class="badge bg-info ms-2">Staff Panel</span></a>
          <div class="d-flex align-items-center gap-3">
            <span class="navbar-text">Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
            <a href="../../logout.php" class="btn btn-logout">Logout</a>
          </div>
        </div>
      </div>
    </nav>
    <div class="dashboard-container">
      <div class="container">
        <div class="mb-4">
          <h2 class="text-gradient fw-bold">Mortgage Applications</h2>
          <p class="text-muted">Review and manage client mortgage applications</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
          <div class="col-md-3 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-warning">⏳</div>
              <div class="stat-value"><?php echo count($pending); ?></div>
              <div class="stat-label">Pending Applications</div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <a href="manage_investments.php" class="text-decoration-none">
              <div class="stat-card">
                <div class="stat-icon stat-icon-success">💎</div>
                <div class="stat-value">View</div>
                <div class="stat-label">Manage Investments</div>
              </div>
            </a>
          </div>
        </div>

        <div class="modern-card fade-in">
          <div class="modern-card-header">📋 Application Queue</div>
          <div class="p-0">
            <div class="modern-table">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Property Address</th>
                    <th>Amount</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($pending as $app): ?>
                    <tr>
                      <td><strong>#<?php echo $app['mortgage_id']; ?></strong></td>
                      <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                      <td><?php echo htmlspecialchars($app['property_address']); ?></td>
                      <td><strong>$<?php echo number_format($app['principal_amount'], 0); ?></strong></td>
                      <td><?php echo number_format($app['interest_rate'], 2); ?>%</td>
                      <td><span class="modern-badge badge-<?php echo $app['status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $app['status'])); ?></span></td>
                      <td>
                        <form method="POST" class="d-inline">
                          <input type="hidden" name="mortgage_id" value="<?php echo $app['mortgage_id']; ?>">
                          <div class="btn-group btn-group-sm" role="group">
                            <button type="submit" name="action" value="under_review" class="btn modern-btn-warning">📝 Review</button>
                            <button type="submit" name="action" value="approved" class="btn modern-btn-success">✓ Approve</button>
                            <button type="submit" name="action" value="rejected" class="btn modern-btn-danger">✗ Reject</button>
                          </div>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <?php if (empty($pending)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-5">
                      <div class="py-4">
                        <h5>🎉 All caught up!</h5>
                        <p class="mb-0">No pending applications at the moment.</p>
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