<?php
require_once __DIR__ . '/../../includes/functions.php';
require_login();
$user = current_user();

$error = '';
$success = '';
$result = null;

// Currency conversion rates (updated regularly - in production, fetch from API)
$exchange_rates = [
    'GBP' => 1.0000,
    'USD' => 1.2750,
    'EUR' => 1.1650,
    'BRL' => 6.3500,
    'JPY' => 188.50,
    'TRY' => 34.75
];

$currency_symbols = [
    'GBP' => '£',
    'USD' => '$',
    'EUR' => '€',
    'BRL' => 'R$',
    'JPY' => '¥',
    'TRY' => '₺'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_currency = $_POST['from_currency'] ?? '';
    $to_currency = $_POST['to_currency'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);
    
    // Validation
    if (empty($from_currency) || empty($to_currency)) {
        $error = 'Please select both currencies.';
    } elseif ($from_currency === $to_currency) {
        $error = 'Please select different currencies for conversion.';
    } elseif ($amount < 300) {
        $error = 'Minimum transaction amount is 300 in the initial currency.';
    } elseif ($amount > 5000) {
        $error = 'Maximum transaction amount is 5000 in the initial currency.';
    } else {
        // Calculate conversion
        $from_rate = $exchange_rates[$from_currency];
        $to_rate = $exchange_rates[$to_currency];
        
        // Convert to GBP first, then to target currency
        $gbp_amount = $amount / $from_rate;
        $converted_amount = $gbp_amount * $to_rate;
        
        // Calculate fee based on initial amount
        if ($amount <= 500) {
            $fee_percent = 3.5;
        } elseif ($amount <= 1500) {
            $fee_percent = 2.7;
        } elseif ($amount <= 2500) {
            $fee_percent = 2.0;
        } else {
            $fee_percent = 1.5;
        }
        
        $fee_amount = ($converted_amount * $fee_percent) / 100;
        $final_amount = $converted_amount - $fee_amount;
        
        $result = [
            'from_currency' => $from_currency,
            'to_currency' => $to_currency,
            'amount' => $amount,
            'converted_amount' => $converted_amount,
            'fee_percent' => $fee_percent,
            'fee_amount' => $fee_amount,
            'final_amount' => $final_amount,
            'exchange_rate' => $to_rate / $from_rate
        ];
        
        // Save transaction to database
        $profile = get_client_profile($user['user_id']);
        if ($profile) {
            save_currency_transaction($profile['client_id'], $result);
            $success = 'Currency conversion calculated successfully!';
        }
    }
}

