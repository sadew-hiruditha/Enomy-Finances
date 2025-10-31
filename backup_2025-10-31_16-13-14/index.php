<?php
require_once __DIR__ . '/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (login($username, $password)) {
        $role = $_SESSION['role'];
        if ($role === 'client') {
            header('Location: dashboard_client.php');
        } elseif ($role === 'staff') {
            header('Location: dashboard_staff.php');
        } else {
            header('Location: dashboard_admin.php');
        }
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enomy‑Finances Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="auth-container">
      <div class="auth-card fade-in">
        <div class="auth-header">
          <h4>🏦 Enomy‑Finances</h4>
          <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Sign in to your account</p>
        </div>
        <div class="auth-body">
          <?php if ($error): ?>
            <div class="modern-alert modern-alert-danger mb-3" role="alert">
              <?php echo htmlspecialchars($error); ?>
            </div>
          <?php endif; ?>
          <form method="POST" action="">
            <div class="mb-3">
              <label for="username" class="form-label fw-semibold">Username</label>
              <input type="text" class="form-control modern-input" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <input type="password" class="form-control modern-input" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn modern-btn modern-btn-primary w-100 mb-3">Sign In</button>
          </form>
          <div class="text-center">
            <p class="text-muted mb-0">Don't have an account? <a href="register.php" class="text-decoration-none fw-semibold" style="color: var(--primary-color);">Register here</a></p>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>