<?php
session_start();
require 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$page = isset($_GET['page']) ? $_GET['page'] : 'upload';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard KYC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .table thead {
            background-color: #f0f0f0;
        }

        .card {
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Dashboard UAS Network Programming & Administration</a>
            <div class="ms-auto">
                <span class="text-white me-3">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($user['username']) ?>
                </span>
                <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Menu Tabs -->
        <ul class="nav nav-pills mb-4 justify-content-center">
            <li class="nav-item">
                <a class="nav-link <?= $page === 'upload' ? 'active' : '' ?>" href="dashboard.php?page=upload">📤 Upload Video</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page === 'list' ? 'active' : '' ?>" href="dashboard.php?page=list">📁 List Video</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $page === 'about' ? 'active' : '' ?>" href="dashboard.php?page=about">👥 About</a>
            </li>
        </ul>

        <?php if ($page === 'upload'): ?>
            <!-- Form Upload -->
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Form Upload KYC</h5>
                <form action="upload.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Upload Video KYC (MP4)</label>
                            <input type="file" name="video" class="form-control" accept="video/mp4" required>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-2" type="submit">Upload & Enkripsi</button>
                </form>
            </div>

        <?php elseif ($page === 'list'): ?>
            <!-- List Video -->
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Daftar Video KYC Anda</h5>
                <?php
                $result = $conn->query("SELECT * FROM kyc WHERE user_id = $user_id ORDER BY created_at DESC");
                ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Email</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= $row['nik'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                    <td>
                                        <a href="decrypt.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-info">🔓 Lihat</a>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($page === 'about'): ?>
            <!-- Tentang Kelompok -->
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Tentang Kelompok 1</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">1. ACHMAD DWI PRASETYO (Ketua)</li>
                    <li class="list-group-item">2. GILANG IHZA PERMANA</li>
                    <li class="list-group-item">3. FITRAN IMAN RAMADHAN</li>
                    <li class="list-group-item">4. FIRSTA ROYAN DALISKA</li>
                    <li class="list-group-item">5. ALDI AZHARI</li>
                    <li class="list-group-item">6. AGTONMY WIDAYAKA</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>