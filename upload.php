<?php
session_start();
require 'config.php';
require 'encrypt.php'; // pastikan file ini ada dan isinya benar

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validasi input
    if (
        isset($_POST['name'], $_POST['nik'], $_POST['email']) &&
        isset($_FILES['video']) &&
        $_FILES['video']['error'] === 0
    ) {
        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'];
        $nik = $_POST['nik'];
        $email = $_POST['email'];
        $tmp = $_FILES['video']['tmp_name'];

        $newName = 'uploads/' . uniqid() . '.enc';

        // Pastikan fungsi encryptFile ada di encrypt.php
        encryptFile($tmp, $newName, AES_KEY);

        $stmt = $conn->prepare("INSERT INTO kyc (user_id, name, nik, email, file_encrypted) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $name, $nik, $email, $newName);
        $stmt->execute();

        echo "<script>
            alert('Upload & enkripsi berhasil');
            window.location = 'dashboard.php?page=list';
        </script>";
    } else {
        echo "Form tidak lengkap atau video error.";
    }
} else {
    die("Akses tidak sah.");
}
