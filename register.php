<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>Form Registrasi</h4>
                </div>
                <div class="card-body">
                    <p class="alert alert-info">
                        🔔 <strong>PERHATIAN:</strong><br>
                        Sebelum mendaftar, silakan buka <a href="https://t.me/kel1_npa_bot" target="_blank" class="text-decoration-underline">@kel1_npa_bot</a> dan kirim pesan <code>/start</code> ke bot.
                    </p>
                    <p class="alert alert-info">
                        Untuk mengetahui ID Telegram, silakan buka <a href="https://t.me/userinfobot" target="_blank" class="text-decoration-underline">@userinfobot</a> dan kirim pesan <code>/start</code> ke bot.
                    </p>
                    <form method="post" action="register_action.php">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="reg_password" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleRegPassword()">
                                    <i class="bi bi-eye-slash" id="toggleRegIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>ID Telegram</label>
                            <input type="text" name="telegram_id" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Daftar</button>
                        <div class="mt-3 text-center">
                            <a href="index.php">Sudah punya akun? Login di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleRegPassword() {
            const passwordInput = document.getElementById("reg_password");
            const toggleIcon = document.getElementById("toggleRegIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("bi-eye-slash");
                toggleIcon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("bi-eye");
                toggleIcon.classList.add("bi-eye-slash");
            }
        }
    </script>
    <script>
        document.querySelector("form").addEventListener("submit", function(e) {
            const password = document.querySelector("#reg_password").value;

            const minLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSymbol = /[^A-Za-z0-9]/.test(password);

            if (!(minLength && hasUpper && hasLower && hasNumber && hasSymbol)) {
                alert("Password harus mengandung:\n- Minimal 8 karakter\n- Huruf besar (A-Z)\n- Huruf kecil (a-z)\n- Angka (0-9)\n- Simbol (^A-Za-z0-9)");
                e.preventDefault(); // Mencegah form terkirim
            }
        });
    </script>

</body>

</html>