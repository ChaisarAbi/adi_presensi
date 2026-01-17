<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Presensi Siswa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 40px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            color: white;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 2px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 2px;
        }
        .badge-admin { background: #dc3545; color: white; }
        .badge-guru { background: #198754; color: white; }
        .badge-ortu { background: #0d6efd; color: white; }
        @media (max-width: 768px) {
            .login-body {
                padding: 25px;
            }
            body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <h1 class="h3 mb-2">SISTEM Presensi SISWA</h1>
                        <p class="mb-0">Login berdasarkan peran Anda</p>
                        <div class="mt-3">
                            <span class="role-badge badge-admin">Admin</span>
                            <span class="role-badge badge-guru">Guru</span>
                            <span class="role-badge badge-ortu">Orang Tua</span>
                        </div>
                    </div>
                    <div class="login-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email') }}" required autofocus 
                                       placeholder="admin@presensi.sch.id">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       required placeholder="password123">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            <button type="submit" class="btn btn-login mb-3">
                                <i class="bi bi-box-arrow-in-right"></i> LOGIN
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-2">Akun Demo:</p>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card border-primary">
                                        <div class="card-body p-2">
                                            <small class="text-primary">Admin</small><br>
                                            <strong>admin@presensi.sch.id</strong><br>
                                            <small>password123</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card border-success">
                                        <div class="card-body p-2">
                                            <small class="text-success">Guru</small><br>
                                            <strong>guru@presensi.sch.id</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card border-info">
                                        <div class="card-body p-2">
                                            <small class="text-info">Orang Tua</small><br>
                                            <strong>ortu@presensi.sch.id</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted small">
                                Sistem Presensi Siswa &copy; {{ date('Y') }}<br>
                                <span class="text-primary">Mobile-Friendly Design</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alert after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }, 5000);
            });
        });
    </script>
</body>
</html>
