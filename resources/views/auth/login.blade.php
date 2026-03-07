<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Manajemen Surat Digital Bagian Hukum</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #fff;
        }

        .login-wrapper {
            min-height: 100vh;
        }

        /* === BAGIAN KIRI (GAMBAR) === */
        .bg-image-side {
            /* Gambar Background Anda */
            background: url("{{ asset('bg.jpg') }}") no-repeat center center;
            background-size: cover;
            position: relative;
        }

        /* Overlay Biru Transparan di atas gambar agar teks terbaca */
        .bg-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.9) 0%, rgba(42, 82, 152, 0.8) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            color: white;
        }

        /* === BAGIAN KANAN (FORM) === */
        .login-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: #ffffff;
        }

        .login-content {
            width: 100%;
            max-width: 400px;
        }

        .logo-login {
            height: 70px;
            width: auto;
            margin-bottom: 2rem;
            object-fit: contain;
        }

        /* Styling Input Form yang Bersih */
        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            background-color: #fff;
            border-color: #1e3c72;
            box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background-color: #1e3c72;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #e2e6ea;
            color: #0d6efd;
        }

        .captcha-wrapper {
            display: flex;
            align-items: stretch;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
        }

        .captcha-img-box {
            flex-grow: 1;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-refresh {
            width: 50px;
            border: none;
            border-left: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: #1e3c72;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-refresh:active {
        background-color: #dbeafe;
        }
    </style>
</head>
<body>

    <div class="container-fluid login-wrapper">
        <div class="row h-100">
            
            <div class="col-lg-7 col-md-6 d-none d-md-block p-0 bg-image-side">
                <div class="bg-overlay">
                    <h1 class="fw-bold display-5 mb-3">Selamat Datang</h1>
                    <p class="lead mb-4 opacity-75">Sistem Informasi Manajemen Persuratan<br>Bagian Hukum Setda Kab. Malang</p>
                    
                    <div class="d-flex align-items-center mt-auto">
                        <small class="opacity-50">&copy; {{ date('Y') }} Bagian Hukum Setda Kab. Malang</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-6 login-side">
                <div class="login-content">
                    
                    <div class="text-start">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg/960px-Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg.png" alt="Logo" class="logo-login">
                        <h3 class="fw-bold text-dark mb-1">Masuk Akun</h3>
                        <p class="text-muted small mb-4">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email yang sudah didaftarkan" value="{{ old('email') }}" required autofocus>
                            @error('email') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label">Password</label>
                            </div>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Silahkan Masukkan Password" required>
                            @error('password') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Kode Keamanan</label>
        
                                <div class="captcha-wrapper mb-2">
                                    <div class="captcha-img-box">
                                        <span class="captcha-img">{!! captcha_img('flat') !!}</span>
                                    </div>
            
                                    <button type="button" class="btn-refresh" onclick="refreshCaptcha()" title="Ganti Gambar">
                                        <i class="bi bi-arrow-clockwise fs-5"></i>
                                    </button>
                                </div>

                                <input type="text" name="captcha" class="form-control" placeholder="Ketik karakter yang muncul di atas" autocomplete="off" required>
        
                                @error('captcha') 
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span> 
                                @enderror
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg fs-6">
                                MASUK SEKARANG
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function refreshCaptcha(){
            $.ajax({ type: 'GET', url: '{{ url("/captcha/api/flat") }}', success: function(data){ $(".captcha-img").html(data.img); } });
        }
    </script>
</body>
</html>