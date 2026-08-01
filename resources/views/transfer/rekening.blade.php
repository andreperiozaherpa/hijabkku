@extends('layouts.main')
@section('main')
    <style>
        .error {
            color: #F00;
            background-color: #FFF;
        }

        /* Customize Select2 dropdown option hover/highlight states */
        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary, #3454d1) !important;
            color: #fff !important;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .select2-results__option {
            padding: 8px 12px !important;
            border-radius: var(--border-radius-sm, 4px) !important;
            margin: 2px 4px !important;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .select2-container--bootstrap4 .select2-results__option[aria-selected="true"],
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: rgba(var(--primary-rgb, 52, 84, 209), 0.08) !important;
            color: var(--primary, #3454d1) !important;
        }

        .select2-dropdown {
            border-color: var(--separator, #e4e4e4) !important;
            border-radius: var(--border-radius-md, 8px) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden !important;
            padding: 4px 0 !important;
        }

        .filled .select2-container--bootstrap4 .select2-selection:hover,
        .filled .select2-container--default .select2-selection:hover {
            border-color: rgba(var(--primary-rgb, 52, 84, 209), 1) !important;
            background: initial !important;
        }
    </style>
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Rekening Client</h1>
                    </div>
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12">
                    <section class="scroll-section" id="hover">
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-1">
                                        <div
                                            class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                            <input class="form-control form-control-sm datatable-search"
                                                placeholder="Search" data-datatable="#tbRekening">
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
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-add"
                                                type="button" data-datatable="#tbRekening">
                                                <i data-acorn-icon="plus"></i>
                                            </button>
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print"
                                                type="button" data-datatable="#tbRekening">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="data-table-responsive-wrapper">
                                    <table class="data-table nowrap hover" id="tbRekening" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
                                                <th class="text-muted text-small text-uppercase">Nama Client</th>
                                                <th class="text-muted text-small text-uppercase">Bank</th>
                                                <th class="text-muted text-small text-uppercase">No Rekening</th>
                                                <th class="text-muted text-small text-uppercase">Atas Nama</th>
                                                <th class="text-muted text-small text-uppercase">Keterangan</th>
                                                <th class="text-muted text-small text-uppercase">Aksi</th>
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

    <div class="modal fade" id="rekeningModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formRekening" class="tooltip-label-end" novalidate data-no-spinner>
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="user"></i>
                            <input type="text" class="form-control" placeholder="Nama Client" id="nama_client"
                                name="nama_client" required>
                        </div>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="bank"></i>
                            <select class="form-control" id="channel_type" name="channel_type" required>
                                <option value="BANK">Bank</option>
                                <option value="EWALLET">E-Wallet</option>
                            </select>
                        </div>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="bank"></i>
                            <select class="form-control" id="bank_code" name="bank_code" required>
                                <option value="">Pilih Bank</option>
                                <option value="BCA" data-bank-name="Bank Central Asia">BCA - Bank Central Asia</option>
                                <option value="BNI" data-bank-name="Bank Negara Indonesia">BNI - Bank Negara Indonesia
                                </option>
                                <option value="BRI" data-bank-name="Bank Rakyat Indonesia">BRI - Bank Rakyat Indonesia
                                </option>
                                <option value="MANDIRI" data-bank-name="Bank Mandiri">MANDIRI - Bank Mandiri</option>
                                <option value="PERMATA" data-bank-name="Bank Permata">PERMATA - Bank Permata</option>
                                <option value="BSI" data-bank-name="Bank Syariah Indonesia">BSI - Bank Syariah Indonesia
                                </option>
                                <option value="CIMB" data-bank-name="CIMB Niaga">CIMB - CIMB Niaga</option>
                                <option value="DANAMON" data-bank-name="Bank Danamon">DANAMON - Bank Danamon</option>
                                <option value="MAYBANK" data-bank-name="Maybank Indonesia">MAYBANK - Maybank Indonesia
                                </option>
                                <option value="OCBC" data-bank-name="OCBC NISP">OCBC - OCBC NISP</option>
                                <option value="UOB" data-bank-name="UOB Indonesia">UOB - UOB Indonesia</option>
                                <option value="PANIN" data-bank-name="Bank Panin">PANIN - Bank Panin</option>
                                <option value="BTN" data-bank-name="Bank Tabungan Negara">BTN - Bank Tabungan Negara
                                </option>
                            </select>
                        </div>
                        <input type="hidden" id="bank_name" name="bank_name">
                        <div class="mb-3 filled">
                            <i data-acorn-icon="credit-card"></i>
                            <input type="text" class="form-control" placeholder="Nomor Rekening" id="account_number"
                                name="account_number" required>
                        </div>
                        <div class="mb-3 filled">
                            <i data-acorn-icon="user"></i>
                            <input type="text" class="form-control" placeholder="Atas Nama (Pemilik Rekening)"
                                id="account_holder_name" name="account_holder_name" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="filled position-relative form-group">
                                    <i data-acorn-icon="user"></i>
                                    <select class="form-control" id="recipient_type" name="recipient_type" required>
                                        <option value="INDIVIDUAL">INDIVIDUAL</option>
                                        <option value="BUSINESS">BUSINESS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="filled position-relative form-group">
                                    <i data-acorn-icon="user"></i>
                                    <select class="form-control" id="relationship" name="relationship" required>
                                        <option value="CUSTOMER">CUSTOMER</option>
                                        <option value="SUPPLIER">SUPPLIER</option>
                                        <option value="EMPLOYEE">EMPLOYEE</option>
                                        <option value="OWNSELF">OWNSELF</option>
                                        <option value="BUSINESS_PARTNER">BUSINESS_PARTNER</option>
                                        <option value="OTHER">OTHER</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="mb-3 filled">
                                    <i data-acorn-icon="pin"></i>
                                    <input type="text" class="form-control" placeholder="Kota" id="city"
                                        name="city">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3 filled">
                                    <i data-acorn-icon="pin"></i>
                                    <input type="text" class="form-control" placeholder="Alamat (Jalan)"
                                        id="street_line_1" name="street_line_1">
                                </div>
                            </div>
                        </div>
                        <div class="filled">
                            <textarea class="form-control" placeholder="Keterangan (opsional)" rows="2" id="keterangan"
                                name="keterangan"></textarea>
                            <i data-acorn-icon="pencil"></i>
                        </div>
                        <div class="mt-3 float-end">
                            <button type="button" class="closed btn btn-muted">Close</button>
                            <button type="button" class="simpan btn btn-primary"></button>
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
            $('#tbRekening').DataTable({
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
                ajax: '/transfer/rekening/show',
                columns: [{
                        'data': null,
                        'sortable': false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1
                        }
                    },
                    {
                        data: 'nama_client'
                    },
                    {
                        data: 'bank_code'
                    },
                    {
                        data: 'account_number'
                    },
                    {
                        data: 'account_holder_name'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ],
                sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                language: {
                    paginate: {
                        previous: '<i class="cs-chevron-left"></i>',
                        next: '<i class="cs-chevron-right"></i>',
                    },
                },
            });

            $("#formRekening").validate();

            $('#bank_code').select2({
                placeholder: 'Cari / Pilih Bank',
                dropdownParent: $('#rekeningModal'),
                allowClear: true,
            });

            var bankOptions = [{
                    value: 'BCA',
                    name: 'Bank Central Asia'
                },
                {
                    value: 'BNI',
                    name: 'Bank Negara Indonesia'
                },
                {
                    value: 'BRI',
                    name: 'Bank Rakyat Indonesia'
                },
                {
                    value: 'MANDIRI',
                    name: 'Bank Mandiri'
                },
                {
                    value: 'PERMATA',
                    name: 'Bank Permata'
                },
                {
                    value: 'BSI',
                    name: 'Bank Syariah Indonesia'
                },
                {
                    value: 'CIMB',
                    name: 'CIMB Niaga'
                },
                {
                    value: 'DANAMON',
                    name: 'Bank Danamon'
                },
                {
                    value: 'MAYBANK',
                    name: 'Maybank Indonesia'
                },
                {
                    value: 'OCBC',
                    name: 'OCBC NISP'
                },
                {
                    value: 'UOB',
                    name: 'UOB Indonesia'
                },
                {
                    value: 'PANIN',
                    name: 'Bank Panin'
                },
                {
                    value: 'BTN',
                    name: 'Bank Tabungan Negara'
                },
            ];

            var ewalletOptions = [{
                    value: 'DANA',
                    name: 'DANA'
                },
                {
                    value: 'GOPAY',
                    name: 'GoPay'
                },
                {
                    value: 'OVO',
                    name: 'OVO'
                },
                {
                    value: 'LINKAJA',
                    name: 'LinkAja'
                },
                {
                    value: 'SHOPEEPAY',
                    name: 'ShopeePay'
                },
            ];

            function setChannelOptions(channelType) {
                var options = channelType === 'EWALLET' ? ewalletOptions : bankOptions;
                var html = '<option value="">Pilih ' + (channelType === 'EWALLET' ? 'E-Wallet' : 'Bank') +
                    '</option>';
                $.each(options, function(index, opt) {
                    html += '<option value="' + opt.value + '" data-bank-name="' + opt.name + '">' + opt
                        .value + ' - ' + opt.name + '</option>';
                });
                $('#bank_code').html(html).trigger('change');
                $('#bank_name').val('');
            }

            $('#channel_type').on('change', function() {
                setChannelOptions($(this).val());
            });

            $('#bank_code').on('change', function() {
                var namaBank = $(this).find(':selected').data('bank-name');
                $('#bank_name').val(namaBank || '');
            });

            function isiForm(data) {
                $('#id').val(data.id);
                $('#nama_client').val(data.nama_client);
                $('#channel_type').val(data.channel_type || 'BANK');
                setChannelOptions(data.channel_type || 'BANK');
                $('#bank_code').val(data.bank_code).trigger('change');
                $('#bank_name').val(data.bank_name || $('#bank_code').find(':selected').data('bank-name') || '');
                $('#account_number').val(data.account_number);
                $('#account_holder_name').val(data.account_holder_name);
                $('#recipient_type').val(data.recipient_type);
                $('#relationship').val(data.relationship);
                $('#city').val(data.city);
                $('#street_line_1').val(data.street_line_1);
                $('#keterangan').val(data.keterangan);
            }

            function resetForm() {
                $('#id').val('');
                $('#nama_client').val('');
                $('#channel_type').val('BANK');
                setChannelOptions('BANK');
                $('#bank_name').val('');
                $('#account_number').val('');
                $('#account_holder_name').val('');
                $('#recipient_type').val('INDIVIDUAL');
                $('#relationship').val('CUSTOMER');
                $('#city').val('');
                $('#street_line_1').val('');
                $('#keterangan').val('');
            }

            $(document).on('click', '.datatable-add', function() {
                resetForm();
                $('#rekeningModal').modal('show');
                $('.modal-title').html('Tambah Rekening Client');
                $('.simpan').html('Simpan');
            });

            $(document).on('click', '.edit', function() {
                $('#rekeningModal').modal('show');
                $('.modal-title').html('Edit Rekening Client');
                $('.simpan').html('Edit');
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'get',
                    url: '/transfer/rekening/edit',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        isiForm(response.data);
                    }
                });
            });

            $(document).on('click', '.closed', function() {
                $('#rekeningModal').modal('toggle');
                resetForm();
            });

            $(document).on('click', '.simpan', function() {
                var cekButton = $('.simpan').html();
                var valid = $("#formRekening").valid();
                if (valid != true) {
                    return;
                }

                var data = {
                    id: $('#id').val(),
                    nama_client: $('#nama_client').val(),
                    bank_code: $('#bank_code').val(),
                    bank_name: $('#bank_name').val(),
                    account_number: $('#account_number').val(),
                    account_holder_name: $('#account_holder_name').val(),
                    recipient_type: $('#recipient_type').val(),
                    relationship: $('#relationship').val(),
                    channel_type: $('#channel_type').val(),
                    city: $('#city').val(),
                    street_line_1: $('#street_line_1').val(),
                    keterangan: $('#keterangan').val(),
                };

                var url = cekButton == 'Simpan' ? '/transfer/rekening/store' : '/transfer/rekening/update';

                $.ajax({
                    type: 'post',
                    url: url,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: response.icon,
                            title: response.title,
                            text: response.text,
                        });
                        $('.closed').click();
                        $('#tbRekening').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response && response.message ? response.message :
                                'Terjadi kesalahan.',
                        });
                    }
                });
            });

            $(document).on('click', '.destroy', function() {
                var id = $(this).attr('data-id');
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Akan Dihapus',
                    text: 'Anda yakin akan menghapus rekening client ini?',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }
                    $.ajax({
                        type: 'post',
                        url: '/transfer/rekening/destroy',
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: response.icon,
                                title: response.title,
                                text: response.text,
                            });
                            $('#tbRekening').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            var response = xhr.responseJSON;
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response && response.text ? response
                                    .text : 'Terjadi kesalahan.',
                            });
                        }
                    });
                });
            });
        });
    </script>
@endpush
