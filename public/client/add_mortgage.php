<?php
require_once __DIR__ . '/../../includes/functions.php';
require_login();
$user = current_user();
if ($user['role'] !== 'client') {
    header('Location: ../../index.php');
    exit();
}
$profile = get_client_profile($user['user_id']);
$error = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'property_address'  => trim($_POST['property_address'] ?? ''),
        'principal_amount'  => floatval($_POST['principal_amount'] ?? 0),
        'interest_rate'     => floatval($_POST['interest_rate'] ?? 0),
        'term_years'        => intval($_POST['term_years'] ?? 0),
    ];
    if (empty($data['property_address']) || $data['principal_amount'] <= 0 || $data['interest_rate'] <= 0 || $data['term_years'] <= 0) {
        $error = 'Please complete all fields with valid values.';
    } else {
        if (create_mortgage($profile['client_id'], $data)) {
            $success = true;
        } else {
            $error = 'Failed to submit application. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply for Mortgage - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class="modern-navbar">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="dashboard.php">← Back to Dashboard</a>
        </div>
      </div>
    </nav>
    <div class="dashboard-container">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="mb-4">
              <h2 class="text-gradient fw-bold">Mortgage Application</h2>
              <p class="text-muted">Fill in the details below to apply for a new mortgage</p>
            </div>

            <?php if ($success): ?>
              <div class="modern-card fade-in">
                <div class="modern-card-body text-center py-5">
                  <div class="mb-4">
                    <div style="font-size: 4rem;">✅</div>
                  </div>
                  <h3 class="text-gradient mb-3">Application Submitted!</h3>
                  <p class="text-muted mb-4">Your mortgage application has been successfully submitted and is now under review.</p>
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
                <div class="modern-card-header">🏠 Property & Loan Details</div>
                <div class="modern-card-body">
                  <form method="POST" action="">
                    <div class="mb-4">
                      <label for="property_address" class="form-label fw-semibold">Property Address</label>
                      <input type="text" class="form-control modern-input" id="property_address" name="property_address" placeholder="Enter the full property address" required>
                    </div>
                    
                    <div class="row mb-4">
                      <div class="col-md-4">
                        <label for="principal_amount" class="form-label fw-semibold">Principal Amount</label>
                        <div class="input-group">
                          <span class="input-group-text">$</span>
                          <input type="number" step="0.01" class="form-control modern-input" id="principal_amount" name="principal_amount" placeholder="0.00" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label for="interest_rate" class="form-label fw-semibold">Interest Rate</label>
                        <div class="input-group">
                          <input type="number" step="0.01" class="form-control modern-input" id="interest_rate" name="interest_rate" placeholder="0.00" required>
                          <span class="input-group-text">%</span>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label for="term_years" class="form-label fw-semibold">Term (Years)</label>
                        <input type="number" class="form-control modern-input" id="term_years" name="term_years" placeholder="30" required>
                      </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                      <button type="submit" class="btn modern-btn modern-btn-primary">Submit Application</button>
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
  </body>
</html>