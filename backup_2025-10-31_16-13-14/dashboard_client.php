<?php
require_once __DIR__ . '/functions.php';
require_login();
$user = current_user();
if ($user['role'] !== 'client') {
    header('Location: index.php');
    exit();
}
$profile = get_client_profile($user['user_id']);
$mortgages = get_client_mortgages($profile['client_id']);
$investments = get_client_investments($profile['client_id']);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Dashboard - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class="modern-navbar">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="#">🏦 Enomy‑Finances</a>
          <div class="d-flex align-items-center gap-3">
            <span class="navbar-text">Welcome, <strong><?php echo htmlspecialchars($profile['full_name']); ?></strong></span>
            <a href="logout.php" class="btn btn-logout">Logout</a>
          </div>
        </div>
      </div>
    </nav>
    <div class="dashboard-container">
      <div class="container">
        <div class="mb-4">
          <h2 class="text-gradient fw-bold">Your Portfolio</h2>
          <p class="text-muted">Manage your mortgages and investments</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-primary">📊</div>
              <div class="stat-value"><?php echo count($mortgages); ?></div>
              <div class="stat-label">Active Mortgages</div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-success">💰</div>
              <div class="stat-value"><?php echo count($investments); ?></div>
              <div class="stat-label">Active Investments</div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-warning">📈</div>
              <div class="stat-value">$<?php 
                $total = 0;
                foreach ($mortgages as $m) $total += $m['principal_amount'];
                foreach ($investments as $i) $total += $i['amount'];
                echo number_format($total, 0);
              ?></div>
              <div class="stat-label">Total Portfolio Value</div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
          <div class="col-md-4 mb-3">
            <a href="currency_converter.php" class="text-decoration-none">
              <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E;">💱</div>
                <div class="stat-value" style="font-size: 1.5rem;">Convert</div>
                <div class="stat-label">Currency Converter</div>
              </div>
            </a>
          </div>
          <div class="col-md-4 mb-3">
            <a href="investment_calculator.php" class="text-decoration-none">
              <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #E0E7FF, #C7D2FE); color: #3730A3;">📈</div>
                <div class="stat-value" style="font-size: 1.5rem;">Calculate</div>
                <div class="stat-label">Investment Calculator</div>
              </div>
            </a>
          </div>
          <div class="col-md-4 mb-3">
            <a href="add_mortgage.php" class="text-decoration-none">
              <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135dvgb, #FEE2E2, #FECACA); color: #DC2626;">🏠</div>
                <div class="stat-value" style="font-size: 1.5rem;">Apply</div>
                <div class="stat-label">Mortgage Application</div>
              </div>
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6 mb-4">
            <div class="modern-card fade-in">
              <div class="modern-card-header d-flex justify-content-between align-items-center">
                <span>🏠 Mortgages</span>
                <a href="add_mortgage.php" class="btn btn-sm btn-light">+ Apply New</a>
              </div>
              <div class="p-0">
                <div class="modern-table">
                  <table class="table mb-0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Property</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($mortgages as $m): ?>
                        <tr>
                          <td><strong><?php echo $m['mortgage_id']; ?></strong></td>
                          <td><?php echo htmlspecialchars($m['property_address']); ?></td>
                          <td><strong>$<?php echo number_format($m['principal_amount'], 0); ?></strong></td>
                          <td><?php echo number_format($m['interest_rate'], 2); ?>%</td>
                          <td><span class="modern-badge badge-<?php echo $m['status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $m['status'])); ?></span></td>
                        </tr>
                      <?php endforeach; ?>
                      <?php if (empty($mortgages)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">No mortgages yet. <a href="add_mortgage.php" class="text-decoration-none">Apply for your first mortgage!</a></td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mb-4">
            <div class="modern-card fade-in">
              <div class="modern-card-header d-flex justify-content-between align-items-center">
                <span>💎 Investments</span>
                <a href="add_investment.php" class="btn btn-sm btn-light">+ Add Investment</a>
              </div>
              <div class="p-0">
                <div class="modern-table">
                  <table class="table mb-0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Maturity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($investments as $i): ?>
                        <tr>
                          <td><strong><?php echo $i['investment_id']; ?></strong></td>
                          <td><?php echo htmlspecialchars($i['product_type']); ?></td>
                          <td><strong>$<?php echo number_format($i['amount'], 0); ?></strong></td>
                          <td><?php echo number_format($i['interest_rate'], 2); ?>%</td>
                          <td><?php echo htmlspecialchars($i['maturity_date']); ?></td>
                        </tr>
                      <?php endforeach; ?>
                      <?php if (empty($investments)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">No investments yet. <a href="add_investment.php" class="text-decoration-none">Create your first investment!</a></td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>