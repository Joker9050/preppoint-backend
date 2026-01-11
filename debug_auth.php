<?php

// Debug authentication issue
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "=== DEBUGGING AUTHENTICATION ===\n\n";

// Check what's in the database
$admin = DB::table('admins')->where('email', 'gargumesh463@gmail.com')->first();

if (!$admin) {
    echo "❌ No admin found with email gargumesh463@gmail.com\n";
    exit;
}

echo "Admin found:\n";
echo "- ID: {$admin->id}\n";
echo "- Email: {$admin->email}\n";
echo "- Password hash: {$admin->password}\n";
echo "- Password hash length: " . strlen($admin->password) . "\n";

// Check if it starts with bcrypt identifier
if (strpos($admin->password, '$2y$') === 0) {
    echo "✅ Password appears to be bcrypt hashed\n";
} else {
    echo "❌ Password is NOT bcrypt hashed\n";
}

// Test direct password verification
$testPassword = 'admin123';
try {
    $isValid = password_verify($testPassword, $admin->password);
    echo "🔍 Direct password_verify('$testPassword', hash): " . ($isValid ? 'TRUE' : 'FALSE') . "\n";
} catch (Exception $e) {
    echo "❌ Error in password_verify: " . $e->getMessage() . "\n";
}

// Test Laravel's Hash::check
try {
    $isValid = \Illuminate\Support\Facades\Hash::check($testPassword, $admin->password);
    echo "🔍 Laravel Hash::check('$testPassword', hash): " . ($isValid ? 'TRUE' : 'FALSE') . "\n";
} catch (Exception $e) {
    echo "❌ Error in Hash::check: " . $e->getMessage() . "\n";
}

// Test authentication attempt
echo "\n=== TESTING AUTH ATTEMPT ===\n";
try {
    $credentials = [
        'email' => 'gargumesh463@gmail.com',
        'password' => $testPassword
    ];

    $attempt = Auth::guard('admin')->attempt($credentials, false);
    echo "🔍 Auth::guard('admin')->attempt(): " . ($attempt ? 'SUCCESS' : 'FAILED') . "\n";

    if ($attempt) {
        $user = Auth::guard('admin')->user();
        echo "✅ Authentication successful for user: {$user->email}\n";
    } else {
        echo "❌ Authentication failed\n";
    }
} catch (Exception $e) {
    echo "❌ Error during auth attempt: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== RECOMMENDED FIX ===\n";
if (strpos($admin->password, '$2y$') !== 0) {
    $newHash = password_hash($testPassword, PASSWORD_BCRYPT);
    echo "Run this SQL to fix the password:\n";
    echo "UPDATE admins SET password = '$newHash' WHERE email = 'gargumesh463@gmail.com';\n";
    echo "\nNew hash: $newHash\n";
}
