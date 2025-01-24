@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detail Transaksi</h1>
            <p class="text-muted small mb-0">Detail data transaksi keuangan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('keuangan.edit', $keuangan->id) }}" class="btn btn-info btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm me-1"></i> Edit
            </a>
            <a href="{{ route('keuangan.index') }}" class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Transaction Type -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-{{ $keuangan->status_color }} bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-{{ $keuangan->jenis === 'pemasukan' ? 'arrow-up' : 'arrow-down' }} text-{{ $keuangan->status_color }}"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-muted text-uppercase">Jenis Transaksi</div>
                                    <div class="h5 mb-0">{{ $keuangan->jenis_label }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-money-bill text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-muted text-uppercase">Jumlah</div>
                                    <div class="h5 mb-0 text-{{ $keuangan->status_color }}">{{ $keuangan->formatted_jumlah }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-tag text-info"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-muted text-uppercase">Kategori</div>
                                    <div class="h5 mb-0">{{ $keuangan->kategori }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-calendar text-warning"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-muted text-uppercase">Tanggal</div>
                                    <div class="h5 mb-0">{{ $keuangan->tanggal->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($keuangan->keterangan)
                            <div class="col-12">
                                <div class="d-flex mb-3">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                                        <i class="fas fa-align-left text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-muted text-uppercase">Keterangan</div>
                                        <div class="h6 mb-0">{{ $keuangan->keterangan }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Transaction Proof -->
                        @if($keuangan->bukti_transaksi)
                            <div class="col-12">
                                <div class="d-flex mb-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                        <i class="fas fa-image text-success"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs text-muted text-uppercase">Bukti Transaksi</div>
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($keuangan->bukti_transaksi) }}" 
                                                 alt="Bukti Transaksi" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 300px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Created By -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-gray-200 p-3 me-3">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-muted text-uppercase">Dibuat Oleh</div>
                                    <div class="h6 mb-0">{{ $keuangan->user->name }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Created At -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-gray-200 p-3 me-3">
                                    <i class="fas fa-clock text-gray-600"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-muted text-uppercase">Dibuat Pada</div>
                                    <div class="h6 mb-0">{{ $keuangan->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
