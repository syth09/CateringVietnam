<?php
require_once '../includes/db_connection.php';

$username = 'admin';
$new_password = 'admin123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = :password WHERE username = :username");
$stmt->execute(['password' => $hashed_password, 'username' => $username]);

echo "Mật khẩu admin đã được cập nhật thành công.";
