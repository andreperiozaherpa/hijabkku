@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Stock Toko</h1>
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
                                    <div class="col-12 col-sm-12 col-lg-12 col-xxl-12 mb-2">
                                        <div class="form-floating w-100">
                                            <select class="select-floating" id="selectFloating">
                                                <option label="&nbsp;"></option>
                                                @if (Auth::user()->role == 'admin')
                                                    <option value="semua">Semua</option>
                                                @endif
                                                @foreach ($toko as $t)
                                                    <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                                @endforeach
                                            </select>
                                            <label>Pilih Toko</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-lg-12 col-xxl-12 mb-2">
                                        <div class="form-floating w-100">
                                            <select class="select-floating" id="select2Basic">
                                                <option label="&nbsp;"></option>
                                                @if (Auth::user()->role == 'admin')
                                                    <option value="semua">Semua</option>
                                                @endif
                                                @foreach ($kasir as $k)
                                                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                                                @endforeach
                                            </select>
                                            <label>Pilih Kasir</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-lg-12 col-xxl-12 mb-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Input Tanggal" id="getDates">
                                            <span class="input-group-text">
                                                <i data-acorn-icon="calendar" class="text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-lg-12 col-xxl-12 mb-2">
                                        <div class="btn-group w-100">
                                            <button class="rekap btn btn-primary mb-1">Hari</button>
                                            <button class="rekap btn btn-primary mb-1">Bulan</button>
                                            <button class="rekap btn btn-primary mb-1">Tahun</button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-1">
                                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#tbDaftarTransaksi">
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
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#tbDaftarTransaksi">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                            <div class="d-inline-block datatable-export" data-datatable="#tbDaftarTransaksi">
                                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                                    <i data-acorn-icon="download"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                                    <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                                </div>
                                            </div>
                                            <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#tbDaftarTransaksi">
                                                <button class="btn btn-outline-muted btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                                    10 Items
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">10 Items</a>
                                                    <a class="dropdown-item active" href="#">20 Items</a>
                                                    <a class="dropdown-item" href="#">50 Items</a>
                                                    <a class="dropdown-item" href="#">100 Items</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="data-table-responsive-wrapper">
                                    <table class="data-table nowrap hover" id="tbDaftarTransaksi" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
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

    <div class="modal fade" id="modalDetailTransaksi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4 m-0 text-center">
                    <div class="w-100">
                        <h3 id="invoiceTitle"></h3>
                        <div id="tokoTitle"></div>
                        <div id="nameTitle"></div>
                        <div id="dateTitle"></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody id="dataTransaksi">
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 float-end">
                        <button type="button" class="closed btn btn-muted">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRekapData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4 m-0 text-center">

                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody id="dataSemuaTransaksi">
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 float-end">
                        <button type="button" class="closed btn btn-muted">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('#tbDaftarTransaksi').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                length: 10,
                pageLength: 20,
                buttons: ['copy', 'excel', 'csv', 'print'],
                order: [
                    [0, "asc"]
                ],
                responsive: true,
                ajax: '/transaksi/daftar/show',
                columns: [{
                        'data': null,
                        'sortable': false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1
                        },

                    },
                    {
                        data: 'Keterangan',
                    },
                    {
                        data: 'aksi',
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

            function ajaxData(method, url, data, param) {
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    success: function(response) {
                        if (param == 'detail') {
                            getData(response)
                            return response
                        } else if (param == 'all') {
                            getDataAllTime(response)
                            return response
                        }
                        // $('#tbStockInOut').DataTable().ajax.reload(null, false);
                    }
                });
            }

            function rupiah(param) {
                var bilangan = param;
                var number_string = bilangan.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
            }

            function getData(res) {
                console.log(res.data);
                $('#invoiceTitle').text('Invoice ' + res.data[0].kode_invoice)
                $('#nameTitle').text('Kasir ' + res.username)
                $('#dateTitle').text(res.tanggal)
                $('#tokoTitle').text(res.toko)
                $.each(res.data, function(index, value) {
                    $('#dataTransaksi').append(
                        `<tr>
                            <td scope="col" class="dataBarang">` + value.nama_barang + `</td>
                            <td scope="col" class="dataBarang">` + value.jumlah + `</td>
                            <td scope="col" class="dataBarang"> ` + rupiah(value.harga) + `</td>
                            <td scope="col" class="dataBarang"> ` + rupiah(value.harga_total) + `</td>
                        </tr>`
                    );
                });
                $('#dataTransaksi').append(
                    `<tr>
                        <td class="fw-bold text-center dataBarang" colspan="2" scope="col">Pembayaran</td>
                        <td colspan="3" scope="col" class="dataBarang text-uppercase">` + res.metode + `</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-center dataBarang" colspan="2" scope="col">Total Harga</td>
                        <td colspan=3" scope="col" class="dataBarang">Rp. ` + rupiah(res.total_harga) + `</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-center dataBarang" colspan="2" scope="col">Tunai</td>
                        <td colspan=3" scope="col" class="dataBarang">Rp. ` + rupiah(res.pembayaran) + `</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-center dataBarang" colspan="2" scope="col">Kembali</td>
                        <td colspan=3" scope="col" class="dataBarang">Rp. ` + rupiah(res.kembalian) + `</td>
                    </tr>
                    `
                );
            }

            // function getDataAllTime(res) {
            //     function sumData(params) {
            //         var sum = 0;
            //         $('.' + params).each(function() {
            //             var combat = $(this).text().replaceAll('.', '');
            //             if (!isNaN(combat) && combat.length !== 0) {
            //                 sum += parseFloat(combat);
            //             }
            //         });
            //         return sum;
            //     }

            //     if (res.role == 'admin') {
            //         $.each(res.data, function(index, value) {
            //             $('#dataSemuaTransaksi').append(
            //                 `<tr>
        //                     <td scope="col" class="dataBarangAll">` + value.user_name + `</td>
        //                     <td scope="col" class="dataBarangAll">` + value.nama_barang + `</td>
        //                     <td scope="col" class="dataBarangAll">` + value.total_jumlah + `</td>
        //                     <td scope="col" class="dataBarangAll hargaJual"> ` + rupiah(value.harga) + `</td>
        //                     <td scope="col" class="dataBarangAll hargaBeli" hidden> ` + rupiah(value.harga_beli) + `</td>
        //                     <td scope="col" class="dataBarangAll hargaTotal"> ` + rupiah(value.total_jumlah *
            //                     value.harga) + `</td>
        //                     <td scope="col" class="dataBarangAll hargaBeliTotal" hidden> ` + rupiah(value
            //                     .total_jumlah *
            //                     value.harga_beli) + `</td>
        //                 </tr>`
            //             );
            //         });
            //         $('#dataSemuaTransaksi').append(
            //             `<tr>
        //                 <td colspan="2" scope="col" class="dataBarangAll fw-bold text-center">Bruto</td>
        //                 <td colspan="3" scope="col" class="dataBarangAll">Rp. ` + rupiah(sumData('hargaTotal')) + `</td>
        //             </tr>
        //             <tr>
        //                 <td colspan="2" scope="col" class="dataBarangAll fw-bold text-center">Keuntungan </td>
        //                 <td colspan="3" scope="col" class="dataBarangAll">Rp. ` + rupiah(sumData('hargaTotal') -
            //                 sumData('hargaBeliTotal')) + `</td>
        //             </tr>`
            //         );
            //     } else {
            //         $.each(res.data, function(index, value) {
            //             $('#dataSemuaTransaksi').append(
            //                 `<tr>
        //                     <td scope="col" class="dataBarangAll">` + value.user_name + `</td>
        //                     <td scope="col" class="dataBarangAll">` + value.nama_barang + `</td>
        //                     <td scope="col" class="dataBarangAll">` + value.total_jumlah + `</td>
        //                     <td scope="col" class="dataBarangAll"> ` + rupiah(value.harga) + `</td>
        //                     <td scope="col" class="dataBarangAll hargaTotal"> ` + rupiah(value.total_jumlah *
            //                     value.harga) + `</td>
        //                 </tr>`
            //             );
            //         });
            //         $('#dataSemuaTransaksi').append(
            //             `<tr>
        //                 <td colspan="2" scope="col" class="dataBarangAll fw-bold text-center">Bruto</td>
        //                 <td colspan="3" scope="col" class="dataBarangAll">Rp. ` + rupiah(sumData('hargaTotal')) + `</td>
        //             </tr>`
            //         );
            //     }
            // }

            $('#getDates').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });

            $(document).on('click', '.detailTransaksi', function() {
                var kodeInvoice = $(this).attr('data-invoice');
                $('#modalDetailTransaksi').modal('show')
                var data = {
                    invoice: kodeInvoice,
                }
                ajaxData('get', '/transaksi/daftar/show_detail', data, 'detail');
            });


            $(document).on('click', '.rekap', function() {
                if (!($('#selectFloating').val() == '') && !($('#select2Basic').val() == '')) {
                    // if ($(this).text() == 'Hari') {
                    //     $('#modalRekapData').modal('show')
                    // }
                    if (!$('#getDates').val()) {
                        $('#getDates').datepicker('setDate', new Date());
                    }
                    var getDates = $('#getDates').val();
                    var getFormat = $(this).text();
                    var getToko = $('#selectFloating').val();
                    var getUser = $('#select2Basic').val();
                    var data = {
                        tanggal: getDates,
                        format: getFormat,
                        toko: getToko,
                        users: getUser,
                    }

                    if (getFormat == 'Tahun') {
                        window.open(
                            "https://hijabkku.com/transaksi/daftar/edit?tanggal=" + getDates +
                            "&format=" + getFormat + "&toko=" + getToko + "&users=" + getUser + "",
                            "_blank"
                        );
                    } else if (getFormat == 'Bulan') {
                        window.open(
                            "https://hijabkku.com/transaksi/daftar/edit?tanggal=" + getDates +
                            "&format=" + getFormat + "&toko=" + getToko + "&users=" + getUser + "",
                            "_blank"
                        );
                    } else {
                        // ajaxData('get', '/transaksi/daftar/edit', data, 'all');
                        window.open(
                            "https://hijabkku.com/transaksi/daftar/edit?tanggal=" + getDates +
                            "&format=" + getFormat + "&toko=" + getToko + "&users=" + getUser + "",
                            "_blank"
                        );
                    }
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 1500,
                        icon: 'error',
                        title: 'Harap pilih Toko',
                    })
                }
            });

            $(document).on('click', '.closed', function() {
                $('#modalDetailTransaksi').modal('hide')
                $('#modalRekapData').modal('hide')
                $('.dataBarang').remove();
                $('.dataBarangAll').remove();
            });
        });
    </script>
@endpush
