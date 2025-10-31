<?php
require_once __DIR__ . '/includes/functions.php';

$error = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username'      => trim($_POST['username'] ?? ''),
        'password'      => trim($_POST['password'] ?? ''),
        'full_name'     => trim($_POST['full_name'] ?? ''),
        'address'       => trim($_POST['address'] ?? ''),
        'phone'         => trim($_POST['phone'] ?? ''),
        'email'         => trim($_POST['email'] ?? ''),
        'date_of_birth' => trim($_POST['date_of_birth'] ?? ''),
    ];
    // Basic validation
    if (empty($data['username']) || empty($data['password']) || empty($data['full_name'])) {
        $error = 'Please fill in all required fields.';
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->execute([$data['username']]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username already taken.';
        } else {
            if (register_client($data)) {
                $success = true;
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="auth-container">
      <div class="auth-card fade-in" style="max-width: 600px;">
        <div class="auth-header">
          <h4>🏦 Enomy‑Finances</h4>
          <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Create your account</p>
        </div>
        <div class="auth-body">
          <?php if ($success): ?>
            <div class="modern-alert modern-alert-success">
              Registration successful! <a href="index.php" class="text-decoration-none fw-semibold" style="color: #065F46;">Click here to login.</a>
            </div>
          <?php else: ?>
            <?php if ($error): ?>
              <div class="modern-alert modern-alert-danger mb-3" role="alert">
                <?php echo htmlspecialchars($error); ?>
              </div>
            <?php endif; ?>
            <form method="POST" action="">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="username" class="form-label fw-semibold">Username</label>
                  <input type="text" class="form-control modern-input" id="username" name="username" placeholder="Choose username" required value="<?php echo isset($data['username']) ? htmlspecialchars($data['username']) : ''; ?>">
                </div>
                <div class="col-md-6">
                  <label for="password" class="form-label fw-semibold">Password</label>
                  <input type="password" class="form-control modern-input" id="password" name="password" placeholder="Choose password" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="full_name" class="form-label fw-semibold">Full Name</label>
                <input type="text" class="form-control modern-input" id="full_name" name="full_name" placeholder="Enter your full name" required value="<?php echo isset($data['full_name']) ? htmlspecialchars($data['full_name']) : ''; ?>">
              </div>
              <div class="mb-3">
                <label for="address" class="form-label fw-semibold">Address</label>
                <input type="text" class="form-control modern-input" id="address" name="address" placeholder="Enter your address" value="<?php echo isset($data['address']) ? htmlspecialchars($data['address']) : ''; ?>">
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="phone" class="form-label fw-semibold">Phone</label>
                  <input type="text" class="form-control modern-input" id="phone" name="phone" placeholder="Enter phone number" value="<?php echo isset($data['phone']) ? htmlspecialchars($data['phone']) : ''; ?>">
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label fw-semibold">Email</label>
                  <input type="email" class="form-control modern-input" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>">
                </div>
              </div>
              <div class="mb-3">
                <label for="date_of_birth" class="form-label fw-semibold">Date of Birth</label>
                <input type="date" class="form-control modern-input" id="date_of_birth" name="date_of_birth" value="<?php echo isset($data['date_of_birth']) ? htmlspecialchars($data['date_of_birth']) : ''; ?>">
              </div>
              <button type="submit" class="btn modern-btn modern-btn-primary w-100 mb-3">Create Account</button>
            </form>
          <?php endif; ?>
          <div class="text-center">
            <p class="text-muted mb-0">Already have an account? <a href="index.php" class="text-decoration-none fw-semibold" style="color: var(--primary-color);">Sign in here</a></p>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>