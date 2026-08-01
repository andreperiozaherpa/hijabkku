@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Transaksi Transfer Dana</h1>
                    </div>
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Saldo Card Start -->
            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-gradient-light sw-11 sh-11 rounded-xl d-flex justify-content-center align-items-center me-3">
                                    <i data-acorn-icon="wallet" class="text-primary" data-acorn-size="24"></i>
                                </div>
                                <div>
                                    <div class="text-small text-muted">Saldo Xendit (CASH IDR)</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="cta-3 saldo-hidden" id="saldoTersedia" data-saldo="Rp {{ number_format($saldo['available'], 0, ',', '.') }}">Rp ••••••••••</div>
                                        <button type="button" id="toggleSaldo" class="btn btn-icon btn-icon-only btn-outline-muted btn-sm" title="Sembunyikan / Tampilkan Saldo">
                                            <i data-acorn-icon="eye-off" class="text-primary"></i>
                                        </button>
                                    </div>
                                    <a href="#" id="refreshSaldo" class="small text-primary">Perbarui Saldo</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Saldo Card End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12">
                    <section class="scroll-section" id="hover">
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-1">
                                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#tbTransfer">
                                            <span class="search-magnifier-icon">
                                                <i data-acorn-icon="search"></i>
                                            </span>
                                            <span class="search-delete-icon d-none">
                                                <i data-acorn-icon="close"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-1">
                                        <div class="d-inline-block">
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-add" type="button" data-datatable="#tbTransfer">
                                                <i data-acorn-icon="plus"></i>
                                            </button>
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#tbTransfer">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="data-table-responsive-wrapper">
                                    <table class="data-table nowrap hover" id="tbTransfer" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
                                                <th class="text-muted text-small text-uppercase">Kode Transfer</th>
                                                <th class="text-muted text-small text-uppercase">Penerima</th>
                                                <th class="text-muted text-small text-uppercase">Bank</th>
                                                <th class="text-muted text-small text-uppercase">No Rekening</th>
                                                <th class="text-muted text-small text-uppercase">Nominal</th>
                                                <th class="text-muted text-small text-uppercase">Status</th>
                                                <th class="text-muted text-small text-uppercase">Tanggal</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- Content End -->
        </div>
    </main>

    <div class="modal fade" id="transferModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Dana</h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formTransfer" class="tooltip-label-end" novalidate data-no-spinner>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="user"></i>
                            <select class="form-control" id="rekening_client_id" name="rekening_client_id" required>
                                <option value="">Pilih Rekening Client</option>
                                @foreach ($rekeningClients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nama_client }} - {{ $client->bank_code }} {{ $client->account_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 filled">
                            <i data-acorn-icon="money"></i>
                            <input type="text" class="form-control" placeholder="Nominal Transfer (Rp)" id="amount" name="amount" inputmode="numeric" maxlength="15" required>
                        </div>
                        <div class="mb-3 filled">
                            <i data-acorn-icon="lock-on"></i>
                            <input type="password" class="form-control" placeholder="PIN Validasi Transfer (6 Digit)" id="transfer_pin" name="transfer_pin" maxlength="6" required>
                        </div>
                        <div class="filled">
                            <textarea class="form-control" placeholder="Keterangan / Deskripsi (opsional)" rows="2" id="description" name="description"></textarea>
                            <i data-acorn-icon="pencil"></i>
                        </div>
                        <div class="mt-3 float-end">
                            <button type="button" class="closed btn btn-muted">Close</button>
                            <button type="button" class="simpan btn btn-primary">Transfer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="pinModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi PIN Admin</h5>
                    <button type="button" class="closed-pin btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPin" class="tooltip-label-end" novalidate data-no-spinner>
                        <p class="text-muted small mb-3">Masukkan PIN validasi admin untuk menampilkan saldo Xendit.</p>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="lock-on"></i>
                            <input type="password" class="form-control" placeholder="PIN Admin (6 Digit)" id="pin_admin"
                                name="pin" maxlength="6" inputmode="numeric" autocomplete="off" required>
                        </div>
                        <div class="mt-3 float-end">
                            <button type="button" class="closed-pin btn btn-muted">Batal</button>
                            <button type="button" class="verify-pin btn btn-primary">Verifikasi</button>
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
            $('#tbTransfer').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                length: 10,
                pageLength: 20,
                buttons: ['copy', 'excel', 'csv', 'print'],
                order: [
                    [0, "desc"]
                ],
                responsive: true,
                ajax: '/transfer/show',
                columns: [
                    { 'data': null, 'sortable': false, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1 } },
                    { data: 'kode_transfer' },
                    { data: 'account_holder_name' },
                    { data: 'bank_code' },
                    { data: 'account_number' },
                    { data: 'amount' },
                    { data: 'status' },
                    { data: 'created_at' },
                ],
                sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                language: {
                    paginate: {
                        previous: '<i class="cs-chevron-left"></i>',
                        next: '<i class="cs-chevron-right"></i>',
                    },
                },
            });

            $.validator.addMethod('rupiah', function(value, element) {
                if (value === '') return true;
                var clean = value.replace(/\./g, '').trim();
                return /^\d+$/.test(clean) && parseInt(clean, 10) >= 10000;
            }, 'Nominal minimal Rp 10.000 dan harus berupa angka.');

            $("#formTransfer").validate({
                rules: {
                    amount: {
                        required: true,
                        rupiah: true,
                    },
                    transfer_pin: {
                        required: true,
                        digits: true,
                        minlength: 6,
                        maxlength: 6,
                    },
                },
                messages: {
                    amount: {
                        required: 'Nominal transfer wajib diisi.',
                    },
                },
            });

            $('#amount').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length === 0) {
                    $(this).val('');
                    return;
                }
                $(this).val(Number(value).toLocaleString('id-ID'));
            });

            $(document).on('click', '.datatable-add', function() {
                $('#transferModal').modal('show');
                $('.modal-title').html('Transfer Dana');
            });

            $(document).on('click', '.closed', function() {
                $('#transferModal').modal('toggle');
                $('#rekening_client_id').val('');
                $('#amount').val('');
                $('#description').val('');
                $('#transfer_pin').val('');
            });

            $(document).on('click', '.simpan', function() {
                var valid = $("#formTransfer").valid();
                if (valid != true) {
                    return;
                }

                var rekeningId = $('#rekening_client_id').val();
                var rekeningLabel = rekeningId ? $('#rekening_client_id option:selected').text() : '-';
                var amountDisplay = $('#amount').val() || '0';
                var deskripsi = $('#description').val() || '-';

                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Transfer Dana',
                    html: '<div class="text-start" style="font-size: 0.9rem; line-height: 1.9;">' +
                        '<div><strong>Rekening Tujuan :</strong> ' + rekeningLabel + '</div>' +
                        '<div><strong>Nominal Transfer :</strong> Rp ' + amountDisplay + '</div>' +
                        '<div><strong>Keterangan :</strong> ' + deskripsi + '</div>' +
                        '<hr>' +
                        '<div class="text-muted">Pastikan data di atas sudah benar. Saldo Xendit akan terpotong setelah transfer diproses.</div>' +
                        '</div>',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    var data = {
                        rekening_client_id: $('#rekening_client_id').val(),
                        amount: $('#amount').val().replace(/\./g, ''),
                        description: $('#description').val(),
                        transfer_pin: $('#transfer_pin').val(),
                    };

                    Swal.fire({
                        title: 'Memproses Transfer...',
                        html: 'Mohon tunggu, transfer sedang diproses ke Xendit.<br>Jangan tutup halaman ini.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: function() {
                            Swal.showLoading();
                        },
                    });

                    $.ajax({
                        type: 'post',
                        url: '/transfer/store',
                        data: data,
                        timeout: 60000,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            $('.simpan').prop('disabled', true).html('Memproses...');
                        },
                        success: function(response) {
                            Swal.close();
                            Swal.fire({
                                icon: response.icon,
                                title: response.title,
                                text: response.text,
                            });
                            $('.closed').click();
                            $('#tbTransfer').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            Swal.close();

                            var icon = 'error';
                            var title = 'Gagal';
                            var text = 'Terjadi kesalahan pada proses transfer.';

                            if (xhr.status === 0) {
                                title = 'Koneksi Gagal';
                                text = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda dan coba lagi.';
                            } else {
                                var response = xhr.responseJSON;

                                if (response) {
                                    icon = response.icon || icon;
                                    title = response.title || title;
                                    text = response.text || text;
                                }

                                if (xhr.status === 500) {
                                    title = 'Server Error';
                                    text = 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.';
                                } else if (xhr.status === 429) {
                                    title = 'Terlalu Banyak Percobaan';
                                    text = 'Terlalu banyak permintaan dalam waktu singkat. Tunggu sebentar lalu coba lagi.';
                                }
                            }

                            Swal.fire({
                                icon: icon,
                                title: title,
                                text: text,
                            });
                        },
                        complete: function() {
                            $('.simpan').prop('disabled', false).html('Transfer');
                        }
                    });
                });
            });

            function renderSaldoIcon(iconName) {
                $('#toggleSaldo').html('<i data-acorn-icon="' + iconName + '" data-acorn-size="18" class="text-primary"></i>');
                if (typeof AcornIcons !== 'undefined') {
                    new AcornIcons().replace();
                }
            }

            $(document).on('click', '#toggleSaldo', function(e) {
                e.preventDefault();
                var $el = $('#saldoTersedia');

                if ($el.hasClass('saldo-hidden')) {
                    $('#pinModal').modal('show');
                    $('#pin_admin').val('');
                    setTimeout(function() {
                        $('#pin_admin').focus();
                    }, 300);
                } else {
                    $el.addClass('saldo-hidden').html('Rp ••••••••••');
                    renderSaldoIcon('eye-off');
                }
            });

            $(document).on('click', '.closed-pin', function() {
                $('#pinModal').modal('hide');
                $('#pin_admin').val('');
            });

            $('#pinModal').on('hidden.bs.modal', function() {
                $('#pin_admin').val('');
            });

            $('#pin_admin').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('.verify-pin').click();
                }
            });

            $(document).on('click', '.verify-pin', function() {
                var pin = $('#pin_admin').val();

                if (!/^\d{6}$/.test(pin)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'PIN Tidak Valid',
                        text: 'PIN harus terdiri dari 6 digit angka.',
                    });
                    $('#pin_admin').focus();
                    return;
                }

                var $btn = $(this);
                $btn.prop('disabled', true).html('Memverifikasi...');

                $.ajax({
                    type: 'post',
                    url: '/transfer/verify-pin',
                    data: { pin: pin },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        $btn.prop('disabled', false).html('Verifikasi');
                        $('#pinModal').modal('hide');
                        var $el = $('#saldoTersedia');
                        $el.html($el.data('saldo')).removeClass('saldo-hidden');
                        renderSaldoIcon('eye');
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html('Verifikasi');
                        var response = xhr.responseJSON;
                        var title = (response && response.title) ? response.title : 'Gagal';
                        var text = (response && response.text) ? response.text : 'PIN tidak valid.';
                        Swal.fire({
                            icon: 'error',
                            title: title,
                            text: text,
                        });
                        $('#pin_admin').val('');
                        $('#pin_admin').focus();
                    }
                });
            });

            $(document).on('click', '#refreshSaldo', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'get',
                    url: '/transfer/saldo',
                    success: function(response) {
                        var $el = $('#saldoTersedia');
                        $el.data('saldo', response.saldo);
                        if ($el.hasClass('saldo-hidden')) {
                            $el.html('Rp ••••••••••');
                        } else {
                            $el.html(response.saldo);
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Saldo berhasil diperbarui',
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Tidak dapat mengambil saldo Xendit.',
                        });
                    }
                });
            });
        });
    </script>
@endpush
