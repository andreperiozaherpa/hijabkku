@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Laporan Penjualan</h1>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card mb-3 card-spiner">
                        <div class="card-body">
                            <div class="col-12 col-sm-12 col-lg-12 col-xxl-12 mb-3">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="Input Tanggal" id="getDates">
                                    <span class="input-group-text">
                                        <i data-acorn-icon="calendar" class="text-muted"></i>
                                    </span>
                                </div>

                                <div class="input-group mb-2">
                                    <select class="form-select select2Class" id="selectToko" data-placeholder="Pilih Toko">
                                        <option label="&nbsp;"></option>
                                        @if (Auth::user()->role == 'admin')
                                            <option value="semua">Semua</option>
                                            @foreach ($toko as $tk)
                                                <option value="{{ $tk->kode }}">{{ $tk->nama_toko }}</option>
                                            @endforeach
                                        @else
                                            <option value="{{ Auth::user()->kode_toko }}" selected>{{ Auth::user()->kode_toko }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="input-group">
                                    <select class="form-select select2Class" id="selectKaryawan" data-placeholder="Pilih Karyawan" disabled>
                                        @if (Auth::user()->role != 'admin')
                                            <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->name }}</option>
                                        @endif
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="btn-group w-100" role="group">
                                        <button class="btn btn-primary filter-button" id="hari">
                                            Hari
                                        </button>
                                        <button class="btn btn-primary filter-button" id="bulan">
                                            Bulan
                                        </button>
                                        <button class="btn btn-primary filter-button" id="tahun">
                                            Tahun
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary mb-3 w-100" id="hitungTotal" data-parameter="" disabled>Hitung Total Penjualan</button>
                                </div>
                            </div>
                        </div>
                        <div class="overlay-spinner d-none">
                            <div class="spinner-border text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    <div class="row grid">
                        <div class="col-12 col-md-6 grid-item">
                            <button class="btn btn-primary mb-1 w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-table-umum" aria-expanded="false" aria-controls="collapseExample">
                                Pembayaran Umum
                            </button>
                            <div class="collapsing" id="collapse-table-umum">
                                <div class="card card-body no-shadow border mt-3">
                                    <div class="table-responsive mt-2 mb-4">
                                        <table class="table table-bordered" id="table-umum">
                                            <thead>
                                                <tr>
                                                    <th>Toko</th>
                                                    <th>Kode Invoice</th>
                                                    <th>Nama Kasir</th>
                                                    <th>Nama Barang</th>
                                                    <th>Pembayaran</th>
                                                    <th>Jumlah</th>
                                                    <th>Harga</th>
                                                    @if (Auth::user()->role == 'admin')
                                                        <th>Modal</th>
                                                        <th>Total Modal</th>
                                                    @endif
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody-umum"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 grid-item">
                            <button class="btn btn-primary mb-1 w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-table-grosir" aria-expanded="false" aria-controls="collapseExample">
                                Pembayaran Grosir
                            </button>
                            <div class="collapsing" id="collapse-table-grosir">
                                <div class="card card-body no-shadow border mt-3">
                                    <div class="table-responsive mt-2 mb-4">
                                        <table class="table table-bordered" id="table-grosir">
                                            <thead>
                                                <tr>
                                                    <th>Toko</th>
                                                    <th>Kode Invoice</th>
                                                    <th>Nama Kasir</th>
                                                    <th>Nama Barang</th>
                                                    <th>Pembayaran</th>
                                                    <th>Jumlah</th>
                                                    <th>Harga</th>
                                                    @if (Auth::user()->role == 'admin')
                                                        <th>Modal</th>
                                                        <th>Total Modal</th>
                                                    @endif
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody-grosir"></tbody>
                                        </table>
                                    </div>
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
        function formatRupiah(angka) {
            return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function removeData(chart) {
            // console.log(chart.data.labels);
            chart.data = {
                labels: [],
                datasets: []
            };
            chart.update();
        }

        function addData(chart, label, newData) {
            // chart.data.labels.push(label);
            // chart.data.datasets.push(newData)
            chart.data = {
                labels: label,
                datasets: newData
            };
            chart.update();
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

        function checkSelectKaryawan() {
            const val = $('#selectKaryawan').val();
            if (val && val !== '') {
                return true
            } else {
                return false
            }
        }

        function ajaxData(method, url, data, param, optional) {
            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function(response) {
                    $('.overlay-spinner').removeClass('d-none');
                },
                success: function(response) {
                    if (response.param == 'hari') {
                        getData(response, optional)
                    }
                    if (response.param == 'bulan') {
                        getData(response, optional)
                    }
                    if (response.param == 'tahun') {
                        getData(response, optional)
                    }

                    if (response.param == 'change') {
                        getDataChange(response, optional)
                    }

                    $('.overlay-spinner').addClass('d-none');
                },
                error: function(xhr) { // if error occured

                },
            });
        }

        function getData(res, opt) {
            $('.tbody-umum').html('');
            $('.tbody-grosir').html('');

            const dataTransaksi = res.data.transaksi
            const dataPembayaran = res.data.pembayaran
            const dataKaryawan = res.karyawan


            const laporan = dataTransaksi.map(tr => {
                const pembayaran = dataPembayaran.find(p => p.kode_invoice === tr.kode_invoice);
                return {
                    ...tr,
                    user_name: pembayaran ? pembayaran.user_name : 'Tidak diketahui',
                    user_id: pembayaran ? pembayaran.user_id : 'Tidak diketahui'
                };
            });


            laporan.forEach(item => {
                let toko = '';
                if (item.kode_toko == 'TK_4fep') {
                    toko = 'Daya Asri'
                } else if (item.kode_toko == 'TK_z4sj') {
                    toko = 'Panaragan'
                } else if (item.kode_toko == 'TK_un7s') {
                    toko = 'Mesuji'
                }


                const total = item.jumlah * item.harga;
                const modal = item.jumlah * item.harga_beli;

                if ("{{ Auth::user()->role }}" == 'admin') {
                    if (dataKaryawan == item.user_id) {
                        var rowHtml = `
                            <tr>
                                <td>${toko}</td>
                                <td>${item.kode_invoice}</td>
                                <td>${item.user_name}</td>
                                <td>${item.nama_barang}</td>
                                <td>${item.metode}</td>
                                <td>${item.jumlah}</td>
                                <td>${formatRupiah(item.harga)}</td>
                                <td>${formatRupiah(item.harga_beli)}</td>
                                <td>${formatRupiah(modal)}</td>
                                <td>${formatRupiah(total)}</td>
                            </tr>
                        `;
                    }

                    if (dataKaryawan == 'semua') {
                        var rowHtml = `
                            <tr>
                                <td>${toko}</td>
                                <td>${item.kode_invoice}</td>
                                <td>${item.user_name}</td>
                                <td>${item.nama_barang}</td>
                                <td>${item.metode}</td>
                                <td>${item.jumlah}</td>
                                <td>${formatRupiah(item.harga)}</td>
                                <td>${formatRupiah(item.harga_beli)}</td>
                                <td>${formatRupiah(modal)}</td>
                                <td>${formatRupiah(total)}</td>
                            </tr>
                        `;
                    }
                } else {
                    if (dataKaryawan == item.user_id) {
                        var rowHtml = `
                        <tr>
                            <td>${toko}</td>
                            <td>${item.kode_invoice}</td>
                            <td>${item.user_name}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.metode}</td>
                            <td>${item.jumlah}</td>
                            <td>${formatRupiah(item.harga)}</td>
                            <td>${formatRupiah(total)}</td>
                        </tr>
                    `;
                    }
                }

                if (item.metode === 'umum') {
                    $('.tbody-umum').append(rowHtml);
                } else if (item.metode === 'grosir') {
                    $('.tbody-grosir').append(rowHtml);
                }
            });

            $('#hitungTotal').attr('disabled', false)
        }

        function getDataChange(res, opt) {
            var karyawan = $('#selectKaryawan')
            karyawan.attr('disabled', true)
            karyawan.html('')

            checkSelectKaryawan()

            if ("{{ Auth::user()->role }}" == 'admin') {
                karyawan.append('<option label="&nbsp;"> </option>')
                karyawan.append('<option value="semua" selected>Semua</option>')
                res.data.forEach(item => {
                    karyawan.append(`<option value="${item.id}">${item.name}</option>`)
                });
            }

            karyawan.attr('disabled', false)
        }

        function ekstrakInfoTanggal(tanggalStr) {
            const [tahunStr, bulanStr, hariStr] = tanggalStr.split('-');

            const tahun = parseInt(tahunStr, 10);
            const bulanUntukDateObj = parseInt(bulanStr, 10) - 1; // 0 untuk Januari, 1 untuk Februari, dst.
            const hari = parseInt(hariStr, 10);

            const tanggalObj = new Date(Date.UTC(tahun, bulanUntukDateObj, hari));

            const tahunHasil = tanggalObj.getUTCFullYear();
            const bulanRaw = tanggalObj.getUTCMonth(); // Ini akan 0 untuk Januari, 1 untuk Februari, dst.
            const bulanAngkaFormatted = (bulanRaw + 1).toString().padStart(2, '0');
            const namaBulan = new Intl.DateTimeFormat('id-ID', {
                month: 'long',
                timeZone: 'UTC'
            }).format(tanggalObj);

            return {
                tahun: tahunHasil,
                bulan: bulanAngkaFormatted,
                namaBulan: namaBulan
            };
        }

        $(document).ready(function() {
            checkSelectKaryawan()

            if (document.getElementById('laporanGrafik')) {
                const barChart = document.getElementById('laporanGrafik').getContext('2d');
                var chartBars = new Chart(barChart, {
                    type: 'bar',
                    options: {
                        cornerRadius: parseInt(Globals.borderRadiusMd),
                        plugins: {
                            crosshair: false,
                            datalabels: {
                                display: false
                            },
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    display: true,
                                    lineWidth: 1,
                                    color: Globals.separatorLight,
                                    drawBorder: false,
                                },
                                ticks: {
                                    beginAtZero: true,
                                    // stepSize: 100,
                                    padding: 20,
                                },
                            }, ],
                            xAxes: [{
                                gridLines: {
                                    display: false
                                },
                            }, ],
                        },
                        legend: {
                            display: false,
                            position: 'bottom',
                            labels: ChartsExtend.LegendLabels(),
                        },
                        tooltips: ChartsExtend.ChartTooltip(),
                    },
                    data: {
                        labels: [],
                        datasets: [],
                    },
                });
            }

            $('#getDates').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
            }).datepicker('update', new Date());
            // }).datepicker('update', '2025-05-14');

            $('#hari').click(function(e) {
                e.preventDefault();
                var date = $('#getDates').val()
                var selectToko = $('#selectToko').val()
                var selectKaryawan = $('#selectKaryawan').val()
                $('#hitungTotal').attr('data-parameter', 'tanggal')
                $('#hitungTotal').attr('disabled', true)
                $('#laporan-body').empty();

                ajaxData('GET', '/laporan/penjualan/show', {
                    date: date,
                    param: 'hari',
                    toko: selectToko,
                    karyawan: selectKaryawan,
                }, 'hari', chartBars);
            });

            $('#bulan').click(function(e) {
                e.preventDefault();
                var date = $('#getDates').val()
                var get_date = ekstrakInfoTanggal(date);

                var selectToko = $('#selectToko').val()
                var selectKaryawan = $('#selectKaryawan').val()
                $('#hitungTotal').attr('data-parameter', 'tanggal')
                $('#hitungTotal').attr('disabled', true)
                $('#laporan-body').empty();

                ajaxData('GET', '/laporan/penjualan/show', {
                    date: get_date.tahun + '-' + get_date.bulan,
                    param: 'bulan',
                    toko: selectToko,
                    karyawan: selectKaryawan,
                }, 'bulan', chartBars);
            });

            $('#tahun').click(function(e) {
                e.preventDefault();
                var date = $('#getDates').val()
                var get_date = ekstrakInfoTanggal(date);

                var selectToko = $('#selectToko').val()
                var selectKaryawan = $('#selectKaryawan').val()
                $('#hitungTotal').attr('data-parameter', 'tanggal')
                $('#hitungTotal').attr('disabled', true)
                $('#laporan-body').empty();

                ajaxData('GET', '/laporan/penjualan/show', {
                    date: get_date.tahun,
                    param: 'tahun',
                    toko: selectToko,
                    karyawan: selectKaryawan,
                }, 'tahun', chartBars);
            });

            $('#selectToko').change(function(e) {
                e.preventDefault();
                var selected = $(this).val()
                var data = {
                    selected: selected,
                    parameters: 'karyawan'
                }
                ajaxData('get', '/laporan/penjualan/create', data, 'change')
            });

            $('#hitungTotal').click(function() {
                let totalUmum = 0;
                let totalUmumUntung = 0;
                let totalGrosir = 0;
                let totalGrosirUntung = 0;
                var parameter_date = $('#hitungTotal').attr('data-parameter')
                var date = $('#getDates').val()
                var nama_toko = $('#selectToko').find('option:selected').text()
                var data_date;

                const infoTanggal1 = ekstrakInfoTanggal(date);

                if ("{{ Auth::user()->role }}" == 'admin') {
                    var eqTotal = 9
                    var eqUntung = 8
                } else {
                    var eqTotal = 7
                    var eqUntung = 8
                }

                if (parameter_date == 'tanggal') {
                    data_date = nama_toko + ' Tanggal ' + date;
                } else if (parameter_date == 'bulan') {
                    data_date = nama_toko + ' Bulan ' + infoTanggal1.namaBulan + ' Tahun ' + infoTanggal1.tahun;
                } else if (parameter_date == 'tahun') {
                    data_date = nama_toko + ' Tahun ' + infoTanggal1.tahun;
                } else {
                    data_date = 'error';
                }

                // Hitung total dari tabel umum
                $('.tbody-umum tr').each(function() {
                    const totalText = $(this).find('td').eq(eqTotal).text().replace(/[Rp.\s]/g, '');
                    const totalTextUntung = $(this).find('td').eq(eqUntung).text().replace(/[Rp.\s]/g, '');
                    totalUmum += parseInt(totalText) || 0;
                    totalUmumUntung += parseInt(totalTextUntung) || 0;
                });

                // Hitung total dari tabel grosir
                $('.tbody-grosir tr').each(function() {
                    const totalText = $(this).find('td').eq(eqTotal).text().replace(/[Rp.\s]/g, '');
                    const totalTextUntung = $(this).find('td').eq(eqUntung).text().replace(/[Rp.\s]/g, '');
                    totalGrosir += parseInt(totalText) || 0;
                    totalGrosirUntung += parseInt(totalTextUntung) || 0;
                });

                // Tampilkan hasil
                const totalGabungan = totalUmum + totalGrosir;
                const totalmodal = totalUmumUntung + totalGrosirUntung;
                var totalKeuntungan = totalGabungan - totalmodal;

                if ("{{ Auth::user()->role }}" == 'admin') {
                    var htmlAdds = `<div style="text-align: left;">
                                <b>Pembayaran Umum:</b> ${formatRupiah(totalUmum)}<br>
                                <b>Pembayaran Grosir:</b> ${formatRupiah(totalGrosir)}<br>
                                <hr>
                                <b>Total Gabungan:</b> ${formatRupiah(totalGabungan)}<br>
                                <b>Total Keuntung:</b> ${formatRupiah(totalKeuntungan)}
                            </div>`;
                } else {
                    var htmlAdds = `<div style="text-align: left;">
                                <b>Pembayaran Umum:</b> ${formatRupiah(totalUmum)}<br>
                                <b>Pembayaran Grosir:</b> ${formatRupiah(totalGrosir)}<br>
                                <hr>
                                <b>Total Gabungan:</b> ${formatRupiah(totalGabungan)}
                            </div>`;
                }

                Swal.fire({
                    title: 'Total Penjualan ' + data_date,
                    html: htmlAdds,
                    icon: 'success',
                    confirmButtonText: 'Tutup'
                });

                $('#collapse-table-grosir').collapse("show")
                $('#collapse-table-umum').collapse("show")
            });
        });
    </script>
@endpush
