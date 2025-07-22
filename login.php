<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $q = mysqli_query($conn, "SELECT * FROM tbl_user WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($q);
    $_SESSION['login_success'] = 'Selamat datang, ' . $username . '!';
    if ($data) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
    } else {
        $error = "Login Gagal! Periksa Username dan Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Informasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
    <style>
        :root {
            --primary: #4361ee;
            --dark: #212529;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-wrapper {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, #3a0ca3 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .login-body {
            padding: 2rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .btn-login {
            background-color: var(--primary);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #3a0ca3;
            transform: translateY(-2px);
        }

        .input-group-text {
            background-color: transparent;
            border-right: none;
        }

        .input-with-icon {
            border-left: none;
        }

        .password-toggle {
            cursor: pointer;
            background-color: transparent;
            border-left: none;
            color: #6c757d;
        }

        .error-message {
            animation: fadeIn 0.3s ease;
        }

        .back-btn {
            display: inline-block;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
            width: 100%;
        }

        .back-btn:hover {
            color: #3a0ca3;
            transform: translateX(-5px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-wrapper">
            <div class="login-container">
                <div class="login-header">
                    <h4><i class="fas fa-user-graduate me-2"></i> Sistem Informasi Mahasiswa</h4>
                    <p class="mb-0">Silakan masuk dengan akun Anda</p>
                </div>

                <div class="login-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger error-message">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control input-with-icon" placeholder="Masukkan username" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control input-with-icon" placeholder="Masukkan password" required>
                                <span class="input-group-text password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" name="login" class="btn btn-login btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </form>
                </div>
            </div>

            <a href="index.php" class="back-btn">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Focus pada input username saat halaman dimuat
        document.querySelector('input[name="username"]').focus();

        // Animasi untuk error message
        if (document.querySelector('.error-message')) {
            setTimeout(() => {
                document.querySelector('.error-message').style.opacity = '1';
            }, 100);
        }

        // Toggle show/hide password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Ganti ikon eye
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>
</body>

</html>