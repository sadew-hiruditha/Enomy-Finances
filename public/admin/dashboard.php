<?php
require_once __DIR__ . '/../../includes/functions.php';
require_login();
$user = current_user();
if ($user['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

// Handle user creation (minimal)
$create_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_username'], $_POST['new_password'], $_POST['new_role'])) {
    $nu = trim($_POST['new_username']);
    $np = trim($_POST['new_password']);
    $nr = trim($_POST['new_role']);
    if ($nu && $np && in_array($nr, ['client','staff','admin'])) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->execute([$nu]);
        if ($stmt->fetchColumn() > 0) {
            $create_error = 'Username already exists.';
        } else {
            $hash = password_hash($np, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
            $stmt->execute([$nu, $hash, $nr]);
        }
    }
}
// Fetch all users
$users = $pdo->query('SELECT user_id, username, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class="modern-navbar">
      <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand" href="dashboard.php">🏦 Enomy‑Finances <span class="badge bg-danger ms-2">Admin Panel</span></a>
          <div class="d-flex align-items-center gap-3">
            <span class="navbar-text">Admin: <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
            <a href="../../logout.php" class="btn btn-logout">Logout</a>
          </div>
        </div>
      </div>
    </nav>
    <div class="dashboard-container">
      <div class="container">
        <div class="mb-4">
          <h2 class="text-gradient fw-bold">User Management</h2>
          <p class="text-muted">Create and manage system users</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
          <?php
            $clients = array_filter($users, fn($u) => $u['role'] === 'client');
            $staff = array_filter($users, fn($u) => $u['role'] === 'staff');
            $admins = array_filter($users, fn($u) => $u['role'] === 'admin');
          ?>
          <div class="col-md-3 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-primary">👥</div>
              <div class="stat-value"><?php echo count($users); ?></div>
              <div class="stat-label">Total Users</div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-success">👤</div>
              <div class="stat-value"><?php echo count($clients); ?></div>
              <div class="stat-label">Clients</div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="stat-card">
              <div class="stat-icon stat-icon-warning">💼</div>
              <div class="stat-value"><?php echo count($staff); ?></div>
              <div class="stat-label">Staff Members</div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="stat-card">
              <div class="stat-icon" style="background: linear-gradient(135deg, #FEE2E2, #FECACA); color: #DC2626;">🔐</div>
              <div class="stat-value"><?php echo count($admins); ?></div>
              <div class="stat-label">Administrators</div>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="row mb-4">
          <div class="col-md-6 mb-3">
            <a href="../staff/manage_investments.php" class="text-decoration-none">
              <div class="stat-card">
                <div class="stat-icon stat-icon-success">💎</div>
                <div class="stat-value">Manage</div>
                <div class="stat-label">Client Investments</div>
              </div>
            </a>
          </div>
          <div class="col-md-6 mb-3">
            <a href="../staff/dashboard.php" class="text-decoration-none">
              <div class="stat-card">
                <div class="stat-icon stat-icon-warning">📋</div>
                <div class="stat-value">View</div>
                <div class="stat-label">Mortgage Applications</div>
              </div>
            </a>
          </div>
        </div>

        <?php if ($create_error): ?>
          <div class="modern-alert modern-alert-danger mb-4"><?php echo htmlspecialchars($create_error); ?></div>
        <?php endif; ?>

        <div class="modern-card mb-4 fade-in">
          <div class="modern-card-header">➕ Create New User</div>
          <div class="modern-card-body">
            <form method="POST">
              <div class="row g-3">
                <div class="col-md-3">
                  <input type="text" name="new_username" class="form-control modern-input" placeholder="Username" required>
                </div>
                <div class="col-md-3">
                  <input type="password" name="new_password" class="form-control modern-input" placeholder="Password" required>
                </div>
                <div class="col-md-3">
                  <select name="new_role" class="form-select modern-input" required>
                    <option value="client">Client</option>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <button type="submit" class="btn modern-btn modern-btn-primary w-100">Add User</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="modern-card fade-in">
          <div class="modern-card-header">📋 User Directory</div>
          <div class="p-0">
            <div class="modern-table">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Created At</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($users as $u): ?>
                    <tr>
                      <td><strong>#<?php echo $u['user_id']; ?></strong></td>
                      <td><?php echo htmlspecialchars($u['username']); ?></td>
                      <td><span class="modern-badge badge-<?php echo $u['role']; ?>"><?php echo ucfirst($u['role']); ?></span></td>
                      <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                    </tr>
                  <?php endforeach; ?>
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