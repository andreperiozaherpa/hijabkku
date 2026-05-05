@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Penjualan</h1>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card mb-2">
                        <div class="card-body h-100">
                            <div class="row">
                                <div class="col-6 col-md-6 col-lg-5">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td>Nama Toko</td>
                                                <td>:</td>
                                                <td> <strong>{{ $data_toko->nama_toko }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Kasir</td>
                                                <td>:</td>
                                                <td> <strong>{{ Auth::user()->name }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-6 col-md-6 col-lg-3">
                                    <div class="w-100 mb-3">
                                        <label class="form-label mt-2">Metode Pembayaran</label>
                                        <select id="jenisPembayaran">
                                            <option value="umum">Umum</option>
                                            <option value="grosir">Grosir</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-3">
                                    <div id="basked" data-bs-toggle="modal" data-bs-target="#baskedModal" class="card btn btn-primary bg-primary p-0 m-0">
                                        <span class="baskeds badge rounded-pill bg-quaternary me-1 position-absolute e-n4 t-2 z-index-1 fs-4"></span>
                                        <div class="card-body">
                                            <i data-acorn-icon="basket" data-acorn-size="50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="d-inline-block float-md-start mb-2 search-input-container w-100 shadow bg-foreground">
                        <input class="form-control w-100 fs-2" placeholder="Cari Jenis/Merek/Bahan" id="cariProduk" name="cariProduk">
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="d-inline-block float-md-start mb-2 search-input-container w-100 shadow bg-foreground">
                        <input class="form-control w-100 fs-2" placeholder="Cari Model/Variasi/Packaging" id="cariProdukOptional" name="cariProdukOptional">
                    </div>
                </div>
                <div class="stock row p-0 m-0"></div>
            </div>
            <!-- Content End -->
        </div>
    </main>
    <div class="modal fade" id="baskedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <table class="transaksi table table-borderless">
                        <thead>
                            <tr>
                                <td colspan="5" class="text-center" id="methodPembayaran"><strong></strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-4"><strong>Nama Barang</strong></td>
                                <td class="counter col-1">Jumlah</td>
                                <td class="col-2">Metode</td>
                                <td class="col-2">Harga</td>
                                <td class="totalJual col-3">Total Harga</td>
                                <td class="col-2">Kurangi</td>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <table class="totals table table-borderless">
                        <thead>
                            <tr>
                                <td class="col-6"></td>
                                <td class="counter col-1"></td>
                                <td class="col-2"></td>
                                <td class="totalJual col-3"></td>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                    </table>
                    <form id="formValid" class="tooltip-label-end" novalidate>
                        <div class="mb-2 input-group">
                            <input id="jumlahUang" type="text" class="form-control" placeholder="Jumlah uang" required>
                            <button class="btn btn-outline-secondary" type="button" id="button_pas">Uang Pas</button>
                        </div>
                        <div>
                            <input id="kembalian" type="text" class="form-control" readonly required>
                        </div>
                    </form>
                    <div class="mt-3 float-end">
                        <button type="button" class="closed btn btn-muted">Close</button>
                        <button type="button" class="simpan btn btn-primary">Bayar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('#jenisPembayaran').select2({
                placeholder: '',
            });

            function ajaxQuery(method, url, data) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    success: function(response) {
                        dataStock(response)
                        if (method == 'post') {
                            simpan(response)
                        }
                        // return response
                    }
                });
            }

            function simpan(res) {
                console.log(res.cek_data)
                Swal.fire({
                    position: 'center',
                    icon: res.icon,
                    title: res.cek_data,
                    showConfirmButton: true,
                    timer: 2500
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    location.reload();
                })
            };

            function dataStock(params) {
                $('.stock').html('');
                $.each(params.stock, function(index, value) {

                    $('.stock').prepend(`
                    <div class="col-12 col-md-4 col-lg-3 ">
                        <div class="card mb-2">
                            <button id="` + value.id +
                        `" class="selected btn btn-transparent" data-nama_barang="` + value
                        .nama_barang +
                        `" data-kode_barang="` + value.kode_barang + `" data-kode_toko="` + value
                        .kode_toko + `" data-harga_grosir="` + value.harga_grosir +
                        `" data-harga_jual="` + value.harga_jual +
                        `">
                                <div class="row">
                                    <div class="col sw-9 sh-10">
                                        <div
                                            class="card-body p-0 m-0 d-flex flex-column h-100 justify-content-center">
                                            <div class="d-flex flex-column p-0 m-0">
                                                <div style="white-space: normal;" class="text-alternate">` +
                        value.nama_barang + `</div>
                                                <div class="sisaStock text-small text-muted" data-jumlah="` + (value
                            .jumlah - value
                            .terjual) + `">Stock : ` + (value
                            .jumlah - value
                            .terjual) + `</div>
                                                <div class="text-alternate">E :` + value.harga_jual + ` G :` + value.harga_grosir + `</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                    `);
                });
            }

            ajaxQuery('get', '/transaksi/penjualan/create', {
                param: 'all'
            })

            // $('#cariProduk').keyup(function(e) {
            //     var searchKey = $(this).val();
            //     var data = {
            //         key: searchKey
            //     }
            //     ajaxQuery('get', '/transaksi/penjualan/create', data)
            // });

            $('input').keyup(function(e) {
                var searchKey1 = $('input[name=cariProduk]').val();
                var searchKey2 = $('input[name=cariProdukOptional]').val();
                console.log(searchKey1, searchKey2);

                var data = {
                    key1: searchKey1,
                    key2: searchKey2
                }
                ajaxQuery('get', '/transaksi/penjualan/create', data)
            });

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

            var i = 1;
            var c = 1;
            var x = 1;
            $(document).on('click', '.selected', function() {
                var id = $(this).attr('id');
                var nama_barang = $(this).attr('data-nama_barang');
                var kode_barang = $(this).attr('data-kode_barang');
                var kode_toko = $(this).attr('data-kode_toko');
                // var sisaStock = $(this).find('.sisaStock').attr('data-jumlah');
                var sisaStock = $(this).find('.sisaStock').text().replaceAll('Stock : ', '');
                var transaksiCount = $('.transaksi #transaksi_' + kode_barang + '').length
                var metode_pembayaran = $('#jenisPembayaran').val();
                if (metode_pembayaran == 'grosir') {
                    var harga_jual = $(this).attr('data-harga_grosir');
                } else {
                    var harga_jual = $(this).attr('data-harga_jual');
                }
                // $('#jenisPembayaran').prop('disabled', true);
                if (sisaStock > 0) {
                    $(this).find('.sisaStock').text('Stock : ' + (sisaStock - i));
                    // $('.baskeds').text(' ');
                    var countItem = $('.baskeds').text();
                    if (countItem == '') {
                        var v = 1;
                    } else {
                        var v = +countItem + 1;
                    }
                    $('.baskeds').text(v);
                    if (transaksiCount < 1) {
                        $('.transaksi tbody').append(`
                        <tr id="transaksi_` + kode_barang + `">
                            <td class="col-4">` + nama_barang + `</td>
                            <td class="counter col-1">` + c + `</td>
                            <td class="metode col-2">` + metode_pembayaran + `</td>
                            <td class="hargaJual col-2">` + harga_jual + `</td>
                            <td class="totalJual col-3">` + harga_jual + `</td>
                            <td class="col-2"><button type="button" data-kode_barang="` + kode_barang + `" class="kurangi btn btn-danger px-3 py-1">&#8722;</button></td>
                        </tr>
                        `);
                    } else {
                        var counter = $('#transaksi_' + kode_barang + ' .counter ');
                        var jual = $('#transaksi_' + kode_barang + ' .totalJual ');
                        var counterSum = counter.text();
                        var jualSum = jual.text().replaceAll('.', '');

                        counter.text((+counterSum + c));
                        var bilangan = (+harga_jual.replaceAll('.', '') + +jualSum);
                        jual.text(rupiah(bilangan));
                    }
                };

                var checkMethod = $('#methodPembayaran strong').text();
                if (checkMethod == '') {
                    $('#methodPembayaran strong').text(metode_pembayaran);
                }
            });

            $('#basked').click(function(e) {
                e.preventDefault();
                $('#baskedModal').modal('show');

                // declare all characters
                const characters =
                    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                function generateString(length) {
                    let result = '';
                    const charactersLength = characters.length;
                    for (let i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() *
                            charactersLength));
                    }

                    return result.toLowerCase();
                }

                var sum = 0;
                $('.totalJual').each(function(index, value) {
                    var combat = $(this).text().replaceAll('.', '');
                    if (!isNaN(combat) && combat.length !== 0) {
                        sum += parseFloat(combat);
                    }
                });

                $('.codeTransaksi').remove();

                $('.transaksi thead').prepend(
                    '<tr class="codeTransaksi"><td colspan="4" class="text-center"><strong>' +
                    'TRHJ_' +
                    generateString(4) +
                    new Date()
                    .getFullYear() +
                    '</strong></td></tr>');

                if ($('#totalBayar').length <= 0) {
                    $('.totals tbody').append(`
                        <tr id="totalBayar">
                            <td class="text-center" colspan="3"><strong>Total</strong></td>
                            <td class="totalRupiah" colspan="1">` + rupiah(sum) + `</td>
                        </tr>
                        `);
                } else {
                    $('.totals .totalRupiah').text(rupiah(sum))
                }

            });

            $('.closed').click(function(e) {
                e.preventDefault();
                $('#baskedModal').modal('hide');
                $('#kembalian').val('');
                $('#jumlahUang').val('');
                // $('.codeTransaksi').remove();
            });

            $('.simpan').click(function(e) {
                e.preventDefault();
                var valid = $("#formValid").valid();
                var kembalianCek = $('#kembalian').val();
                if (valid == true) {
                    if (kembalianCek == 'kurang') {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Pastikan Uang Cukup',
                            showConfirmButton: true,
                            timer: 2500
                        })
                    } else {
                        var trCheck = $('.transaksi tbody tr')
                        var invoice = $('.codeTransaksi').find('td:eq(0) strong').text();
                        var kembali = $('#kembalian').val().replaceAll('.', '');
                        var pembayaran = $('#jumlahUang').val().replaceAll('.', '');
                        var total_harga = $('.totals tbody #totalBayar').find("td:eq(1)").text()
                            .replaceAll(
                                '.', '');

                        var arr = {};
                        trCheck.each(function(index, value) {
                            var nomor_paket = this.id.replaceAll('transaksi_', '');
                            var nama_barang = $(this).find("td:eq(0)").text();
                            var jumlah_barang = $(this).find("td:eq(1)").text();
                            var method = $(this).find("td:eq(2)").text();
                            var harga_item = $(this).find("td:eq(3)").text().replaceAll(
                                '.',
                                '');
                            var harga_jual = $(this).find("td:eq(4)").text().replaceAll(
                                '.',
                                '');
                            // var method = $('#jenisPembayaran').val();


                            // var data = {};
                            arr[index] = {
                                nomor_paket: nomor_paket,
                                nama_barang: nama_barang,
                                jumlah_barang: jumlah_barang,
                                harga_item: harga_item,
                                harga_jual: harga_jual,
                                method: method,
                            };

                        });
                        var data = {
                            invoice: invoice,
                            kembali: kembali,
                            pembayaran: pembayaran,
                            total_harga: total_harga
                        };
                        data['data'] = arr;
                        if (data['data'][0]) {
                            $(".simpan").attr("disabled", true);
                            ajaxQuery('post', '/transaksi/penjualan/store', data)
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Tidak Ada Barang Yang harus Dibayar',
                                showConfirmButton: true,
                                timer: 2500
                            })
                        }
                    }
                }
            });

            var jumlahUangTerima = IMask(
                document.getElementById('jumlahUang'), {
                    mask: 'num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.',
                        },
                    },
                });


            $('#jumlahUang').keyup(function(e) {
                e.preventDefault();
                var jumlahUang = $(this).val().replaceAll('.', '');
                jumlahUangTerima.updateOptions({
                    mask: 'num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: '.'
                        }
                    }
                });
                jumlahUangTerima.typedValue = jumlahUang;
                var totalBelanja = $('.totals .totalRupiah').text().replaceAll('.', '');
                var all = +jumlahUang - +totalBelanja
                if (all > 0) {
                    $('#kembalian').val(rupiah(all))
                } else {
                    $('#kembalian').val('kurang')
                }
            });

            $('#button_pas').click(function(e) {
                e.preventDefault();
                var jumlahUang = $('.totals .totalRupiah').text();
                $('#jumlahUang').val(jumlahUang);
                $('#kembalian').val(0);
            });

            // kurangi item
            $(document).on('click', '.kurangi', function() {

                var barang = $(this).attr('data-kode_barang')
                var jumlah_lama = $('#transaksi_' + barang + ' .counter').text();
                var kurang = $('#transaksi_' + barang + ' .counter').text((jumlah_lama - 1));
                var jumlah_baru = $('#transaksi_' + barang + ' .counter').text();
                var hargaJual = $('#transaksi_' + barang + ' .hargaJual').text().replaceAll('.',
                    '');
                var totalJual = $('#transaksi_' + barang + ' .totalJual').text().replaceAll('.',
                    '');
                var totalJualBaru = $('#transaksi_' + barang + ' .totalJual').text(rupiah(
                    totalJual -
                    hargaJual));
                var totalRupiah = $('.totalRupiah').text().replaceAll('.', '');
                var totalRupiahbaru = $('.totalRupiah').text(rupiah(totalRupiah - hargaJual));

                var basked = $('.baskeds')
                basked.text((+basked.text() - 1));

                if (jumlah_baru == '0') {
                    $('#transaksi_' + barang).remove()
                }
                var tables = $('.transaksi tbody tr').length;
                if (tables == 0) {
                    basked.text('');
                }
            });
        });
    </script>
@endpush
