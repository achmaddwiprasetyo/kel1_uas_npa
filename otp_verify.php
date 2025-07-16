<?php
session_start();
require 'config.php';

// Batasi hanya yang sudah login username/password
if (!isset($_SESSION['otp_user'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .timer {
            font-weight: bold;
            font-size: 1.5rem;
            color: #dc3545;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4>Verifikasi OTP</h4>
                </div>
                <div class="card-body">
                    <p class="alert alert-info">
                        🔐 Masukkan kode OTP yang telah dikirim ke akun Telegram Anda melalui bot.<br>
                        Kode OTP akan kedaluwarsa dalam: <span class="timer" id="countdown">05:00</span>
                    </p>
                    <form method="post" action="otp_check.php">
                        <div class="mb-3">
                            <label for="otp" class="form-label">Kode OTP</label>
                            <input type="text" id="otp" name="otp" class="form-control" maxlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Verifikasi</button>
                    </form>
                    <div class="mt-3 text-center text-muted">
                        OTP tidak berlaku setelah waktu habis.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set timer 5 menit
        let duration = 300; // 300 detik = 5 menit
        let display = document.getElementById("countdown");

        const timer = setInterval(function() {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--duration < 0) {
                clearInterval(timer);
                display.textContent = "Waktu habis!";
                alert("Waktu OTP habis. Silakan login kembali untuk mendapatkan OTP baru.");
                window.location.href = "index.php";
            }
        }, 1000);
    </script>

</body>

</html>