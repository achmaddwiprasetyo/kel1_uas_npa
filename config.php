<?php
$host = "localhost";
$db = "kel1_uas_npa";
$user = "root";
$pass = "admin@2025";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$bot_token = "7922451808:AAEdh_ZxbFgiRjCHgrre741FNjjGVslZygc";

define('AES_KEY', 'aB1cD2eF3gH4iJ5kL6mN7oP8qR9sT0uv'); //AES-256-CBC
