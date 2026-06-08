<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir - Kreato Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #EFEBE4; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #333333;
            overflow: hidden;
            position: relative;
        }

        /* Elemen Estetik Lingkungan Dekoratif di Latar Belakang */
        body::before, body::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(161, 43, 37, 0.04), rgba(232, 226, 217, 0));
            z-index: -1;
        }
        body::before {
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
        }
        body::after {
            width: 500px;
            height: 500px;
            bottom: -150px;
            right: -150px;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #FDFBF7; 
            border: 1px solid rgba(161, 43, 37, 0.08);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(74, 69, 63, 0.08), 0 1px 3px rgba(0, 0, 0, 0.02);
            padding: 40px 40px;
            width: 100%;
            max-width: 440px;
            backdrop-filter: blur(10px);
        }

        .brand-section {
            text-align: center;
            margin-bottom: 25px;
        }

        /* --- KUSTOMISASI REPLIKA LOGO BINTANG KREATO (MURNI CSS) --- */
        .kreato-star-logo {
            position: relative;
            display: block;
            width: 0px;
            height: 0px;
            margin: 35px auto 45px auto; /* Memberi ruang untuk ujung-ujung bintang */
            border-right: 100px solid transparent;
            border-bottom: 70px solid #A12B25; /* Warna merah marun utama */
            border-left: 100px solid transparent;
            transform: rotate(35deg) scale(0.45); /* Dikecilkan agar pas di card */
        }
        .kreato-star-logo:before {
            border-bottom: 80px solid #A12B25;
            border-left: 30px solid transparent;
            border-right: 30px solid transparent;
            position: absolute;
            height: 0px;
            width: 0px;
            top: -45px;
            left: -65px;
            display: block;
            content: '';
            transform: rotate(-35deg);
        }
        .kreato-star-logo:after {
            position: absolute;
            display: block;
            color: #A12B25;
            top: 3px;
            left: -105px;
            width: 0px;
            height: 0px;
            border-right: 100px solid transparent;
            border-bottom: 70px solid #A12B25;
            border-left: 100px solid transparent;
            transform: rotate(-70deg);
            content: '';
        }

        /* Elemen Wajah (Mata & Senyum Putih) di dalam Bintang */
        .star-face {
            position: absolute;
            z-index: 10;
            top: 35px;
            left: -25px;
            width: 50px;
            height: 30px;
            transform: rotate(-35deg); /* Mengembalikan posisi wajah agar lurus */
        }
        .star-eye {
            position: absolute;
            width: 8px;
            height: 8px;
            background-color: #FDFBF7;
            border-radius: 50%;
            top: 0;
        }
        .star-eye.left { left: 8px; }
        .star-eye.right { right: 8px; }
        
        .star-smile {
            position: absolute;
            width: 20px;
            height: 10px;
            border-bottom: 3px solid #FDFBF7;
            border-radius: 0 0 15px 15px;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
        }
        /* ----------------------------------------------------------- */

        .brand-title {
            font-size: 24px;
            font-weight: 700;
            color: #2D2722;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #8C857B;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #5C544C;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background-color: #F5F1E9; 
            border: 1px solid #E2DCD2;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 14px;
            color: #2D2722;
            transition: all 0.25s ease;
        }

        .form-control::placeholder {
            color: #A6A095;
        }

        .form-control:focus {
            background-color: #FDFBF7;
            border-color: #A12B25; 
            box-shadow: 0 0 0 4px rgba(161, 43, 37, 0.12);
        }

        .btn-kreato {
            background-color: #A12B25; 
            color: #ffffff;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(161, 43, 37, 0.2);
        }

        .btn-kreato:hover {
            background-color: #82201B;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(161, 43, 37, 0.3);
        }

        .btn-kreato:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(161, 43, 37, 0.2);
        }

        .alert-kreato {
            background-color: #FDF3F2;
            border: 1px solid #FAD7D5;
            color: #A12B25;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 500;
            padding: 14px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            
            <div class="brand-section">
                <div class="kreato-star-logo">
                    <div class="star-face">
                        <div class="star-eye left"></div>
                        <div class="star-eye right"></div>
                        <div class="star-smile"></div>
                    </div>
                </div>
                <h1 class="brand-title">Kreato Coffee</h1>
                <p class="brand-subtitle">Point of Sales System</p>
            </div>

            @if($errors->any())
                <div class="alert alert-kreato alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <span class="me-2">⚠️</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                
                <div class="form-group-custom">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="nama@kreatocoffee.com" required value="{{ old('email') }}" autocomplete="off">
                </div>

                <div class="form-group-custom">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-kreato w-100">Login</button>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>