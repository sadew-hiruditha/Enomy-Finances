<?php
/**
 * Script to create admin and staff users
 * For security, this file should be deleted after creating the initial admin/staff accounts
 */

require_once __DIR__ . '/../../includes/config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? 'staff';
    $full_name = trim($_POST['full_name'] ?? '');
    
    // Validate inputs
    if (empty($username) || empty($password) || empty($full_name)) {
        $error = 'All fields are required.';
    } elseif (!in_array($role, ['admin', 'staff'])) {
        $error = 'Invalid role selected.';
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare('SELECT user_id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username already exists.';
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                // Insert into users table
                $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
                $stmt->execute([$username, $password_hash, $role]);
                
                $message = ucfirst($role) . ' account created successfully! Username: ' . htmlspecialchars($username);
            } catch (PDOException $e) {
                $error = 'Failed to create account: ' . $e->getMessage();
            }
        }
    }
}

// Get existing admin and staff users
$stmt = $pdo->query("SELECT user_id, username, role, created_at FROM users WHERE role IN ('admin', 'staff') ORDER BY role, username");
$existing_users = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Admin/Staff - Enomy‑Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="dashboard-container">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="modern-alert modern-alert-warning mb-4 fade-in" role="alert">
              <strong>⚠️ Security Notice:</strong> Delete this file (add_admin_staff.php) after creating your admin and staff accounts!
            </div>
            
            <div class="modern-card fade-in mb-4">
              <div class="modern-card-header">🔐 Create Admin or Staff Account</div>
              <div class="modern-card-body">
                <?php if ($error): ?>
                  <div class="modern-alert modern-alert-danger mb-3" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                  </div>
                <?php endif; ?>
                <?php if ($message): ?>
                  <div class="modern-alert modern-alert-success mb-3" role="alert">
                    <?php echo $message; ?>
                  </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <label for="role" class="form-label fw-semibold">Role *</label>
                      <select class="form-select modern-input" id="role" name="role" required>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label for="username" class="form-label fw-semibold">Username *</label>
                      <input type="text" class="form-control modern-input" id="username" name="username" placeholder="Enter username" required>
                    </div>
                    <div class="col-md-3">
                      <label for="password" class="form-label fw-semibold">Password *</label>
                      <input type="password" class="form-control modern-input" id="password" name="password" placeholder="Enter password" required>
                    </div>
                    <div class="col-md-3">
                      <label for="full_name" class="form-label fw-semibold">Full Name *</label>
                      <input type="text" class="form-control modern-input" id="full_name" name="full_name" placeholder="Enter full name" required>
                    </div>
                  </div>
                  <div class="d-flex gap-2">
                    <button type="submit" class="btn modern-btn modern-btn-primary">Create Account</button>
                    <a href="../../index.php" class="btn btn-outline-secondary">Back to Login</a>
                  </div>
                </form>
              </div>
            </div>

            <div class="modern-card fade-in">
              <div class="modern-card-header">📋 Existing Admin & Staff Accounts</div>
              <div class="p-0">
                <?php if (count($existing_users) > 0): ?>
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
                        <?php foreach ($existing_users as $user): ?>
                          <tr>
                            <td><strong>#<?php echo htmlspecialchars($user['user_id']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td>
                              <span class="modern-badge badge-<?php echo $user['role']; ?>">
                                <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                              </span>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($user['created_at'])); ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php else: ?>
                  <div class="modern-card-body text-center py-5">
                    <p class="text-muted mb-0">No admin or staff accounts exist yet.</p>
                  </div>
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
