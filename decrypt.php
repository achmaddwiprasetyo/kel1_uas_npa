<?php
session_start();
require 'config.php';
require 'encrypt.php'; // berisi fungsi encryptFile & decryptFile

// Cek apakah user login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Akses ditolak");
}

// Validasi ID
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$video_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Ambil data video milik user
$stmt = $conn->prepare("SELECT * FROM kyc WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $video_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data tidak ditemukan atau bukan milik Anda");
}

$data = $result->fetch_assoc();

// Dekripsi file
$encrypted_path = $data['file_encrypted'];
$temp_path = 'temp/' . uniqid('kyc_', true) . '.mp4';

decryptFile($encrypted_path, $temp_path, AES_KEY);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lihat Video KYC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Video KYC: <?= htmlspecialchars($data['name']) ?></h5>
            </div>
            <div class="card-body">
                <video controls width="100%" height="auto">
                    <source src="<?= $temp_path ?>" type="video/mp4">
                    Browser tidak mendukung pemutaran video.
                </video>

                <div class="mt-4">
                    <a href="dashboard.php?page=list" class="btn btn-secondary">⬅ Kembali ke List Video</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>