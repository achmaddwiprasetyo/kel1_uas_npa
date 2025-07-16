<?php
session_start();
require 'config.php';

if (!isset($_SESSION['otp_user'])) {
    die("Akses ditolak.");
}

$user_id = $_SESSION['otp_user'];
$otp = $_POST['otp'];

// Verifikasi OTP + waktu valid
$stmt = $conn->prepare("SELECT * FROM users WHERE id=? AND otp_code=? AND otp_expiry > NOW()");
$stmt->bind_param("is", $user_id, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // OTP valid
    $_SESSION['loggedin'] = true;
    $_SESSION['user_id'] = $user_id;

    // Kosongkan OTP agar tidak bisa digunakan ulang
    $clear = $conn->prepare("UPDATE users SET otp_code=NULL, otp_expiry=NULL WHERE id=?");
    $clear->bind_param("i", $user_id);
    $clear->execute();

    unset($_SESSION['otp_user']);
    header("Location: dashboard.php");
    exit();
} else {
    echo "<script>alert('OTP tidak valid atau sudah kedaluwarsa.'); window.location='index.php';</script>";
}
