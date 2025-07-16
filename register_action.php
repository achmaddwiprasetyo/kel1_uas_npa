<?php
session_start();
require 'config.php';

$username = trim($_POST['username']);
$password_plain = $_POST['password'];
$telegram_id = trim($_POST['telegram_id']);

// Validasi password kuat
$valid = preg_match('/[A-Z]/', $password_plain) &&  // Huruf besar
    preg_match('/[a-z]/', $password_plain) &&  // Huruf kecil
    preg_match('/[0-9]/', $password_plain) &&  // Angka
    preg_match('/[^A-Za-z0-9]/', $password_plain) && // Simbol
    strlen($password_plain) >= 8;

if (!$valid) {
    echo "<script>alert('Password harus terdiri dari minimal 8 karakter, huruf besar, huruf kecil, angka, dan simbol.'); window.location='register.php';</script>";
    exit();
}

// Hash password setelah validasi
$password = password_hash($password_plain, PASSWORD_DEFAULT);

// Cek apakah username sudah digunakan
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Username sudah terdaftar. Silakan gunakan username lain.'); window.location='register.php';</script>";
    exit();
}
$stmt->close();

// Cek apakah telegram_id sudah digunakan
$stmt = $conn->prepare("SELECT id FROM users WHERE telegram_id = ?");
$stmt->bind_param("s", $telegram_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('ID Telegram sudah terdaftar. Silakan gunakan ID lain.'); window.location='register.php';</script>";
    exit();
}
$stmt->close();

// Insert user baru
$stmt = $conn->prepare("INSERT INTO users (username, password, telegram_id) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $telegram_id);

if ($stmt->execute()) {
    // Kirim pesan Telegram
    $message = "✅ Anda berhasil mendaftar di sistem kami.\nUsername: $username\nSilakan login untuk melanjutkan.";
    file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$telegram_id&text=" . urlencode($message));

    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Registrasi gagal. Silakan coba lagi.'); window.location='register.php';</script>";
}

$stmt->close();
$conn->close();
