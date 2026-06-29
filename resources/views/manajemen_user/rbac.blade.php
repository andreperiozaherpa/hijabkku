@extends('layouts.main')
@section('main')
    <style>
        .rbac-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .rbac-header-card {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(2, 132, 199, 0.15);
        }

        .accordion-custom .accordion-item {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            background: #fff;
            margin-bottom: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e2e8f0 !important;
            overflow: hidden;
        }

        .accordion-custom .accordion-item:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08) !important;
            transform: translateY(-2px);
            border-color: #cbd5e1 !important;
        }

        .accordion-custom .accordion-button {
            border-radius: 16px !important;
            padding: 1.25rem 1.5rem !important;
            background-color: #fff !important;
            color: #1e293b !important;
            box-shadow: none !important;
            transition: all 0.25s ease;
        }

        .accordion-custom .accordion-button:not(.collapsed) {
            background: linear-gradient(90deg, rgba(56, 189, 248, 0.08) 0%, rgba(3, 105, 161, 0.03) 100%) !important;
            color: #0369a1 !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            border-bottom: 1px solid rgba(56, 189, 248, 0.15) !important;
        }

        .accordion-custom .accordion-button::after {
            background-size: 1rem !important;
            width: 1rem !important;
            height: 1rem !important;
            transition: transform 0.2s ease-in-out;
        }

        .rbac-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .rbac-table th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.8px;
            padding: 1rem 1.25rem !important;
            border-bottom: 2px solid #e2e8f0 !important;
        }

        .rbac-table tr:last-child td {
            border-bottom: none !important;
        }

        .rbac-table td {
            padding: 1.25rem 1.25rem !important;
            border-bottom: 1px solid #f1f5f9 !important;
            transition: background-color 0.2s ease;
        }

        .rbac-table tr:hover td {
            background-color: #f8fafc !important;
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
            background-color: #0ea5e9; /* Sleek sky blue color */
            background-position: right center;
        }

        .form-switch .form-check-input:active {
            transform: scale(0.95);
        }

        .perm-badge {
            background-color: rgba(14, 165, 233, 0.08);
            color: #0369a1;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-family: 'Courier New', Courier, monospace;
            border: 1px solid rgba(14, 165, 233, 0.15);
        }

        .toggle-btn-custom {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: rgba(71, 85, 105, 0.05);
            color: #475569;
            border: 1px solid rgba(71, 85, 105, 0.1);
        }

        .toggle-btn-custom:hover {
            background-color: #0284c7;
            color: #fff;
            border-color: #0284c7;
            transform: translateY(-1px);
        }
    </style>
    <main>
        <div class="container rbac-container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container mb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-1 pb-0 display-4 fw-bold" id="title">Pengaturan Hak Akses (RBAC)</h1>
                        <p class="text-muted mb-0">Kelola hak akses dinamis untuk setiap level pengguna sistem secara real-time.</p>
                    </div>
                    <div class="col-12 col-md-5 text-end mt-3 mt-md-0 d-flex align-items-center justify-content-md-end">
                        <button class="btn btn-icon btn-icon-start btn-primary btn-sm shadow-sm" type="button" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i data-acorn-icon="plus"></i>
                            <span>Tambah Role</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-5">
                <div class="col-12">
                    @php
                        $groupedPermissions = $permissions->groupBy('module');
                    @endphp

                    <div class="accordion accordion-custom" id="rbacAccordion">
                        @foreach ($groupedPermissions as $moduleName => $modulePermissions)
                            @php
                                $cleanModuleName = str_replace([' ', '/'], '-', strtolower($moduleName));
                                
                                // Map icons based on module names
                                $icon = 'grid';
                                if (strtolower($moduleName) === 'dashboard') {
                                    $icon = 'home';
                                } elseif (strtolower($moduleName) === 'transaksi') {
                                    $icon = 'cart';
                                } elseif (strtolower($moduleName) === 'manajemen') {
                                    $icon = 'gear';
                                } elseif (strtolower($moduleName) === 'stok') {
                                    $icon = 'factory';
                                } elseif (strtolower($moduleName) === 'laporan') {
                                    $icon = 'book-open';
                                } elseif (strtolower($moduleName) === 'panduan') {
                                    $icon = 'book';
                                }
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $cleanModuleName }}">
                                    <button class="accordion-button collapsed fw-bold d-flex align-items-center justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $cleanModuleName }}" aria-expanded="false" aria-controls="collapse-{{ $cleanModuleName }}">
                                        <div class="d-flex align-items-center">
                                            <i data-acorn-icon="{{ $icon }}" class="me-3 text-primary" data-acorn-size="20"></i>
                                            <span style="font-size: 1.05rem;">Modul {{ $moduleName }}</span>
                                            <span class="badge bg-outline-primary ms-3 rounded-pill px-2.5 py-1.5" style="font-size: 0.72rem;">{{ $modulePermissions->count() }} Fitur</span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $cleanModuleName }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $cleanModuleName }}" data-bs-parent="#rbacAccordion">
                                    <div class="accordion-body p-0">
                                        <div class="table-responsive">
                                            <table class="table rbac-table align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="ps-4" style="width: 30%">Hak Akses / Fitur</th>
                                                        <th style="width: 30%">Deskripsi Fitur</th>
                                                        @foreach ($roles as $role)
                                                            <th class="text-center" style="width: 10%">
                                                                <div class="d-flex flex-column align-items-center">
                                                                    <span class="mb-1 text-alternate">{{ strtoupper($role) }}</span>
                                                                    <button type="button" class="btn btn-xs toggle-btn-custom select-all-module-role" data-module="{{ $cleanModuleName }}" data-role="{{ $role }}">
                                                                        Toggle All
                                                                    </button>
                                                                </div>
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($modulePermissions as $permission)
                                                        <tr>
                                                            <td class="ps-4">
                                                                <div class="d-flex flex-column">
                                                                    <span class="fw-bold text-alternate mb-1" style="font-size: 0.95rem;">{{ $permission->display_name }}</span>
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
                                                                            data-module="{{ $cleanModuleName }}"
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
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Content End -->
        </div>
    </main>

    <!-- Modal Tambah Role -->
    <div class="modal fade" id="addRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-alternate"><i data-acorn-icon="plus" class="me-2 text-primary" data-acorn-size="18"></i>Tambah Role Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAddRole" class="tooltip-label-end" novalidate data-no-spinner>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="tag"></i>
                            <input type="text" class="form-control" placeholder="Nama Role (contoh: supervisor)" id="role_name" name="role_name" required pattern="^[a-zA-Z0-9_\-]+$">
                            <div class="form-text text-muted small">Hanya huruf, angka, strip (-), dan underscore (_).</div>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="bookmark"></i>
                            <input type="text" class="form-control" placeholder="Display Name (contoh: Supervisor)" id="role_display_name" name="role_display_name" required>
                        </div>

                        <div class="mt-4 pt-2 border-top text-end">
                            <button type="button" class="btn btn-outline-muted btn-sm me-1" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i data-acorn-icon="save" class="me-1" data-acorn-size="15"></i>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

            // Initialize form validation
            $("#formAddRole").validate({
                rules: {
                    role_name: {
                        required: true,
                        pattern: /^[a-zA-Z0-9_\-]+$/
                    },
                    role_display_name: {
                        required: true
                    }
                },
                messages: {
                    role_name: {
                        required: "Nama Role wajib diisi.",
                        pattern: "Format Nama Role tidak valid (hanya huruf, angka, - dan _)."
                    },
                    role_display_name: {
                        required: "Display Name wajib diisi."
                    }
                }
            });

            // Handle form submission to create role
            $('#formAddRole').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    var roleName = $('#role_name').val().trim().toLowerCase();
                    var roleDisplayName = $('#role_display_name').val().trim();

                    $.ajax({
                        url: "{{ route('user.rbac.role.store') }}",
                        type: "POST",
                        data: {
                            name: roleName,
                            display_name: roleDisplayName
                        },
                        success: function(response) {
                            if (response.icon === 'success') {
                                $('#addRoleModal').modal('hide');
                                Swal.fire({
                                    icon: response.icon,
                                    title: response.title,
                                    text: response.text,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: response.icon,
                                    title: response.title,
                                    text: response.text
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal membuat role baru.'
                            });
                        }
                    });
                }
            });

            // Handle toggle changes
            $('.permission-toggle').on('change', function() {
                var toggle = $(this);
                var role = toggle.attr('data-role');
                var permissionId = toggle.attr('data-permission-id');
                var isChecked = toggle.is(':checked');

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

            // Toggle All permissions for a specific role in a module
            $('.select-all-module-role').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var button = $(this);
                var module = button.attr('data-module');
                var role = button.attr('data-role');
                
                // Find all checkboxes for this role in this module
                var checkboxes = $(`.permission-toggle[data-module="${module}"][data-role="${role}"]`);
                
                // Determine if we should check all or uncheck all
                // If any is unchecked, we check all. Otherwise, we uncheck all.
                var anyUnchecked = false;
                checkboxes.each(function() {
                    if (!$(this).is(':checked')) {
                        anyUnchecked = true;
                        return false; // break loop
                    }
                });
                
                var targetState = anyUnchecked;
                var promises = [];
                
                checkboxes.each(function() {
                    var cb = $(this);
                    if (cb.is(':checked') !== targetState) {
                        cb.prop('checked', targetState);
                        
                        var permissionId = cb.attr('data-permission-id');
                        cb.addClass('pe-none');
                        
                        var p = $.ajax({
                            url: "{{ route('user.rbac.update') }}",
                            type: "POST",
                            data: {
                                role: role,
                                permission_id: permissionId,
                                checked: targetState ? 1 : 0
                            }
                        }).then(
                            function(response) {
                                cb.removeClass('pe-none');
                            },
                            function() {
                                cb.removeClass('pe-none');
                                cb.prop('checked', !targetState);
                            }
                        );
                        promises.push(p);
                    }
                });
                
                if (promises.length > 0) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Memperbarui hak akses grup',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    Promise.all(promises).then(() => {
                        Swal.close();
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Hak akses grup berhasil diperbarui.'
                        });
                    });
                }
            });
        });
    </script>
@endpush
