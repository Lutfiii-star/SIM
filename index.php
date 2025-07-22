<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Mahasiswa</title>
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
            background-color: var(--light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.9) 0%, rgba(103, 189, 207, 0.9) 100%),
                url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRwq2_gqznIIDRkMi5fuGLGdpBYPgT8OzGplQZZEVlxhgoSX7TyUc1EY8p3tn9NSed0VD4&usqp=CAU') center/cover;
            color: white;
            padding: 6rem 0;
            text-align: center;
        }

        .login-btn {
            background-color: white;
            color: var(--primary);
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid white;
        }

        .login-btn:hover {
            background-color: black;
            color: white;
            transform: translateY(-3px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .feature-card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease;
            height: 100%;
            padding: 2rem 1.5rem;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        footer {
            background-color: var(--dark);
            color: white;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Sistem Informasi Mahasiswa</h1>
            <p class="lead mb-4">Manajemen data mahasiswa yang efisien dan terintegrasi</p>
            <a href="login.php" class="btn login-btn">
                <i class="fas fa-sign-in-alt me-2"></i> login
            </a>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h4>Data Mahasiswa</h4>
                        <p>Kelola data mahasiswa secara lengkap dan terpusat</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h4>Akademik</h4>
                        <p>Pantau perkembangan akademik mahasiswa</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h4>Laporan</h4>
                        <p>Generate laporan dengan mudah</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 SIM Mahasiswa. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi sederhana saat hover tombol
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.05)';
            });
        });
    </script>
</body>

</html>