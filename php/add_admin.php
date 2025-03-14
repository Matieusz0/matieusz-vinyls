<?php
require 'php/db.php';

$username = "admin"; // Nazwa użytkownika
$password = "admin"; // Hasło admina (zmień na własne)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashowanie hasła

// Dodanie użytkownika do bazy
$stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)");
$stmt->execute([$username, $hashedPassword, 1]);

echo "Administrator został dodany!";
?>