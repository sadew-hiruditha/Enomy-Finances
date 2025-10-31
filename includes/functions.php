<?php
/**
 * Common functions used across the Enomy‑Finances prototype
 */

require_once __DIR__ . '/config.php';

/**
 * Check if a user is logged in
 */
function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Enforce authentication
 */
function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: index.php');
        exit();
    }
}

/**
 * Retrieve the current logged in user
 */
function current_user(): ?array
{
    global $pdo;
    if (!is_logged_in()) {
        return null;
    }
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Attempt to log in a user
 */
function login(string $username, string $password): bool
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role']    = $user['role'];
        return true;
    }
    return false;
}

/**
 * Create a new user and associated client profile
 */
function register_client(array $data): bool
{
    global $pdo;
    // Hash password
    $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
    try {
        $pdo->beginTransaction();
        // Insert into users table
        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
        $stmt->execute([$data['username'], $password_hash, 'client']);
        $user_id = $pdo->lastInsertId();
        // Insert into client_profiles
        $stmt = $pdo->prepare('INSERT INTO client_profiles (user_id, full_name, address, phone, email, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $user_id,
            $data['full_name'],
            $data['address'],
            $data['phone'],
            $data['email'],
            $data['date_of_birth'],
        ]);
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
    }
}

/**
 * Get mortgages for a client
 */
function get_client_mortgages(int $client_id): array
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM mortgages WHERE client_id = ?');
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();
}

/**
 * Get investments for a client
 */
function get_client_investments(int $client_id): array
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM investments WHERE client_id = ?');
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();
}

/**
 * Create a new mortgage application for a client
 */
function create_mortgage(int $client_id, array $data): bool
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO mortgages (client_id, property_address, principal_amount, interest_rate, term_years, status) VALUES (?, ?, ?, ?, ?, ?)');
    return $stmt->execute([
        $client_id,
        $data['property_address'],
        $data['principal_amount'],
        $data['interest_rate'],
        $data['term_years'],
        'submitted'
    ]);
}

/**
 * Get pending mortgage applications for staff
 */
function get_pending_mortgages(): array
{
    global $pdo;
    $stmt = $pdo->query("SELECT m.*, c.full_name FROM mortgages m JOIN client_profiles c ON m.client_id = c.client_id WHERE m.status IN ('submitted','under_review') ORDER BY m.created_at ASC");
    return $stmt->fetchAll();
}

/**
 * Update mortgage status (for staff)
 */
function update_mortgage_status(int $mortgage_id, string $status): bool
{
    global $pdo;
    $stmt = $pdo->prepare('UPDATE mortgages SET status = ? WHERE mortgage_id = ?');
    return $stmt->execute([$status, $mortgage_id]);
}

/**
 * Get client profile by user ID
 */
function get_client_profile(int $user_id): ?array
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM client_profiles WHERE user_id = ?');
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

/**
 * Create a new investment for a client
 */
function create_investment(int $client_id, array $data): bool
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO investments (client_id, product_type, amount, interest_rate, maturity_date) VALUES (?, ?, ?, ?, ?)');
    return $stmt->execute([
        $client_id,
        $data['product_type'],
        $data['amount'],
        $data['interest_rate'],
        $data['maturity_date']
    ]);
}

/**
 * Get all investments (for admin/staff)
 */
function get_all_investments(): array
{
    global $pdo;
    $stmt = $pdo->query("SELECT i.*, c.full_name FROM investments i JOIN client_profiles c ON i.client_id = c.client_id ORDER BY i.created_at DESC");
    return $stmt->fetchAll();
}

/**
 * Delete an investment
 */
function delete_investment(int $investment_id): bool
{
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM investments WHERE investment_id = ?');
    return $stmt->execute([$investment_id]);
}

/**
 * Save currency transaction
 */
function save_currency_transaction(int $client_id, array $data): bool
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO currency_transactions (client_id, from_currency, to_currency, amount, converted_amount, fee_percent, fee_amount, final_amount, exchange_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    return $stmt->execute([
        $client_id,
        $data['from_currency'],
        $data['to_currency'],
        $data['amount'],
        $data['converted_amount'],
        $data['fee_percent'],
        $data['fee_amount'],
        $data['final_amount'],
        $data['exchange_rate']
    ]);
}

/**
 * Get currency transactions for a client
 */
function get_currency_transactions(int $client_id): array
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM currency_transactions WHERE client_id = ? ORDER BY created_at DESC');
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();
}

/**
 * Save investment quote
 */
function save_investment_quote(int $client_id, array $data): bool
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO investment_quotes (client_id, investment_type, initial_lump_sum, monthly_investment, quote_data) VALUES (?, ?, ?, ?, ?)');
    return $stmt->execute([
        $client_id,
        $data['investment_type'],
        $data['initial_lump_sum'],
        $data['monthly_investment'],
        json_encode($data['quote'])
    ]);
}

/**
 * Get investment quotes for a client
 */
function get_investment_quotes(int $client_id): array
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM investment_quotes WHERE client_id = ? ORDER BY created_at DESC');
    $stmt->execute([$client_id]);
    $quotes = $stmt->fetchAll();
    
    // Decode JSON data
    foreach ($quotes as &$quote) {
        $quote['quote_data'] = json_decode($quote['quote_data'], true);
    }
    
    return $quotes;
}