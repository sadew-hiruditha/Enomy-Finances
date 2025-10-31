<?php
require_once __DIR__ . '/config.php';

try {
    // Read and execute SQL file
    $sql = file_get_contents(__DIR__ . '/setup.sql');
    
    // Split by semicolon and execute each statement
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "✅ Database tables created successfully!\n";
    echo "✅ currency_transactions table added\n";
    echo "✅ investment_quotes table added\n";
    echo "\nYou can now use:\n";
    echo "- Currency Converter\n";
    echo "- Investment Calculator\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
