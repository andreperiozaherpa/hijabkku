@extends('layouts.main')
@section('main')
    <style>
        label.error {
            color: var(--danger) !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            margin-top: 4px !important;
            display: block !important;
            background: transparent !important;
        }
        .data-table-responsive-wrapper {
            padding: 10px 0;
        }
    </style>
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container mb-4">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Manajemen User</h1>
                        <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            <ul class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">User Manager</li>
                            </ul>
                        </nav>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12">
                    <section class="scroll-section" id="hover">
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-12 col-sm-5 col-lg-3 mb-2 mb-sm-0">
                                        <div class="d-inline-block w-100 search-input-container border border-separator bg-foreground search-sm rounded-md">
                                            <input class="form-control form-control-sm datatable-search" placeholder="Cari User..." data-datatable="#tbUser">
                                            <span class="search-magnifier-icon">
                                                <i data-acorn-icon="search"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-7 col-lg-9 text-end">
                                        <div class="d-inline-block">
                                            <button class="btn btn-icon btn-icon-start btn-primary btn-sm datatable-add shadow-sm me-1" type="button">
                                                <i data-acorn-icon="plus"></i>
                                                <span>Tambah User</span>
                                            </button>
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print me-1" type="button" title="Print Table">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                            <div class="d-inline-block datatable-export me-1">
                                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown-toggle" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3" title="Export">
                                                    <i data-acorn-icon="download"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                                    <button class="dropdown-item export-cvs" type="button">CSV</button>
                                                </div>
                                            </div>
                                            <div class="dropdown-as-select d-inline-block datatable-length">
                                                <button class="btn btn-outline-muted btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                                    10 Baris
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <a class="dropdown-item active" href="#">10 Baris</a>
                                                    <a class="dropdown-item" href="#">20 Baris</a>
                                                    <a class="dropdown-item" href="#">50 Baris</a>
                                                    <a class="dropdown-item" href="#">100 Baris</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="data-table-responsive-wrapper">
                                    <table class="data-table nowrap hover" id="tbUser" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
                                                <th class="text-muted text-small text-uppercase">Nama</th>
                                                <th class="text-muted text-small text-uppercase">Kode Toko</th>
                                                <th class="text-muted text-small text-uppercase">Role</th>
                                                <th class="text-muted text-small text-uppercase">Status</th>
                                                <th class="text-muted text-small text-uppercase">Shift</th>
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

    <!-- Modal Tambah/Edit User -->
    <div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-alternate"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValid" class="tooltip-label-end" novalidate>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="user"></i>
                            <input type="text" class="form-control" placeholder="Nama Lengkap" id="nama" name="nama" required>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="email"></i>
                            <input type="email" class="form-control" placeholder="Alamat Email" id="email" name="email" required>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="lock-on"></i>
                            <select class="form-control" id="role" name="role" data-placeholder="Pilih Role" required>
                                <option label="&nbsp;"></option>
                                <option value="admin">admin</option>
                                <option value="gudang">gudang</option>
                                <option value="kasir">kasir</option>
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="home-garage"></i>
                            <select class="form-control" id="namaToko" name="namaToko" data-placeholder="Pilih Cabang Toko" required>
                                <option label="&nbsp;"></option>
                                @foreach ($toko as $t)
                                    <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="activity"></i>
                            <select class="form-control" id="status" name="status" data-placeholder="Status Akun" required>
                                <option label="&nbsp;"></option>
                                <option value="on">Aktif (On)</option>
                                <option value="off">Nonaktif (Off)</option>
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="clock"></i>
                            <select class="form-control" id="shift" name="shift" data-placeholder="Shift Kerja" required>
                                <option label="&nbsp;"></option>
                                <option value="0">Semua Shift</option>
                                <option value="1">Pagi</option>
                                <option value="2">Sore</option>
                            </select>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="lock-off"></i>
                            <input type="password" class="form-control" placeholder="Kata Sandi" id="password" name="password" required>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="lock-off"></i>
                            <input type="password" class="form-control" placeholder="Konfirmasi Kata Sandi" id="confirm_password" name="confirm_password" required>
                        </div>

                        <div class="mt-4 pt-2 border-top text-end">
                            <button type="button" class="btn btn-outline-muted btn-sm me-1" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="simpan btn btn-primary btn-sm"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Password -->
    <div class="modal fade" id="userPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-alternate"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValids" class="tooltip-label-end" novalidate>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="email"></i>
                            <input type="email" class="form-control" placeholder="Email" id="changeEmail" name="changeEmail" readonly required>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="lock-off"></i>
                            <input type="password" class="form-control" placeholder="Kata Sandi Baru" id="changePassword" name="changePassword" required>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="lock-off"></i>
                            <input type="password" class="form-control" placeholder="Konfirmasi Kata Sandi Baru" id="changeConfirmPassword" name="changeConfirmPassword" required>
                        </div>

                        <div class="mt-4 pt-2 border-top text-end">
                            <button type="button" class="btn btn-outline-muted btn-sm me-1" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="simpan btn btn-primary btn-sm"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('#namaToko').select2({
            placeholder: '',
            dropdownParent: $('#userModal')
        });
        $('#role').select2({
            placeholder: '',
            dropdownParent: $('#userModal')
        });
        $('#status').select2({
            placeholder: '',
            dropdownParent: $('#userModal')
        });
        $('#shift').select2({
            placeholder: '',
            dropdownParent: $('#userModal')
        });

        $(document).ready(function() {
            function ajaxData(method, url, data) {
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    success: function(response) {
                        if (method == 'post') {
                            Swal.fire({
                                icon: response.icon,
                                title: response.title,
                                text: response.text,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            if (response.icon == 'success') {
                                $('#userModal').modal('hide');
                                $('#userPasswordModal').modal('hide');
                                $('#formValid')[0].reset();
                                $('#formValids')[0].reset();
                            }
                            $('#tbUser').DataTable().ajax.reload(null, false);
                        } else if (method == 'get') {
                            getData(response);
                        }
                    }
                });
            }

            function getData(params) {
                $('#nama').val(params.data.name);
                $('#email').val(params.data.email).prop('disabled', true);
                $('#role').val(params.data.role).trigger('change');
                $('#namaToko').val(params.data.kode_toko).trigger('change');
                $('#status').val(params.data.status).trigger('change');
                $('#shift').val(params.data.shift).trigger('change');

                $('#changeEmail').val(params.data.email);
            }

            $('#tbUser').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                length: 10,
                pageLength: 10,
                buttons: ['copy', 'excel', 'csv', 'print'],
                order: [
                    [0, "asc"]
                ],
                responsive: true,
                ajax: '/user/show',
                columns: [{
                        'data': null,
                        'sortable': false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1
                        },

                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'kode_toko',
                    },
                    {
                        data: 'role',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'shift',
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

            // Bind Custom Datatable Search
            $('.datatable-search').on('keyup', function() {
                $('#tbUser').DataTable().search($(this).val()).draw();
            });

            // Bind Custom Datatable Length Dropdown
            $('.datatable-length .dropdown-item').on('click', function(e) {
                e.preventDefault();
                $('.datatable-length .dropdown-item').removeClass('active');
                $(this).addClass('active');
                const text = $(this).text();
                const length = parseInt(text);
                $('.datatable-length button').text(text);
                $('#tbUser').DataTable().page.len(length).draw();
            });

            // Bind Custom Datatable Exports
            $('.export-copy').on('click', function() {
                $('#tbUser').DataTable().button('.buttons-copy').trigger();
            });
            $('.export-excel').on('click', function() {
                $('#tbUser').DataTable().button('.buttons-excel').trigger();
            });
            $('.export-cvs').on('click', function() {
                $('#tbUser').DataTable().button('.buttons-csv').trigger();
            });
            $('.datatable-print').on('click', function() {
                $('#tbUser').DataTable().button('.buttons-print').trigger();
            });

            $("#formValid").validate();
            $("#formValids").validate();

            // Tambah Data Action
            $(document).on('click', '.datatable-add', function() {
                $('#formValid')[0].reset();
                $('#email').prop('disabled', false);
                $('#password').prop('disabled', false).parent().show();
                $('#confirm_password').prop('disabled', false).parent().show();
                $('#password').prop('required', true);
                $('#confirm_password').prop('required', true);
                $('#role').val(null).trigger('change');
                $('#namaToko').val(null).trigger('change');
                $('#status').val(null).trigger('change');
                $('#shift').val(null).trigger('change');

                $('#userModal').modal('show');
                $('.modal-title').html('Tambah User Baru');
                $('.simpan').html('Simpan');
            });

            // Ubah Password Action
            $(document).on('click', '.password', function() {
                $('#formValids')[0].reset();
                $('#userPasswordModal').modal('show');
                $('.modal-title').html('Ubah Password User');
                $('.simpan').html('Ubah');
                var kode = $(this).attr('data-kode');
                ajaxData('get', '/user/edit', { kode: kode });
            });

            // Edit Data Action
            $(document).on('click', '.edit', function() {
                $('#formValid')[0].reset();
                $('#email').prop('disabled', true);
                $('#password').prop('disabled', true).parent().hide();
                $('#confirm_password').prop('disabled', true).parent().hide();
                $('#password').prop('required', false);
                $('#confirm_password').prop('required', false);

                var kode = $(this).attr('data-kode');
                $('#userModal').modal('show');
                $('.modal-title').html('Edit Data User');
                $('.simpan').html('Edit');
                
                ajaxData('get', '/user/edit', { kode: kode });
            });

            // Form Submit Event Handlers
            $('#formValid').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    var cekButton = $('.simpan').html();
                    var nama = $('#nama').val();
                    var email = $('#email').val();
                    var role = $('#role').val();
                    var namaToko = $('#namaToko').val();
                    var status = $('#status').val();
                    var shift = $('#shift').val();
                    var password = $('#password').val();
                    var confirm_password = $('#confirm_password').val();

                    var data = {
                        nama: nama,
                        email: email,
                        role: role,
                        namaToko: namaToko,
                        status: status,
                        shift: shift,
                        password: password,
                        confirm_password: confirm_password,
                    };

                    if (cekButton == 'Simpan') {
                        ajaxData('post', '/user/store', data);
                    } else if (cekButton == 'Edit') {
                        ajaxData('post', '/user/update', data);
                    }
                }
            });

            $('#formValids').on('submit', function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    var changePassword = $('#changePassword').val();
                    var changeConfirmPassword = $('#changeConfirmPassword').val();
                    var changeEmail = $('#changeEmail').val();
                    var datas = {
                        params: 'password',
                        changeEmail: changeEmail,
                        changePassword: changePassword,
                        changeConfirmPassword: changeConfirmPassword,
                    };
                    ajaxData('post', '/user/update', datas);
                }
            });

            // Delete Action with native Pre-Confirmation
            $(document).on('click', '.destroy', function() {
                var kode = $(this).attr('data-kode');
                Swal.fire({
                    title: 'Hapus User?',
                    text: 'Tindakan ini akan menghapus akun user secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        ajaxData('post', '/user/destroy', { kode: kode });
                    }
                });
            });

            // Status Toggle Switch Handler
            $(document).on('change', '.status-toggle', function() {
                var toggle = $(this);
                var kode = toggle.attr('data-kode');

                $.ajax({
                    type: 'POST',
                    url: '/user/toggle-status',
                    data: { kode: kode, _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        var toastIcon = response.icon === 'success' ? '✅' : '❌';
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: response.icon,
                            title: response.text,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        });

                        if (response.icon !== 'success') {
                            // Revert the toggle if failed (e.g., self-deactivation blocked)
                            toggle.prop('checked', !toggle.prop('checked'));
                        }
                    },
                    error: function() {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Terjadi kesalahan', timer: 2000, showConfirmButton: false });
                        toggle.prop('checked', !toggle.prop('checked'));
                    }
                });
            });

        });
    </script>
@endpush

