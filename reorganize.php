<?php
/**
 * File Reorganization Script
 * 
 * This script reorganizes the project files into a proper folder structure.
 * It creates necessary directories and moves files to their appropriate locations.
 * 
 * WARNING: This will modify your file structure. Backup your project before running!
 * 
 * Usage: php reorganize.php
 */

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║   Enomy-Finances Project Reorganization Script          ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

// Configuration
$root = __DIR__;
$dryRun = false; // Set to true to preview changes without making them

// Files to move
$fileMap = [
    // Core includes
    'config.php' => 'includes/config.php',
    'functions.php' => 'includes/functions.php',
    
    // Database files
    'setup.sql' => 'database/setup.sql',
    'run_setup.php' => 'database/run_setup.php',
    'insert_admin_staff.sql' => 'database/insert_admin_staff.sql',
    'generate_hash.php' => 'database/generate_hash.php',
    
    // Client pages
    'dashboard_client.php' => 'public/client/dashboard.php',
    'add_mortgage.php' => 'public/client/add_mortgage.php',
    'add_investment.php' => 'public/client/add_investment.php',
    'currency_converter.php' => 'public/client/currency_converter.php',
    'investment_calculator.php' => 'public/client/investment_calculator.php',
    
    // Staff pages
    'dashboard_staff.php' => 'public/staff/dashboard.php',
    'manage_investments.php' => 'public/staff/manage_investments.php',
    
    // Admin pages
    'dashboard_admin.php' => 'public/admin/dashboard.php',
    'add_admin_staff.php' => 'public/admin/add_admin_staff.php',
    
    // Documentation
    'SYSTEM_DOCUMENTATION.md' => 'docs/SYSTEM_DOCUMENTATION.md',
    'REQUIREMENTS_IMPLEMENTATION.md' => 'docs/REQUIREMENTS_IMPLEMENTATION.md',
    'INVESTMENTS_INTEGRATION.md' => 'docs/INVESTMENTS_INTEGRATION.md',
    'MODERN_UI_README.md' => 'docs/MODERN_UI_README.md',
    'FOLDER_STRUCTURE.md' => 'docs/FOLDER_STRUCTURE.md',
    'preview.html' => 'docs/preview.html',
];

// Path updates needed after reorganization
$pathUpdates = [
    'public/client/*.php' => [
        "require_once __DIR__ . '/functions.php';" => "require_once __DIR__ . '/../../includes/functions.php';",
        "require_once __DIR__ . '/config.php';" => "require_once __DIR__ . '/../../includes/config.php';",
        "require_once 'functions.php';" => "require_once __DIR__ . '/../../includes/functions.php';",
        "href=\"assets/" => "href=\"../../assets/",
        "src=\"assets/" => "src=\"../../assets/",
    ],
    'public/staff/*.php' => [
        "require_once __DIR__ . '/functions.php';" => "require_once __DIR__ . '/../../includes/functions.php';",
        "require_once __DIR__ . '/config.php';" => "require_once __DIR__ . '/../../includes/config.php';",
        "require_once 'functions.php';" => "require_once __DIR__ . '/../../includes/functions.php';",
        "href=\"assets/" => "href=\"../../assets/",
        "src=\"assets/" => "src=\"../../assets/",
    ],
    'public/admin/*.php' => [
        "require_once __DIR__ . '/functions.php';" => "require_once __DIR__ . '/../../includes/functions.php';",
        "require_once __DIR__ . '/config.php';" => "require_once __DIR__ . '/../../includes/config.php';",
        "require_once 'functions.php';" => "require_once __DIR__ . '/../../includes/functions.php';",
        "href=\"assets/" => "href=\"../../assets/",
        "src=\"assets/" => "src=\"../../assets/",
    ],
    'database/*.php' => [
        "require_once __DIR__ . '/config.php';" => "require_once __DIR__ . '/../includes/config.php';",
        "require_once 'config.php';" => "require_once __DIR__ . '/../includes/config.php';",
    ],
];

/**
 * Move a file from source to destination
 */
