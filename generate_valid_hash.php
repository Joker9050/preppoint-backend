<?php

// Generate a valid bcrypt hash for admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

echo "Password: $password\n";
echo "Generated bcrypt hash: $hash\n";
echo "\nSQL to update database:\n";
echo "UPDATE admins SET password = '$hash' WHERE email = 'gargumesh463@gmail.com';\n";

// Verify the hash works
$verify = password_verify($password, $hash);
echo "\nVerification test: " . ($verify ? 'PASSED' : 'FAILED') . "\n";
