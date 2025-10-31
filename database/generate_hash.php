<?php
/**
 * Simple password hash generator
 * Usage: php generate_hash.php
 */

if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

echo "Password Hash Generator\n";
echo "=======================\n\n";

echo "Enter password to hash: ";
$password = trim(fgets(STDIN));

if (empty($password)) {
    die("Password cannot be empty.\n");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

echo "\nPassword: " . $password . "\n";
echo "Hash: " . $hash . "\n\n";
echo "You can now use this hash in your SQL INSERT statement.\n";