function moveFile($source, $dest, $dryRun = false) {
    $sourcePath = $GLOBALS['root'] . '/' . $source;
    $destPath = $GLOBALS['root'] . '/' . $dest;
    
    // Check if source exists
    if (!file_exists($sourcePath)) {
        echo "⚠️  SKIP: $source (not found)\n";
        return false;
    }
    
    // Check if destination already exists
    if (file_exists($destPath)) {
        echo "⚠️  SKIP: $source → $dest (destination exists)\n";
        return false;
    }
    
    // Create destination directory if needed
    $destDir = dirname($destPath);
    if (!is_dir($destDir)) {
        if ($dryRun) {
            echo "📁 CREATE DIR: $destDir (dry run)\n";
        } else {
            mkdir($destDir, 0755, true);
            echo "📁 CREATED DIR: $destDir\n";
        }
    }
    
    // Move the file
    if ($dryRun) {
        echo "📄 MOVE: $source → $dest (dry run)\n";
    } else {
        if (rename($sourcePath, $destPath)) {
            echo "✅ MOVED: $source → $dest\n";
            return true;
        } else {
            echo "❌ FAILED: $source → $dest\n";
            return false;
        }
    }
    
    return true;
}

/**
 * Update file paths in PHP files
 */
function updateFilePaths($pattern, $replacements, $dryRun = false) {
    $files = glob($GLOBALS['root'] . '/' . $pattern);
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $modified = false;
        
        foreach ($replacements as $search => $replace) {
            if (strpos($content, $search) !== false) {
                $content = str_replace($search, $replace, $content);
                $modified = true;
            }
        }
        
        if ($modified) {
            if ($dryRun) {
                echo "📝 UPDATE: " . basename($file) . " (dry run)\n";
            } else {
                file_put_contents($file, $content);
                echo "✅ UPDATED: " . basename($file) . "\n";
            }
        }
    }
}

/**
 * Create a backup of the current state
 */
function createBackup() {
    $backupDir = $GLOBALS['root'] . '/backup_' . date('Y-m-d_H-i-s');
    
    echo "\n📦 Creating backup...\n";
    
    // Create backup directory
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    // Copy all PHP files
    $files = array_merge(
        glob($GLOBALS['root'] . '/*.php'),
        glob($GLOBALS['root'] . '/*.sql'),
        glob($GLOBALS['root'] . '/*.md')
    );
    
    foreach ($files as $file) {
        $basename = basename($file);
        copy($file, $backupDir . '/' . $basename);
    }
    
    echo "✅ Backup created: $backupDir\n\n";
    return $backupDir;
}

// Main execution
echo "This script will reorganize your project files.\n";
echo "Mode: " . ($dryRun ? "DRY RUN (no changes will be made)" : "LIVE (files will be moved)") . "\n\n";

if (!$dryRun) {
    echo "⚠️  WARNING: This will modify your file structure!\n";
    echo "Press ENTER to continue or CTRL+C to cancel: ";
    fgets(STDIN);
    
    // Create backup
    $backupDir = createBackup();
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Step 1: Moving Files\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$movedCount = 0;
$skippedCount = 0;

foreach ($fileMap as $source => $dest) {
    if (moveFile($source, $dest, $dryRun)) {
        $movedCount++;
    } else {
        $skippedCount++;
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Step 2: Updating File Paths\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

foreach ($pathUpdates as $pattern => $replacements) {
    echo "Updating: $pattern\n";
    updateFilePaths($pattern, $replacements, $dryRun);
    echo "\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Summary\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Files moved: $movedCount\n";
echo "Files skipped: $skippedCount\n";

if (!$dryRun) {
    echo "\n✅ Reorganization complete!\n";
    echo "\n📋 Next Steps:\n";
    echo "1. Update your index.php paths if needed\n";
    echo "2. Update register.php paths if needed\n";
    echo "3. Update logout.php paths if needed\n";
    echo "4. Test all pages to ensure they work correctly\n";
    echo "5. If everything works, you can delete the backup folder\n";
    echo "\nBackup location: $backupDir\n";
} else {
    echo "\n💡 This was a dry run. No files were modified.\n";
    echo "Set \$dryRun = false in the script to perform the actual reorganization.\n";
}

echo "\n╔══════════════════════════════════════════════════════════╗\n";
echo "║                  Script Complete                         ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
