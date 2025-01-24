@extends('layouts.admin')

@section('content')
    <style>
        /* Global Styling */
        body {
            background-color: #f0f4f8; /* Soft background color for the page */
            font-family: 'Arial', sans-serif;
            color: #2d3436;
        }

        h1 {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1 i {
            margin-right: 10px;
        }

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #f4f4f4; /* Subtle off-white background color */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); /* Softer shadow */
            margin-bottom: 30px;
            position: relative;
            animation: ledPulse 2s infinite alternate, colorChange 3s ease-in-out infinite;
        }

        /* LED Light Pulsing Effect */
        @keyframes ledPulse {
            0% {
                box-shadow: 0 0 15px rgba(255, 0, 0, 0.5), 0 0 25px rgba(0, 255, 0, 0.5), 0 0 35px rgba(0, 0, 255, 0.5);
            }
            50% {
                box-shadow: 0 0 30px rgba(255, 0, 0, 1), 0 0 45px rgba(0, 255, 0, 1), 0 0 60px rgba(0, 0, 255, 1);
            }
            100% {
                box-shadow: 0 0 20px rgba(255, 0, 0, 0.7), 0 0 35px rgba(0, 255, 0, 0.7), 0 0 50px rgba(0, 0, 255, 0.7);
            }
        }

        /* Color Transition Animation */
        @keyframes colorChange {
            0% {
                background-color: #d6eaf8; /* Light blue */
            }
            33% {
                background-color: #d1f0e4; /* Soft light green */
            }
            66% {
                background-color: #f9e2e0; /* Soft pinkish */
            }
            100% {
                background-color: #f4f4f4; /* Neutral beige */
            }
        }

        /* Card Styling with LED Border Effect */
        .dashboard-box {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .dashboard-box .card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            width: 30%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            border: 8px solid transparent; /* Thicker border */
            animation: ledBorderGlow 3s linear infinite, clockwiseGlow 4s linear infinite;
        }

        /* LED Border Glowing Effect with Clockwise Movement */
        @keyframes ledBorderGlow {
            0% {
                border-color: rgba(255, 0, 0, 0.7);
            }
            50% {
                border-color: rgba(0, 255, 0, 0.7);
            }
            100% {
                border-color: rgba(0, 0, 255, 0.7);
            }
        }

        /* Clockwise LED Glow Movement */
        @keyframes clockwiseGlow {
            0% {
                border-image: linear-gradient(to top right, rgba(255, 0, 0, 0.7), rgba(0, 255, 0, 0.7), rgba(0, 0, 255, 0.7)) 1;
            }
            25% {
                border-image: linear-gradient(to top left, rgba(255, 0, 0, 0.7), rgba(0, 255, 0, 0.7), rgba(0, 0, 255, 0.7)) 1;
            }
            50% {
                border-image: linear-gradient(to bottom left, rgba(255, 0, 0, 0.7), rgba(0, 255, 0, 0.7), rgba(0, 0, 255, 0.7)) 1;
            }
            75% {
                border-image: linear-gradient(to bottom right, rgba(255, 0, 0, 0.7), rgba(0, 255, 0, 0.7), rgba(0, 0, 255, 0.7)) 1;
            }
            100% {
                border-image: linear-gradient(to top right, rgba(255, 0, 0, 0.7), rgba(0, 255, 0, 0.7), rgba(0, 0, 255, 0.7)) 1;
            }
        }

        .dashboard-box .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card i {
            font-size: 40px;
            color: #1abc9c;
            margin-bottom: 15px;
        }

        .card h3 {
            font-size: 24px;
            color: #34495e;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 18px;
            color: #7f8c8d;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-box {
                flex-direction: column;
                align-items: center;
            }

            .dashboard-box .card {
                width: 90%;
                margin-bottom: 20px;
            }
        }
    </style>

<section class="content">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard Supplier</h1>

    <div class="dashboard-container">
        <div class="dashboard-box">
            <div class="card">
                <i class="fas fa-box"></i>
                <h3>Total Barang</h3>
                {{-- <p>{{ $totalBarang }} barang</p> --}}
            </div>
            <div class="card">
                <i class="fas fa-user"></i>
                <h3>Profile</h3>
                <p>{{ Auth::user()->name }}</p>
            </div>
            <div class="card">
                <i class="fas fa-cogs"></i>
                <h3>Stok Barang</h3>
            </div>
        </div>
    </div>
</section>

 <!-- Font Awesome for Icons -->
 <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
