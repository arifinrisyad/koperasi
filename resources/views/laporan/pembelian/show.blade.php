@extends('layouts.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-xl border-0 mb-4">
                    <div class="card-header bg-gradient-primary position-relative py-5">
                        <div class="row align-items-center position-relative z-1">
                            <div class="col">
                                <h5><span class="badge bg-white text-primary mb-2 px-3 py-2">Detail Laporan Pembelian</span>
                                </h5>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex gap-3">
                                    <a href="{{ route('laporan-pembelian.edit', $laporan->id) }}"
                                        class="btn btn-warning btn-lg shadow-sm hover-translate">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                    {{-- <a href="{{ route('laporan-pembelian.print', $laporan->id) }}"
                                        class="btn btn-light btn-lg shadow-sm hover-translate" target="_blank">
                                        <i class="fas fa-print me-2"></i>Print
                                    </a> --}}
                                    <a href="{{ route('laporan-pembelian.export-pdf', $laporan->id) }}"
                                        class="btn btn-danger btn-lg shadow-sm hover-translate" target="_blank">
                                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="wave-wrapper">
                            {{-- <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                        </svg> --}}
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <!-- Informasi Utama -->
                                <div class="card bg-light border-0 mb-4 hover-shadow">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="icon-shape bg-primary text-white rounded-circle me-3">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <h5 class="text-primary mb-0">Informasi Barang</h5>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <div class="info-card bg-white rounded-3 p-4 h-100">
                                                    <p class="text-muted small mb-1">Nama Barang</p>
                                                    <h5 class="mb-0">{{ $laporan->nama_barang }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-card bg-white rounded-3 p-4 h-100">
                                                    <p class="text-muted small mb-1">Jumlah</p>
                                                    <h5 class="mb-0">{{ $laporan->jumlah }} <small
                                                            class="text-muted">unit</small></h5>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-card bg-white rounded-3 p-4 h-100">
                                                    <p class="text-muted small mb-1">Total Harga</p>
                                                    <h5 class="text-success mb-0">Rp
                                                        {{ number_format($laporan->total_harga, 0, ',', '.') }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Tambahan -->
                                <div class="card bg-light border-0 hover-shadow">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="icon-shape bg-info text-white rounded-circle me-3">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <h5 class="text-primary mb-0">Informasi Transaksi</h5>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="info-card bg-white rounded-3 p-4 h-100">
                                                    <p class="text-muted small mb-1">Tanggal Transaksi</p>
                                                    <h5 class="mb-0">
                                                        {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card bg-white rounded-3 p-4 h-100">
                                                    <p class="text-muted small mb-1">Periode</p>
                                                    <h5 class="mb-0">
                                                        {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('F Y') }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Informasi Pembuat -->
                                <div class="card bg-light border-0 mb-4 hover-shadow">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="icon-shape bg-warning text-white rounded-circle me-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <h5 class="text-primary mb-0">Informasi Pembuat</h5>
                                        </div>
                                        <div class="info-card bg-white rounded-3 p-4">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="avatar rounded-circle bg-primary text-white me-3">
                                                    <i class="fas fa-user-circle"></i>
                                                </div>
                                                <div>
                                                    <p class="text-muted small mb-0">Dibuat oleh</p>
                                                    <h5 class="mb-0">{{ $laporan->user->name }}</h5>
                                                </div>
                                            </div>
                                            <p class="text-muted small mb-1">Tanggal Dibuat</p>
                                            <h6 class="mb-0">
                                                {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y H:i') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Card -->
                                <div class="card border-0 hover-shadow">
                                    <div class="card-body p-4 bg-gradient-success text-white rounded-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Status Laporan</h5>
                                            <div class="status-indicator pulse"></div>
                                        </div>
                                        <p class="mb-0 opacity-8">Laporan telah diverifikasi dan disimpan dalam sistem.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{ route('laporan-pembelian.index') }}"
                                class="btn btn-secondary btn-lg hover-translate">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .icon-shape {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .avatar {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, .05);
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
        }

        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
        }

        .hover-translate {
            transition: all 0.3s ease;
        }

        .hover-translate:hover {
            transform: translateY(-3px);
        }

        .wave-wrapper {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .waves {
            position: relative;
            width: 100%;
            height: 40px;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            background-color: #ffffff;
            border-radius: 50%;
            position: relative;
        }

        .status-indicator.pulse::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        .z-1 {
            z-index: 1;
        }

        .display-6 {
            font-size: 2rem;
            font-weight: 600;
            line-height: 1.2;
        }
    </style>
@endsection
