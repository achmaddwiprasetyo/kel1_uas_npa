<?php
session_start();
require 'config.php';

date_default_timezone_set('Asia/Jakarta');

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes")); // expire 5 menit

    $update = $conn->prepare("UPDATE users SET otp_code=?, otp_expiry=? WHERE id=?");
    $update->bind_param("ssi", $otp, $expiry, $user['id']);
    $update->execute();

    // Kirim OTP ke Telegram
    $chat_id = $user['telegram_id'];
    $message = "Kode OTP login Anda adalah: $otp\nKode ini berlaku selama 5 menit.";
    file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message));

    $_SESSION['otp_user'] = $user['id'];
    header("Location: otp_verify.php");
} else {
    header("Location: index.php?error=1");
    exit;
}
