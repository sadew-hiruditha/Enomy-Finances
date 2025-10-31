<?php
require_once __DIR__ . '/functions.php';
require_login();
$user = current_user();
if ($user['role'] !== 'client') {
    header('Location: index.php');
    exit();
}
$profile = get_client_profile($user['user_id']);
$error = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'product_type'  => trim($_POST['product_type'] ?? ''),
        'amount'        => floatval($_POST['amount'] ?? 0),
        'interest_rate' => floatval($_POST['interest_rate'] ?? 0),
        'maturity_date' => trim($_POST['maturity_date'] ?? ''),
    ];
    if (empty($data['product_type']) || $data['amount'] <= 0 || $data['interest_rate'] <= 0 || empty($data['maturity_date'])) {
        $error = 'Please complete all fields with valid values.';
    } else {
        if (create_investment($profile['client_id'], $data)) {
            $success = true;
        } else {
            $error = 'Failed to create investment. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Investment - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class="modern-navbar">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="dashboard_client.php">← Back to Dashboard</a>
        </div>
      </div>
    </nav>
    <div class="dashboard-container">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="mb-4">
              <h2 class="text-gradient fw-bold">Create New Investment</h2>
              <p class="text-muted">Choose your investment product and set your financial goals</p>
            </div>

            <?php if ($success): ?>
              <div class="modern-card fade-in">
                <div class="modern-card-body text-center py-5">
                  <div class="mb-4">
                    <div style="font-size: 4rem;">💎</div>
                  </div>
                  <h3 class="text-gradient mb-3">Investment Created!</h3>
                  <p class="text-muted mb-4">Your investment has been successfully created and added to your portfolio.</p>
                  <a href="dashboard_client.php" class="btn modern-btn modern-btn-primary">Return to Dashboard</a>
                </div>
              </div>
            <?php else: ?>
              <?php if ($error): ?>
                <div class="modern-alert modern-alert-danger mb-4" role="alert">
                  <?php echo htmlspecialchars($error); ?>
                </div>
              <?php endif; ?>
              
              <div class="modern-card fade-in">
                <div class="modern-card-header">💰 Investment Details</div>
                <div class="modern-card-body">
                  <form method="POST" action="">
                    <div class="mb-4">
                      <label for="product_type" class="form-label fw-semibold">Investment Product Type</label>
                      <select class="form-select modern-input" id="product_type" name="product_type" required>
                        <option value="">Select an investment product...</option>
                        <option value="Fixed Deposit">Fixed Deposit</option>
                        <option value="Savings Bond">Savings Bond</option>
                        <option value="Money Market Fund">Money Market Fund</option>
                        <option value="Certificate of Deposit">Certificate of Deposit (CD)</option>
                        <option value="Treasury Bills">Treasury Bills</option>
                        <option value="Mutual Fund">Mutual Fund</option>
                        <option value="Index Fund">Index Fund</option>
                        <option value="Corporate Bonds">Corporate Bonds</option>
                        <option value="Retirement Fund">Retirement Fund</option>
                        <option value="High-Yield Savings">High-Yield Savings Account</option>
                      </select>
                      <small class="text-muted">Choose the type of investment product that suits your financial goals</small>
                    </div>
                    
                    <div class="row mb-4">
                      <div class="col-md-4">
                        <label for="amount" class="form-label fw-semibold">Investment Amount</label>
                        <div class="input-group">
                          <span class="input-group-text">$</span>
                          <input type="number" step="0.01" min="100" class="form-control modern-input" id="amount" name="amount" placeholder="0.00" required>
                        </div>
                        <small class="text-muted">Minimum: $100</small>
                      </div>
                      <div class="col-md-4">
                        <label for="interest_rate" class="form-label fw-semibold">Expected Interest Rate</label>
                        <div class="input-group">
                          <input type="number" step="0.01" min="0.01" max="100" class="form-control modern-input" id="interest_rate" name="interest_rate" placeholder="0.00" required>
                          <span class="input-group-text">% p.a.</span>
                        </div>
                        <small class="text-muted">Annual rate</small>
                      </div>
                      <div class="col-md-4">
                        <label for="maturity_date" class="form-label fw-semibold">Maturity Date</label>
                        <input type="date" class="form-control modern-input" id="maturity_date" name="maturity_date" min="<?php echo date('Y-m-d'); ?>" required>
                        <small class="text-muted">When funds mature</small>
                      </div>
                    </div>

                    <!-- Investment Calculator Preview -->
                    <div class="card mb-4" style="background: linear-gradient(135deg, #F0FDF4, #DCFCE7); border: none;">
                      <div class="card-body">
                        <h6 class="fw-bold mb-3">💡 Quick Estimate</h6>
                        <p class="text-muted mb-2">Based on your inputs, here's a simple calculation:</p>
                        <div class="d-flex justify-content-between">
                          <span>Principal Amount:</span>
                          <strong id="calc-principal">$0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                          <span>Estimated Annual Return:</span>
                          <strong id="calc-return">$0.00</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                          <span class="fw-bold">Estimated Maturity Value:</span>
                          <strong class="text-success" id="calc-total">$0.00</strong>
                        </div>
                      </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                      <button type="submit" class="btn modern-btn modern-btn-primary">Create Investment</button>
                      <a href="dashboard_client.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                  </form>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Simple investment calculator
      document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const rateInput = document.getElementById('interest_rate');
        const maturityInput = document.getElementById('maturity_date');
        
        function calculateInvestment() {
          const principal = parseFloat(amountInput.value) || 0;
          const rate = parseFloat(rateInput.value) || 0;
          const maturityDate = new Date(maturityInput.value);
          const today = new Date();
          
          // Calculate years
          const years = (maturityDate - today) / (1000 * 60 * 60 * 24 * 365);
          
          const annualReturn = principal * (rate / 100);
          const totalReturn = annualReturn * years;
          const maturityValue = principal + totalReturn;
          
          document.getElementById('calc-principal').textContent = '$' + principal.toFixed(2);
          document.getElementById('calc-return').textContent = '$' + annualReturn.toFixed(2);
          document.getElementById('calc-total').textContent = '$' + maturityValue.toFixed(2);
        }
        
        amountInput.addEventListener('input', calculateInvestment);
        rateInput.addEventListener('input', calculateInvestment);
        maturityInput.addEventListener('change', calculateInvestment);
      });
    </script>
  </body>
</html>
