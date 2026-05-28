@extends('layouts.main')
@section('main')
    <style>
        .rbac-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        
        .rbac-table th {
            background-color: rgba(240, 244, 248, 0.6) !important;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 2px solid #eef2f5 !important;
        }

        .rbac-table td {
            vertical-align: middle;
            transition: background-color 0.2s ease;
        }

        .rbac-table tr:hover td {
            background-color: rgba(248, 250, 252, 0.8) !important;
        }

        /* Custom Premium IOS Switch Toggle */
        .form-switch .form-check-input {
            width: 3.2em;
            height: 1.6em;
            background-color: #e2e8f0;
            border-color: transparent;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
            transition: background-position 0.25s ease-in-out, background-color 0.25s ease-in-out, transform 0.1s ease;
            cursor: pointer;
            border-radius: 20px;
        }

        .form-switch .form-check-input:focus {
            box-shadow: none;
            border-color: transparent;
        }

        .form-switch .form-check-input:checked {
            background-color: #38bdf8; /* Sleek sky blue color */
            background-position: right center;
        }

        .form-switch .form-check-input:active {
            transform: scale(0.95);
        }

        .perm-badge {
            background-color: rgba(56, 189, 248, 0.1);
            color: #0284c7;
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 8px;
            font-size: 0.85rem;
        }
    </style>
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-1 pb-0 display-4" id="title">Pengaturan Hak Akses (RBAC)</h1>
                        <p class="text-muted mb-0">Kelola hak akses dinamis untuk setiap level pengguna sistem secara real-time.</p>
                    </div>
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card rbac-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table rbac-table align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 30%">Modul / Hak Akses</th>
                                            <th style="width: 40%">Deskripsi Fitur</th>
                                            @foreach ($roles as $role)
                                                <th class="text-center" style="width: 10%">{{ strtoupper($role) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold text-alternate mb-1">{{ $permission->display_name }}</span>
                                                        <div>
                                                            <span class="perm-badge">{{ $permission->name }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted small">{{ $permission->description ?? 'Tidak ada deskripsi' }}</span>
                                                </td>
                                                @foreach ($roles as $role)
                                                    @php
                                                        $isChecked = isset($rolePermissions[$role]) && in_array($permission->id, $rolePermissions[$role]);
                                                    @endphp
                                                    <td class="text-center">
                                                        <div class="form-check form-switch d-inline-block">
                                                            <input 
                                                                class="form-check-input permission-toggle" 
                                                                type="checkbox" 
                                                                role="switch" 
                                                                data-role="{{ $role }}" 
                                                                data-permission-id="{{ $permission->id }}"
                                                                {{ $isChecked ? 'checked' : '' }}
                                                            >
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content End -->
        </div>
    </main>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            // Setup CSRF Token for Ajax requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle toggle changes
            $('.permission-toggle').on('change', function() {
                var toggle = $(this);
                var role = toggle.attr('data-role');
                var permissionId = toggle.attr('data-permission-id');
                var isChecked = toggle.is(':checked');

                // Animate slightly on check
                toggle.addClass('pe-none'); // Prevent double click

                $.ajax({
                    url: "{{ route('user.rbac.update') }}",
                    type: "POST",
                    data: {
                        role: role,
                        permission_id: permissionId,
                        checked: isChecked ? 1 : 0
                    },
                    success: function(response) {
                        toggle.removeClass('pe-none');
                        
                        // Beautiful Premium SweetAlert Toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                        Toast.fire({
                            icon: response.icon,
                            title: response.text
                        });
                    },
                    error: function() {
                        toggle.removeClass('pe-none');
                        // Revert checkbox state if failed
                        toggle.prop('checked', !isChecked);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memperbarui hak akses. Silakan coba lagi.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
