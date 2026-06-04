@extends('layouts.main')
@section('main')
    <style>
        .settings-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
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
            background-color: #f59e0b; /* Golden warm tone matching catalog style */
            background-position: right center;
        }

        .form-switch .form-check-input:active {
            transform: scale(0.95);
        }
    </style>

    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container mb-4">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-1 pb-0 display-4" id="title">Pengaturan Sistem</h1>
                        <p class="text-muted mb-0">Konfigurasi parameter global aplikasi secara real-time.</p>
                    </div>
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-5">
                <div class="col-12 col-lg-8">
                    <div class="card settings-card">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                            <h3 class="fw-bold text-dark mb-1">Integrasi Gateway Xendit</h3>
                            <p class="text-muted small">Kelola mode operasional Xendit Payment Gateway.</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between p-3 border border-light rounded-lg bg-light-opacity">
                                <div class="me-3">
                                    <h5 class="fw-bold text-alternate mb-1">Mode Simulasi Xendit</h5>
                                    <p class="text-muted small mb-0">
                                        Ketika diaktifkan, semua transaksi checkout tamu di halaman catalog hanya akan menampilkan simulasi pesan konfirmasi pembayaran tanpa membuat invoice Xendit sungguhan atau mencatat pesanan ke database dashboard.
                                    </p>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input 
                                        class="form-check-input settings-toggle" 
                                        type="checkbox" 
                                        role="switch" 
                                        id="xenditSimulationToggle"
                                        data-key="xendit_simulation_mode"
                                        {{ $xenditSimulationMode === 'true' ? 'checked' : '' }}
                                    >
                                </div>
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
            $('.settings-toggle').on('change', function() {
                var toggle = $(this);
                var key = toggle.attr('data-key');
                var isChecked = toggle.is(':checked');

                toggle.addClass('pe-none'); // Prevent double click

                $.ajax({
                    url: "{{ route('user.pengaturan.update') }}",
                    type: "POST",
                    data: {
                        key: key,
                        value: isChecked ? 'true' : 'false'
                    },
                    success: function(response) {
                        toggle.removeClass('pe-none');
                        
                        // SweetAlert Toast Notification
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
                        // Revert switch state if failed
                        toggle.prop('checked', !isChecked);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memperbarui pengaturan sistem. Silakan coba kembali.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
