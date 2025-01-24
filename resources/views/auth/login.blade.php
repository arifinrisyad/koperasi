<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Koperasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: url('/img/gambar cpl.png');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.8), rgba(0,0,0,0.4));
            z-index: 1;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            margin: auto;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        }

        .card-header {
            background: rgba(25, 135, 84, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .card-header h3 {
            color: #fff;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .card-body {
            padding: 40px;
        }

        .form-floating {
            margin-bottom: 25px;
        }

        .form-control {
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            padding: 12px 25px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #2ecc71;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.2);
        }

        .form-floating label {
            padding-left: 25px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #2ecc71;
            transform: scale(0.85) translateY(-0.75rem) translateX(0.15rem);
        }

        .btn-success {
            height: 55px;
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            border: none;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.2);
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }

        .card-footer {
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 25px;
            text-align: center;
        }

        .card-footer p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }

        .card-footer a {
            color: #2ecc71;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .card-footer a:hover {
            color: #fff;
            text-shadow: 0 0 10px rgba(46, 204, 113, 0.5);
        }

        .invalid-feedback {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 8px;
            display: flex;
            align-items: center;
        }

        .invalid-feedback i {
            margin-right: 6px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container {
            animation: fadeIn 0.6s ease-out;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }
            .card-header h3 {
                font-size: 24px;
            }
            .form-control {
                height: 55px;
            }
            .btn-success {
                height: 50px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-shield me-2"></i>Login</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" placeholder="Email address" 
                            value="{{ old('email') }}" required autofocus>
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email address</label>
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
            </div>
            <div class="card-footer">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)',
                customClass: {
                    popup: 'animated fadeInDown faster'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)',
                customClass: {
                    popup: 'animated fadeInDown faster'
                }
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ $errors->first() }}",
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)',
                customClass: {
                    popup: 'animated fadeInDown faster'
                }
            });
        @endif
    </script>
</body>
</html>

