@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Activity Logs</h1>
                <p class="text-muted small mb-0">Track all system activities and user interactions</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('activity-logs.export') }}" class="btn btn-outline-primary btn-sm shadow-sm"
                    target="_blank">
                    <i class="fas fa-file-export fa-sm me-1"></i> Export Excel
                </a>
                <a href="#" class="btn btn-outline-danger btn-sm shadow-sm"
                    onclick="event.preventDefault(); confirmClearLogs()">
                    <i class="fas fa-trash fa-sm me-1"></i> Hapus Semua
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs text-muted text-uppercase mb-1">Total Logs</div>
                                <div class="h3 mb-0 text-gray-800">{{ number_format($logs->total()) }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="fas fa-list text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Logs Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Activity Logs</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="dataTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 text-nowrap">Waktu</th>
                                <th>User</th>
                                <th>Tipe Aktivitas</th>
                                <th>Deskripsi</th>
                                <th class="text-center">Properties</th>
                                <th>IP Address</th>
                                <th class="text-center px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="px-4 text-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        @if(optional($log->user)->name)
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-gray-200 p-2 me-2">
                                                    <i class="fas fa-user fa-sm text-gray-600"></i>
                                                </div>
                                                <span>{{ $log->user->name }}</span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-gray-200 p-2 me-2">
                                                    <i class="fas fa-cog fa-sm text-gray-600"></i>
                                                </div>
                                                <span>System</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-inline-flex align-items-center">
                                            <i class="fas fa-circle fa-xs me-2 text-gray-400"></i>
                                            <span>{{ ucfirst($log->activity_type) }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#propertiesModal{{ $log->id }}">
                                            <i class="fas fa-code fa-sm"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-network-wired fa-sm text-gray-400 me-2"></i>
                                            {{ $log->ip_address }}
                                        </div>
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('activity-logs.show', $log->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye fa-sm"></i>
                                            </a>
                                            <form action="{{ route('activity-logs.destroy', $log->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="confirmDelete('{{ $log->id }}')">
                                                    <i class="fas fa-trash fa-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Tidak ada activity logs
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-end p-4 border-top">
                    <div class="pagination-container">
                        {{ $logs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($logs as $log)
        <!-- Properties Modal -->
        <div class="modal fade" id="propertiesModal{{ $log->id }}" tabindex="-1" role="dialog"
            aria-labelledby="propertiesModalLabel{{ $log->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="propertiesModalLabel{{ $log->id }}">
                            <i class="fas fa-code text-primary me-2"></i>
                            Properties Detail
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <pre class="bg-light p-4 rounded-3 mb-0"><code class="text-dark">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Display notifications
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif
        });

        function confirmDelete(logId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Log ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/activity-logs/${logId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function confirmClearLogs() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Semua activity logs akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('activity-logs.clear') }}";
                }
            });
        }
    </script>
    @endpush
@endsection
