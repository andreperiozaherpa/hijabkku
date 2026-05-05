@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        @if (request()->format == 'Bulan')
                            <h1 class="mb-0 pb-0 display-4" id="title">Rekap Toko {{ $toko }} Bulan
                                {{ $bulans }}
                                {{ $tahun }}</h1>
                        @elseif (request()->format == 'Tahun')
                            <h1 class="mb-0 pb-0 display-4" id="title">Rekap Toko {{ $toko }} Tahun
                                {{ $tahun }}</h1>
                        @else
                            <h1 class="mb-0 pb-0 display-4" id="title">Rekap Toko {{ $toko }} Tanggal
                                {{ $hari }}
                                {{ $bulans }}
                                {{ $tahun }}</h1>
                        @endif

                        <input type="text" class="dataToko" value="{{ $toko }}" hidden>
                        <input type="text" class="dataHari" value="{{ $hari }}" hidden>
                        <input type="text" class="dataBulan" value="{{ $bulan }}" hidden>
                        <input type="text" class="dataTahun" value="{{ $tahun }}" hidden>
                        <input type="text" class="dataUser" value="{{ $users }}" hidden>
                        <input type="text" class="format" value="{{ request()->format }}" hidden>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12 col-sm-12 col-lg-12 col-xxl-12 mb-2">
                    <div class="btn-group w-100">
                        <button class="neraca btn btn-primary mb-1">Neraca</button>
                    </div>
                </div>
                <table class="table table-hover table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Kode Invoice</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Kasir</th>
                            <th scope="col">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                            function rupiah($angka)
                            {
                                $hasil_rupiah = 'Rp ' . number_format($angka, 2, ',', '.');
                                return $hasil_rupiah;
                            }

                        @endphp
                        @foreach ($data as $dt)
                            <tr class="looking" data-invoice="{{ $dt['kode_invoice'] }}">
                                <th scope="row">{{ $i++ }}</th>
                                <td>{{ $dt['kode_invoice'] }}</td>
                                <td>{{ Carbon\Carbon::parse($dt['created_at'])->format('d M Y H:i') }}</td>
                                <td>{{ $dt['user_name'] }}</td>
                                <td class="total_harga">{{ rupiah($dt['total_harga']) }}</td>
                                <td class="total_harga_rupiah" hidden>{{ $dt['total_harga'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-center bold">Total</td>
                            <td class="total"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Content End -->
        </div>
    </main>

    <div class="modal fade modalNeraca" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody class="isiNeraca">
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
        function sumData(params) {
            var sum = 0;
            $('.' + params).each(function() {
                var combat = $(this).text().replaceAll('.', '');
                if (!isNaN(combat) && combat.length !== 0) {
                    sum += parseFloat(combat);
                }
            });
            return sum;
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

        function ajaxData(method, url, data, param) {
            $.ajax({
                type: method,
                url: url,
                data: data,
                success: function(response) {
                    neraca(response)
                }
            });
        }

        function neraca(params) {
            console.log(params);
            $('.isiNeraca').append(`
                <tr>
                    <td scope="col" class="dataBarangAll">Bruto</td>
                    <td scope="col" class="dataBarangAll"></td>
                    <td scope="col" class="dataBarangAll">Rp. ` + rupiah(params.bruto.harga_totals) + `</td>
                </tr>
                <tr>
                    <td scope="col" class="dataBarangAll">Keuntungan</td>
                    <td scope="col" class="dataBarangAll"></td>
                    <td scope="col" class="dataBarangAll">Rp. ` + rupiah(params.keuntungan.harga_beli_totals) + `</td>
                </tr>
                `)
        }

        $(document).ready(function() {
            $('.total').html('Rp. ' + rupiah(sumData('total_harga_rupiah')));

            $('.neraca').click(function(e) {
                e.preventDefault();
                $('.modalNeraca').modal('show');
                var button = $(this).text();
                var dataToko = $('.dataToko').val();
                var dataHari = $('.dataHari').val();
                var dataBulan = $('.dataBulan').val();
                var dataTahun = $('.dataTahun').val();
                var dataUser = $('.dataUser').val();
                var dataFormat = $('.format').val();
                var data = {
                    jenisNeraca: button,
                    dataToko: dataToko,
                    dataHari: dataHari,
                    dataBulan: dataBulan,
                    dataTahun: dataTahun,
                    dataUser: dataUser,
                    dataFormat: dataFormat
                }
                if (button == 'Neraca') {
                    ajaxData('get', '/transaksi/daftar/neraca', data)
                }
            });

            $('.looking').click(function(e) {
                e.preventDefault();
                var invoice = $(this).data('invoice');
                console.log(invoice);
            });

            $('.closed').click(function(e) {
                $('.modalNeraca').modal('hide');
                $('.dataBarangAll').remove();
            })

        });
    </script>
@endpush