// Get transaction history
$profile = get_client_profile($user['user_id']);
$transactions = $profile ? get_currency_transactions($profile['client_id']) : [];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Currency Converter - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class="modern-navbar">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="dashboard.php">← Back to Dashboard</a>
          <div class="d-flex align-items-center gap-3">
            <a href="dashboard_client.php" class="text-decoration-none">Dashboard</a>
            <a href="logout.php" class="btn btn-logout">Logout</a>
          </div>
        </div>
      </div>
    </nav>
    <div class="dashboard-container">
      <div class="container">
        <div class="mb-4">
          <h2 class="text-gradient fw-bold">Currency Converter</h2>
          <p class="text-muted">Convert between GBP, USD, EUR, BRL, JPY, and TRY with real-time rates</p>
        </div>

        <!-- Exchange Rates Display -->
        <div class="row mb-4">
          <?php foreach ($exchange_rates as $code => $rate): ?>
            <div class="col-md-2 mb-3">
              <div class="stat-card text-center" style="padding: 1rem;">
                <div style="font-size: 1.5rem; margin-bottom: 0.5rem;"><?php echo $currency_symbols[$code]; ?></div>
                <div class="fw-bold"><?php echo $code; ?></div>
                <small class="text-muted"><?php echo number_format($rate, 4); ?></small>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="row">
          <div class="col-lg-8">
            <?php if ($error): ?>
              <div class="modern-alert modern-alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
              <div class="modern-alert modern-alert-success mb-4"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <div class="modern-card fade-in mb-4">
              <div class="modern-card-header">💱 Currency Conversion Calculator</div>
              <div class="modern-card-body">
                <form method="POST" id="currencyForm">
                  <div class="row mb-4">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">From Currency</label>
                      <select class="form-select modern-input" name="from_currency" id="fromCurrency" required>
                        <option value="">Select currency...</option>
                        <?php foreach ($exchange_rates as $code => $rate): ?>
                          <option value="<?php echo $code; ?>" <?php echo (isset($_POST['from_currency']) && $_POST['from_currency'] === $code) ? 'selected' : ''; ?>>
                            <?php echo $currency_symbols[$code]; ?> <?php echo $code; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">To Currency</label>
                      <select class="form-select modern-input" name="to_currency" id="toCurrency" required>
                        <option value="">Select currency...</option>
                        <?php foreach ($exchange_rates as $code => $rate): ?>
                          <option value="<?php echo $code; ?>" <?php echo (isset($_POST['to_currency']) && $_POST['to_currency'] === $code) ? 'selected' : ''; ?>>
                            <?php echo $currency_symbols[$code]; ?> <?php echo $code; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="mb-4">
                    <label class="form-label fw-semibold">Amount to Convert</label>
                    <input type="number" step="0.01" min="300" max="5000" class="form-control modern-input" name="amount" id="amount" placeholder="Enter amount (300 - 5000)" value="<?php echo isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : ''; ?>" required>
                    <small class="text-muted">Min: 300 | Max: 5000 in initial currency</small>
                  </div>

                  <div class="alert" style="background: linear-gradient(135deg, #F0FDF4, #DCFCE7); border: none;">
                    <h6 class="fw-bold mb-2">💡 Transaction Fees</h6>
                    <div class="row">
                      <div class="col-6 col-md-3"><small>Up to 500: <strong>3.5%</strong></small></div>
                      <div class="col-6 col-md-3"><small>Over 500: <strong>2.7%</strong></small></div>
                      <div class="col-6 col-md-3"><small>Over 1500: <strong>2.0%</strong></small></div>
                      <div class="col-6 col-md-3"><small>Over 2500: <strong>1.5%</strong></small></div>
                    </div>
                  </div>

                  <button type="submit" class="btn modern-btn modern-btn-primary">Calculate Conversion</button>
                </form>
              </div>
            </div>

            <?php if ($result): ?>
            <div class="modern-card fade-in">
              <div class="modern-card-header">✅ Conversion Result</div>
              <div class="modern-card-body">
                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="p-3 mb-3" style="background: #F9FAFB; border-radius: 10px;">
                      <small class="text-muted">You Send</small>
                      <h3 class="mb-0 text-gradient"><?php echo $currency_symbols[$result['from_currency']]; ?><?php echo number_format($result['amount'], 2); ?></h3>
                      <small class="text-muted"><?php echo $result['from_currency']; ?></small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="p-3 mb-3" style="background: linear-gradient(135deg, #D1FAE5, #A7F3D0); border-radius: 10px;">
                      <small class="text-muted">They Receive</small>
                      <h3 class="mb-0 fw-bold" style="color: #065F46;"><?php echo $currency_symbols[$result['to_currency']]; ?><?php echo number_format($result['final_amount'], 2); ?></h3>
                      <small class="text-muted"><?php echo $result['to_currency']; ?></small>
                    </div>
                  </div>
                </div>

                <table class="table">
                  <tr>
                    <td>Exchange Rate:</td>
                    <td class="text-end"><strong>1 <?php echo $result['from_currency']; ?> = <?php echo number_format($result['exchange_rate'], 4); ?> <?php echo $result['to_currency']; ?></strong></td>
                  </tr>
                  <tr>
                    <td>Converted Amount:</td>
                    <td class="text-end"><?php echo $currency_symbols[$result['to_currency']]; ?><?php echo number_format($result['converted_amount'], 2); ?></td>
                  </tr>
                  <tr>
                    <td>Transaction Fee (<?php echo $result['fee_percent']; ?>%):</td>
                    <td class="text-end text-danger">- <?php echo $currency_symbols[$result['to_currency']]; ?><?php echo number_format($result['fee_amount'], 2); ?></td>
                  </tr>
                  <tr class="table-active">
                    <td class="fw-bold">Final Amount:</td>
                    <td class="text-end fw-bold text-success"><?php echo $currency_symbols[$result['to_currency']]; ?><?php echo number_format($result['final_amount'], 2); ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <?php endif; ?>
          </div>

          <div class="col-lg-4">
            <div class="modern-card fade-in">
              <div class="modern-card-header">📊 Recent Transactions</div>
              <div class="modern-card-body">
                <?php if (empty($transactions)): ?>
                  <p class="text-muted text-center py-4">No transactions yet</p>
                <?php else: ?>
                  <?php foreach (array_slice($transactions, 0, 5) as $trans): ?>
                    <div class="p-2 mb-2" style="background: #F9FAFB; border-radius: 8px;">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <strong><?php echo $trans['from_currency']; ?> → <?php echo $trans['to_currency']; ?></strong>
                          <br><small class="text-muted"><?php echo date('M d, Y', strtotime($trans['created_at'])); ?></small>
                        </div>
                        <div class="text-end">
                          <strong><?php echo number_format($trans['amount'], 2); ?></strong>
                          <br><small class="text-success"><?php echo number_format($trans['final_amount'], 2); ?></small>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
