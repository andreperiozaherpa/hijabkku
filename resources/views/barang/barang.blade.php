@extends('layouts.main')
@section('main')
    <style>
        .error {
            color: #F00;
            background-color: #FFF;
        }
    </style>
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Daftar Barang</h1>
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
                                <div class="row">
                                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-1">
                                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#tbToko">
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
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-add" type="button" data-datatable="#tbToko">
                                                <i data-acorn-icon="plus"></i>
                                            </button>
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#tbToko">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                            <div class="d-inline-block datatable-export" data-datatable="#tbToko">
                                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                                    <i data-acorn-icon="download"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                                    <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                                </div>
                                            </div>
                                            <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#tbToko">
                                                <button class="btn btn-outline-muted btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                                    20 Items
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">10 Items</a>
                                                    <a class="dropdown-item active" href="#">20 Items</a>
                                                    <a class="dropdown-item" href="#">50 Items</a>
                                                    <a class="dropdown-item" href="#">100 Items</a>
                                                    <a class="dropdown-item" href="#">Semua Items</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="data-table-responsive-wrapper">
                                    <table class="data-table nowrap hover" id="tbToko" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
                                                {{-- <th class="text-muted text-small text-uppercase">Kode</th> --}}
                                                <th class="text-muted text-small text-uppercase">Jenis Barang</th>
                                                <th class="text-muted text-small text-uppercase">Nama Barang</th>
                                                <th class="text-muted text-small text-uppercase">Beli</th>
                                                <th class="text-muted text-small text-uppercase">Harga</th>
                                                <th class="text-muted text-small text-uppercase">Grosir</th>
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

    <div class="modal fade indexModal" id="dataBarangModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValid" class="tooltip-label-end" novalidate>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="barcode"></i>
                            <input type="text" class="form-control" placeholder="Kode" id="kode" name="kode" readonly required>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="loaf"></i>
                            <select class="form-control select2Basic" id="areaBarang" name="areaBarang" data-placeholder="Area Barang" required>
                                <option label="&nbsp;"></option>
                                <option value="TBB">TBB</option>
                                <option value="MSJ">MSJ</option>
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="loaf"></i>
                            <select class="form-control select2Basic" id="jenisBarang" name="jenisBarang" data-placeholder="Jenis Barang">
                                <option label="&nbsp;"></option>
                                @foreach ($jenis as $j)
                                    <option value="{{ $j->jenis }}">{{ $j->jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="loaf"></i>
                            <select class="form-control select2Basic" id="merekBarang" name="merekBarang" data-placeholder="Merek Barang">
                                <option label="&nbsp;"></option>
                                @foreach ($merek as $mr)
                                    <option value="{{ $mr->jenis }}">{{ $mr->jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="loaf"></i>
                            <select class="form-control select2Basic" id="modelBarang" name="modelBarang" data-placeholder="Model Barang">
                                <option label="&nbsp;"></option>
                                @foreach ($model as $md)
                                    <option value="{{ $md->jenis }}">{{ $md->jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="loaf"></i>
                            <select multiple="multiple" class="form-control select2Basic" id="bahanBarang" name="bahanBarang" data-placeholder="Bahan Barang">
                                <option label="&nbsp;"></option>
                                @foreach ($bahan as $bhn)
                                    <option value="{{ $bhn->jenis }}">{{ $bhn->jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="loaf"></i>
                            <select multiple="multiple" class="form-control select2Basic" id="variasiBarang" name="variasiBarang" data-placeholder="Variasi Barang">
                                <option label="&nbsp;"></option>
                                @foreach ($variasi as $vr)
                                    <option value="{{ $vr->jenis }}">{{ $vr->jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="loaf"></i>
                            <select class="form-control select2Basic" id="ukuranBarang" name="ukuranBarang" data-placeholder="Ukuran Barang">
                                <option label="&nbsp;"></option>
                                @foreach ($ukuran as $uk)
                                    <option value="{{ $uk->jenis }}">{{ $uk->jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="loaf"></i>
                            <select class="form-control select2Basic" id="packagingBarang" name="packagingBarang" data-placeholder="Packaging Barang" required>
                                <option label="&nbsp;"></option>
                                @foreach ($packaging as $pack)
                                    <option value="{{ $pack->jenis }}">{{ $pack->jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="list"></i>
                            <input type="text" class="form-control" placeholder="Nama Barang" id="namaBarang" name="namaBarang" required readonly>
                        </div>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="money"></i>
                            <input type="text" class="form-control" placeholder="Harga Beli/Modal Barang" id="hargaBeliBarang" name="hargaBeliBarang" required>
                        </div>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="money-bag"></i>
                            <input type="text" class="form-control" placeholder="Harga Jual Barang" id="hargaJualBarang" name="hargaJualBarang" required>
                        </div>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="bitcoin"></i>
                            <input type="text" class="form-control" placeholder="Harga Grosir Barang" id="hargaGrosirBarang" name="hargaGrosirBarang" required>
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
        // Your custom JavaScript...
        $(document).ready(function() {
            // $('#jenisBarang').select2({
            //     placeholder: '',
            //     dropdownParent: $('#dataBarangModal'),
            // });

            var hargaBeli = IMask(
                document.getElementById('hargaBeliBarang'), {
                    mask: 'num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.',
                        },
                    },
                });

            var hargaJual = IMask(
                document.getElementById('hargaJualBarang'), {
                    mask: 'num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.',
                        },
                    },
                });

            var hargaGrosir = IMask(
                document.getElementById('hargaGrosirBarang'), {
                    mask: 'num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.',
                        },
                    },
                });

            function ajaxData(method, url, data, params) {
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
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                            })
                            $('.closed').click();
                        } else if (method == 'get') {
                            getData(response)
                            return response
                        }
                        $('#tbToko').DataTable().ajax.reload(null, false);
                    }
                });
            }

            function getData(params) {
                $('#kode').val(params.data.kode);
                $('#jenisBarang').val(['val', params.data.jenis_barang]).trigger('change');
                $('#namaBarang').val(params.data.nama_barang);
                var hargaBeliBarang = params.data.harga_beli.replace('.', '');
                var hargaJualBarang = params.data.harga_jual.replace('.', '');
                var hargaGrosirBarang = params.data.harga_grosir.replace('.', '');
                hargaBeli.updateOptions({
                    mask: 'num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.'
                        }
                    }
                });
                hargaBeli.typedValue = hargaBeliBarang;

                hargaJual.updateOptions({
                    mask: 'num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.'
                        }
                    }
                });
                hargaJual.typedValue = hargaJualBarang;

                hargaGrosir.updateOptions({
                    mask: 'num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.'
                        }
                    }
                });
                hargaGrosir.typedValue = hargaGrosirBarang;
            }

            $('#tbToko').DataTable({
                // dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                pageLength: 20,
                buttons: ['copy', 'excel', 'csv', 'print'],
                order: [
                    [0, "asc"]
                ],
                responsive: true,
                ajax: '/manajemen/barang/data/show',
                columns: [{
                        'data': null,
                        'sortable': false,
                        'searchable': false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1
                        },

                    },
                    // {
                    //     data: 'kode',
                    //     sortable: false,
                    // },
                    {
                        data: 'jenis_barang',
                    },
                    {
                        data: 'nama_barang',
                        render: function(data, type, row) {
                            return '<div style="white-space:normal; word-wrap:break-word;">' + data + '</div>';
                        }
                    },
                    {
                        data: "{{ Auth::user()->role == 'admin' ? 'harga_beli' : 'harga_grosir' }}",
                        render: function(data, type, row) {
                            return '<div style="white-space:normal; word-wrap:break-word;">' + data + '</div>';
                        }
                    },
                    {
                        data: 'harga_jual',
                        render: function(data, type, row) {
                            return '<div style="white-space:normal; word-wrap:break-word;">' + data + '</div>';
                        }
                    },
                    {
                        data: 'harga_grosir',
                        render: function(data, type, row) {
                            return '<div style="white-space:normal; word-wrap:break-word;">' + data + '</div>';
                        }
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        orderable: false,
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

            $("#formValid").validate();

            $(document).on('click', '.datatable-add', function() {
                $('#dataBarangModal').modal('show');
                $('h5.modal-title').html('Add Data');
                $('.simpan').html('Simpan');

                // declare all characters
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                function generateString(length) {
                    let result = '';
                    const charactersLength = characters.length;
                    for (let i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }

                    return result.toLowerCase();
                }
                var kode = $('#kode').val('DB-' + generateString(4));
            });

            $(document).on('click', '.edit', function() {
                $('#dataBarangModal').modal('show');
                $('h5.modal-title').html('Edit Data');
                $('.simpan').html('Edit');
                var kode = $(this).attr('data-kode');
                var nama = $(this).attr('data-nama');
                console.log(nama);

                var data = {
                    kode: kode
                };
                ajaxData('get', '/manajemen/barang/data/edit', data);
            });

            $(document).on('click', '.closed', function() {
                $('#dataBarangModal').modal('hide');
                $('h5.modal-title').html('');
                $('#kode').val('');
                $('#jenisBarang').val(['val', 'Jenis Barang']).trigger('change');
                $('#namaBarang').val('');
                $('#hargaBeliBarang').val('');
                $('#hargaJualBarang').val('');
                $('#hargaGrosirBarang').val('');
            });

            $(document).on('click', '.simpan', function() {
                var cekButton = $('.simpan').html();
                var kode = $('#kode').val();
                var jenisBarang = $('#jenisBarang').val();
                var namaBarang = $('#namaBarang').val();
                var hargaBeliBarang = $('#hargaBeliBarang').val();
                var hargaJualBarang = $('#hargaJualBarang').val();
                var hargaGrosirBarang = $('#hargaGrosirBarang').val();
                var valid = $("#formValid").valid();

                var data = {
                    kode: kode,
                    jenis_barang: jenisBarang,
                    nama_barang: namaBarang,
                    harga_beli: hargaBeliBarang,
                    harga_jual: hargaJualBarang,
                    harga_grosir: hargaGrosirBarang,
                };

                if (valid == true) {
                    if (cekButton == 'Simpan') {
                        ajaxData('post', '/manajemen/barang/data/store', data);
                        $('#namaToko').val('');
                    } else if (cekButton == 'Edit') {
                        ajaxData('post', '/manajemen/barang/data/update', data);
                        $('#namaToko').val('');
                    } else {

                    }
                }
            });

            $(document).on('click', '.destroy', function() {
                var data = {
                    kode: $(this).attr('data-kode')
                }
                Swal.fire({
                    title: 'Yakin Akan Menghapus Data?',
                    text: "Anda Tidak Bisa Mengembalikan Data!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        ajaxData('post', '/manajemen/barang/data/destroy', data);
                    }
                })
            });

            $(document).on('change', '.select2Basic', function() {

                var areaBarang = $('#areaBarang').val()
                var jenisBarang = $('#jenisBarang').val()
                var merekBarang = $('#merekBarang').val()
                var modelBarang = $('#modelBarang').val()
                var bahanBarang = $('#bahanBarang').val()
                var variasiBarang = $('#variasiBarang').val()
                var ukuranBarang = $('#ukuranBarang').val()
                var packagingBarang = $('#packagingBarang').val()

                if (!((areaBarang == '') || (jenisBarang == '') || (merekBarang == '') || (modelBarang == '') || (bahanBarang == '') || (variasiBarang == '') || (ukuranBarang == '') || (packagingBarang == ''))) {
                    $('#namaBarang').val(areaBarang + ' ' +
                        jenisBarang + ' ' +
                        merekBarang + ' ' +
                        modelBarang + ' ' +
                        bahanBarang + ' ' +
                        '(' + variasiBarang + ')' + ' ' +
                        ukuranBarang + ' ' +
                        packagingBarang)
                }
            });

        });
    </script>
@endpush
