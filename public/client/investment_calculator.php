<?php
require_once __DIR__ . '/../../includes/functions.php';
require_login();
$user = current_user();

$error = '';
$success = '';
$quote = null;

// Investment plan configurations
$investment_plans = [
    'basic' => [
        'name' => 'Basic Savings Plan',
        'max_per_year' => 20000,
        'min_monthly' => 50,
        'min_initial' => 0,
        'return_min' => 1.2,
        'return_max' => 2.4,
        'tax_threshold_1' => 12000,
        'tax_rate_1' => 0,
        'tax_threshold_2' => 0,
        'tax_rate_2' => 0,
        'monthly_fee' => 0.25
    ],
    'plus' => [
        'name' => 'Savings Plan Plus',
        'max_per_year' => 30000,
        'min_monthly' => 50,
        'min_initial' => 300,
        'return_min' => 3.0,
        'return_max' => 5.5,
        'tax_threshold_1' => 12000,
        'tax_rate_1' => 10,
        'tax_threshold_2' => 0,
        'tax_rate_2' => 0,
        'monthly_fee' => 0.3
    ],
    'managed' => [
        'name' => 'Managed Stock Investments',
        'max_per_year' => PHP_INT_MAX,
        'min_monthly' => 150,
        'min_initial' => 1000,
        'return_min' => 4.0,
        'return_max' => 23.0,
        'tax_threshold_1' => 12000,
        'tax_rate_1' => 10,
        'tax_threshold_2' => 40000,
        'tax_rate_2' => 20,
        'monthly_fee' => 1.3
    ]
];

function calculate_investment_quote($plan_config, $initial_lump, $monthly_amount, $years) {
    $results = ['min' => [], 'max' => []];
    
    foreach (['min', 'max'] as $scenario) {
        $return_rate = $scenario === 'min' ? $plan_config['return_min'] : $plan_config['return_max'];
        $monthly_rate = $return_rate / 12 / 100;
        $fee_rate = $plan_config['monthly_fee'] / 100;
        
        $balance = $initial_lump;
        $total_invested = $initial_lump;
        $total_fees = 0;
        $months = $years * 12;
        
        for ($month = 1; $month <= $months; $month++) {
            // Add monthly investment
            $balance += $monthly_amount;
            $total_invested += $monthly_amount;
            
            // Apply monthly return
            $monthly_return = $balance * $monthly_rate;
            $balance += $monthly_return;
            
            // Deduct monthly fee
            $monthly_fee = $balance * $fee_rate;
            $balance -= $monthly_fee;
            $total_fees += $monthly_fee;
        }
        
        $total_profit = $balance - $total_invested;
        
        // Calculate tax
        $tax = 0;
        if ($total_profit > 0) {
            if ($plan_config['tax_rate_2'] > 0 && $total_profit > $plan_config['tax_threshold_2']) {
                $tax_tier_1 = ($plan_config['tax_threshold_1']) * ($plan_config['tax_rate_1'] / 100);
                $tax_tier_2 = ($plan_config['tax_threshold_2'] - $plan_config['tax_threshold_1']) * ($plan_config['tax_rate_1'] / 100);
                $tax_tier_3 = ($total_profit - $plan_config['tax_threshold_2']) * ($plan_config['tax_rate_2'] / 100);
                $tax = $tax_tier_1 + $tax_tier_2 + $tax_tier_3;
            } elseif ($plan_config['tax_rate_1'] > 0 && $total_profit > $plan_config['tax_threshold_1']) {
                $taxable_profit = $total_profit - $plan_config['tax_threshold_1'];
                $tax = $taxable_profit * ($plan_config['tax_rate_1'] / 100);
            }
        }
        
        $final_value = $balance - $tax;
        
        $results[$scenario] = [
            'value' => $final_value,
            'profit' => $total_profit,
            'fees' => $total_fees,
            'tax' => $tax,
            'invested' => $total_invested
        ];
    }
    
    return $results;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $investment_type = $_POST['investment_type'] ?? '';
    $initial_lump_sum = floatval($_POST['initial_lump_sum'] ?? 0);
    $monthly_investment = floatval($_POST['monthly_investment'] ?? 0);
    
    if (empty($investment_type) || !isset($investment_plans[$investment_type])) {
        $error = 'Please select a valid investment type.';
    } else {
        $plan = $investment_plans[$investment_type];
        
        // Validation
        if ($monthly_investment < $plan['min_monthly']) {
            $error = "Minimum monthly investment for {$plan['name']} is £{$plan['min_monthly']}.";
        } elseif ($initial_lump_sum < $plan['min_initial']) {
            $error = "Minimum initial lump sum for {$plan['name']} is £{$plan['min_initial']}.";
        } elseif ($plan['max_per_year'] != PHP_INT_MAX) {
            $annual_total = $initial_lump_sum + ($monthly_investment * 12);
            if ($annual_total > $plan['max_per_year']) {
                $error = "Maximum annual investment for {$plan['name']} is £{$plan['max_per_year']}.";
            }
        }
        
        if (empty($error)) {
            // Calculate quotes for 1, 5, and 10 years
            $quote = [
                'plan_name' => $plan['name'],
                'initial_lump_sum' => $initial_lump_sum,
                'monthly_investment' => $monthly_investment,
                '1_year' => calculate_investment_quote($plan, $initial_lump_sum, $monthly_investment, 1),
                '5_years' => calculate_investment_quote($plan, $initial_lump_sum, $monthly_investment, 5),
                '10_years' => calculate_investment_quote($plan, $initial_lump_sum, $monthly_investment, 10),
                'plan_details' => $plan
            ];
            
            // Save quote
            $profile = get_client_profile($user['user_id']);
            if ($profile) {
                save_investment_quote($profile['client_id'], [
                    'investment_type' => $investment_type,
                    'initial_lump_sum' => $initial_lump_sum,
                    'monthly_investment' => $monthly_investment,
                    'quote' => $quote
                ]);
                $success = 'Investment quote generated and saved!';
            }
        }
    }
}

