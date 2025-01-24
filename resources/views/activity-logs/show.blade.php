@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detail Activity Log</h1>
            <p class="text-muted small mb-0">View detailed information about this activity log</p>
        </div>
        <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase">Waktu</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <span class="h6 mb-0">
                                @if($activityLog->created_at)
                                    {{ $activityLog->created_at->format('d/m/Y H:i:s') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase">User</label>
                        <div class="d-flex align-items-center mt-1">
                            <div class="rounded-circle bg-gray-200 p-2 me-2">
                                <i class="fas {{ $activityLog->user_id ? 'fa-user' : 'fa-cog' }} fa-sm text-gray-600"></i>
                            </div>
                            <span class="h6 mb-0">{{ optional($activityLog->user)->name ?? 'System' }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase">Email</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <span class="h6 mb-0">{{ optional($activityLog->user)->email ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase">Role</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-user-tag text-primary me-2"></i>
                            <span class="h6 mb-0">{{ optional($activityLog->user)->role ? ucfirst($activityLog->user->role) : '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase">Tipe Aktivitas</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-circle fa-xs text-gray-400 me-2"></i>
                            <span class="h6 mb-0">{{ $activityLog->activity_type_text ?? ucfirst($activityLog->activity_type) ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase">Deskripsi</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-align-left text-primary me-2"></i>
                            <span class="h6 mb-0">{{ $activityLog->description ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase">IP Address</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-network-wired text-primary me-2"></i>
                            <span class="h6 mb-0">{{ $activityLog->ip_address ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted text-uppercase">User Agent</label>
                        <div class="d-flex align-items-center mt-1">
                            <i class="fas fa-desktop text-primary me-2"></i>
                            <span class="h6 mb-0 text-wrap">{{ $activityLog->user_agent ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($activityLog->properties)
            <div class="mt-4">
                <label class="small text-muted text-uppercase mb-2">Properties</label>
                <div class="position-relative">
                    <pre class="bg-light p-4 rounded-3 mb-0"><code class="text-dark">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT) }}</code></pre>
                    <div class="position-absolute top-0 end-0 p-3">
                        <i class="fas fa-code text-gray-400"></i>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
