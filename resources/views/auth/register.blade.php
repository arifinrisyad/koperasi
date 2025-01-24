<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
    background-image: url('/img/gambar cpl.png');
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #fff;
    position: relative;
    z-index: 1;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Warna hitam dengan transparansi */
    z-index: -1; /* Pastikan overlay berada di bawah konten */
}

        @keyframes zoomIn {
            0% {
                transform: scale(0.8) rotate(5deg);
                opacity: 0;
            }

            50% {
                transform: scale(1.05) rotate(-2deg);
                opacity: 0.5;
            }

            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            animation: zoomIn 0.5s ease-out forwards;
        }

        .register-box {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #fff;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .input-container input {
            width: 100%;
            padding: 12px 40px;
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .input-container input:focus {
            outline: none;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        button:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .back-to-login {
            display: block;
            text-align: center;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .back-to-login:hover {
            color: #007bff;
        }

        .error-messages {
            background: rgba(255, 0, 0, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }

        .error-messages ul {
            list-style: none;
            padding: 0;
        }

        .error-messages li {
            color: #ff4444;
            font-size: 14px;
            margin-bottom: 5px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }

            .register-box {
                padding: 20px;
            }

            .register-box h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="register-box">
            <h2>Register</h2>
            <div class="register-form">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-container">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="name" placeholder="Enter your name" required>
                    </div>

                    <div class="input-container">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="input-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>

                    <div class="input-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" placeholder="Confirm your password"
                            required>
                    </div>

                    <button type="submit">Register</button>
                    <a href="/login" class="back-to-login">Back to Login</a>
                </form>

                @if ($errors->any())
                    <div class="error-messages">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>