$profile = get_client_profile($user['user_id']);
$saved_quotes = $profile ? get_investment_quotes($profile['client_id']) : [];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investment Calculator - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
      .plan-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
      }
      .plan-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
      }
      .plan-card.selected {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
      }
    </style>
  </head>
  <body>
    <nav class="modern-navbar">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="dashboard.php">← Back to Dashboard</a>
          <div class="d-flex align-items-center gap-3">
            <a href="currency_converter.php" class="text-decoration-none">Currency Converter</a>
            <a href="dashboard_client.php" class="text-decoration-none">Dashboard</a>
            <a href="logout.php" class="btn btn-logout">Logout</a>
          </div>
        </div>
      </div>
    </nav>
    <div class="dashboard-container">
      <div class="container">
        <div class="mb-4">
          <h2 class="text-gradient fw-bold">Investment & Savings Calculator</h2>
          <p class="text-muted">Get personalized investment quotes with projected returns</p>
        </div>

        <?php if ($error): ?>
          <div class="modern-alert modern-alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
          <div class="modern-alert modern-alert-success mb-4"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="row">
          <div class="col-lg-8">
            <div class="modern-card fade-in mb-4">
              <div class="modern-card-header">📊 Investment Calculator</div>
              <div class="modern-card-body">
                <form method="POST" id="investmentForm">
                  <h6 class="fw-bold mb-3">Select Investment Plan</h6>
                  <style>
                    .plan-card {
                      border-radius: 12px;
                      background: white;
                      border: 2px solid #E5E7EB;
                      cursor: pointer;
                      transition: all 0.3s ease;
                      position: relative;
                    }
                    .plan-card:hover {
                      border-color: #6366F1;
                      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
                      transform: translateY(-2px);
                    }
                    .plan-card.selected {
                      border-color: #6366F1;
                      background: linear-gradient(135deg, #F0F1FF, #FFFFFF);
                      box-shadow: 0 4px 16px rgba(99, 102, 241, 0.25);
                    }
                    .plan-card.selected::before {
                      content: '✓';
                      position: absolute;
                      top: 10px;
                      right: 10px;
                      background: #6366F1;
                      color: white;
                      width: 24px;
                      height: 24px;
                      border-radius: 50%;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                      font-size: 14px;
                      font-weight: bold;
                    }
                  </style>
                  <div class="row mb-4">
                    <?php foreach ($investment_plans as $key => $plan): ?>
                      <div class="col-md-4 mb-3">
                        <div class="plan-card p-3" onclick="selectPlan('<?php echo $key; ?>')">
                          <input type="radio" name="investment_type" value="<?php echo $key; ?>" id="plan_<?php echo $key; ?>" style="display: none;" required <?php echo (isset($_POST['investment_type']) && $_POST['investment_type'] === $key) ? 'checked' : ''; ?>>
                          <h6 class="fw-bold mb-2"><?php echo $plan['name']; ?></h6>
                          <div class="mb-2"><small><strong>Returns:</strong> <?php echo $plan['return_min']; ?>% - <?php echo $plan['return_max']; ?>%</small></div>
                          <div class="mb-2"><small><strong>Min Monthly:</strong> £<?php echo number_format($plan['min_monthly']); ?></small></div>
                          <div class="mb-2"><small><strong>Min Initial:</strong> <?php echo $plan['min_initial'] > 0 ? '£' . number_format($plan['min_initial']) : 'N/A'; ?></small></div>
                          <div><small><strong>Fee:</strong> <?php echo $plan['monthly_fee']; ?>%/month</small></div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Initial Lump Sum (£)</label>
                      <input type="number" step="0.01" min="0" class="form-control modern-input" name="initial_lump_sum" placeholder="0.00" value="<?php echo isset($_POST['initial_lump_sum']) ? htmlspecialchars($_POST['initial_lump_sum']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Monthly Investment (£)</label>
                      <input type="number" step="0.01" min="0" class="form-control modern-input" name="monthly_investment" placeholder="0.00" value="<?php echo isset($_POST['monthly_investment']) ? htmlspecialchars($_POST['monthly_investment']) : ''; ?>" required>
                    </div>
                  </div>

                  <button type="submit" class="btn modern-btn modern-btn-primary">Generate Investment Quote</button>
                </form>
              </div>
            </div>

            <?php if ($quote): ?>
            <div class="modern-card fade-in mb-4">
              <div class="modern-card-header">✅ Your Personalized Investment Quote</div>
              <div class="modern-card-body">
                <div class="alert" style="background: linear-gradient(135deg, #F0FDF4, #DCFCE7); border: none; margin-bottom: 1.5rem;">
                  <h5 class="fw-bold mb-2"><?php echo $quote['plan_name']; ?></h5>
                  <div class="row">
                    <div class="col-6"><strong>Initial Investment:</strong> £<?php echo number_format($quote['initial_lump_sum'], 2); ?></div>
                    <div class="col-6"><strong>Monthly Investment:</strong> £<?php echo number_format($quote['monthly_investment'], 2); ?></div>
                  </div>
                </div>

                <?php foreach (['1_year' => '1 Year', '5_years' => '5 Years', '10_years' => '10 Years'] as $period => $label): ?>
                <div class="mb-4">
                  <h6 class="fw-bold mb-3"><?php echo $label; ?> Projection</h6>
                  <div class="table-responsive">
                    <table class="table modern-table">
                      <thead>
                        <tr>
                          <th>Scenario</th>
                          <th>Expected Value</th>
                          <th>Total Profit</th>
                          <th>Total Fees</th>
                          <th>Total Tax</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><strong>Minimum Return</strong> (<?php echo $quote['plan_details']['return_min']; ?>%)</td>
                          <td class="fw-bold text-success">£<?php echo number_format($quote[$period]['min']['value'], 2); ?></td>
                          <td>£<?php echo number_format($quote[$period]['min']['profit'], 2); ?></td>
                          <td class="text-danger">£<?php echo number_format($quote[$period]['min']['fees'], 2); ?></td>
                          <td class="text-danger">£<?php echo number_format($quote[$period]['min']['tax'], 2); ?></td>
                        </tr>
                        <tr>
                          <td><strong>Maximum Return</strong> (<?php echo $quote['plan_details']['return_max']; ?>%)</td>
                          <td class="fw-bold text-success">£<?php echo number_format($quote[$period]['max']['value'], 2); ?></td>
                          <td>£<?php echo number_format($quote[$period]['max']['profit'], 2); ?></td>
                          <td class="text-danger">£<?php echo number_format($quote[$period]['max']['fees'], 2); ?></td>
                          <td class="text-danger">£<?php echo number_format($quote[$period]['max']['tax'], 2); ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <?php endforeach; ?>

                <div class="alert" style="background: #FEF3C7; border: none;">
                  <strong>📌 Note:</strong> All values are formatted in GBP (£) to two decimal places. Returns are estimated and not guaranteed. Tax calculations are based on current regulations.
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>

          <div class="col-lg-4">
            <div class="modern-card fade-in mb-4">
              <div class="modern-card-header">📋 Plan Details</div>
              <div class="modern-card-body">
                <?php foreach ($investment_plans as $key => $plan): ?>
                  <div class="mb-3 p-3" style="background: #F9FAFB; border-radius: 8px;">
                    <h6 class="fw-bold"><?php echo $plan['name']; ?></h6>
                    <small>
                      <strong>Max/Year:</strong> <?php echo $plan['max_per_year'] == PHP_INT_MAX ? 'Unlimited' : '£' . number_format($plan['max_per_year']); ?><br>
                      <strong>Returns:</strong> <?php echo $plan['return_min']; ?>% - <?php echo $plan['return_max']; ?>%<br>
                      <strong>Tax:</strong> 
                      <?php if ($plan['tax_rate_1'] == 0): ?>
                        0%
                      <?php else: ?>
                        <?php echo $plan['tax_rate_1']; ?>% on profits above £<?php echo number_format($plan['tax_threshold_1']); ?>
                        <?php if ($plan['tax_rate_2'] > 0): ?>
                          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $plan['tax_rate_2']; ?>% on profits above £<?php echo number_format($plan['tax_threshold_2']); ?>
                        <?php endif; ?>
                      <?php endif; ?>
                      <br>
                      <strong>Fee:</strong> <?php echo $plan['monthly_fee']; ?>%/month
                    </small>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="modern-card fade-in">
              <div class="modern-card-header">💾 Saved Quotes</div>
              <div class="modern-card-body">
                <?php if (empty($saved_quotes)): ?>
                  <p class="text-muted text-center">No saved quotes</p>
                <?php else: ?>
                  <?php foreach (array_slice($saved_quotes, 0, 5) as $sq): ?>
                    <div class="p-2 mb-2" style="background: #F9FAFB; border-radius: 8px;">
                      <small>
                        <strong><?php echo $investment_plans[$sq['investment_type']]['name']; ?></strong><br>
                        Initial: £<?php echo number_format($sq['initial_lump_sum'], 2); ?><br>
                        Monthly: £<?php echo number_format($sq['monthly_investment'], 2); ?><br>
                        <span class="text-muted"><?php echo date('M d, Y', strtotime($sq['created_at'])); ?></span>
                      </small>
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
    <script>
      function selectPlan(planKey) {
        // Remove selected class from all cards
        document.querySelectorAll('.plan-card').forEach(card => {
          card.classList.remove('selected');
        });
        
        // Check the radio button
        const radio = document.getElementById('plan_' + planKey);
        radio.checked = true;
        
        // Add selected class to clicked card
        radio.closest('.plan-card').classList.add('selected');
      }
      
      // Select on page load if already selected (after form submission)
      document.addEventListener('DOMContentLoaded', function() {
        const selected = document.querySelector('input[name="investment_type"]:checked');
        if (selected) {
          selected.closest('.plan-card').classList.add('selected');
        }
        
        // Add keyboard navigation
        document.querySelectorAll('.plan-card').forEach(card => {
          card.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              const radio = this.querySelector('input[type="radio"]');
              selectPlan(radio.value);
            }
          });
          card.setAttribute('tabindex', '0');
        });
      });
    </script>
  </body>
</html>