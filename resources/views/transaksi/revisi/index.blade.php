@extends('layouts.main')

@section('main')
    <main>
        <div class="container">
            <!-- Title Start -->
            <div class="page-title-container">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Revisi Penjualan</h1>
                        <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            <ul class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Revisi</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Title End -->

            <!-- Search Invoice Start -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-end g-3">
                                <div class="col-12 col-md-8">
                                    <label class="form-label font-weight-bold">Masukkan Kode Invoice (Misal: INV...)</label>
                                    <div class="input-group">
                                        <input type="text" id="inputKodeInvoice" class="form-control form-control-lg" placeholder="Scan atau ketik kode invoice...">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <button type="button" id="btnCariInvoice" class="btn btn-primary btn-lg w-100">
                                        <i data-acorn-icon="search" class="me-2"></i>Cari Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Search Invoice End -->

            <!-- Invoice Details Start (Hidden initially) -->
            <div id="invoiceDetailContainer" style="display: none;">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                                        <div class="text-muted text-small">Invoice</div>
                                        <div class="font-weight-bold fs-5 text-primary" id="lblInvoice">-</div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-3 mb-md-0">
                                        <div class="text-muted text-small">Tanggal Transaksi</div>
                                        <div class="font-weight-bold" id="lblTanggal">-</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="text-muted text-small">Kasir</div>
                                        <div class="font-weight-bold" id="lblKasir">-</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="text-muted text-small">Total Belanja</div>
                                        <div class="font-weight-bold text-success fs-5" id="lblTotal">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">Daftar Item Dibeli</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">SKU / Kode</th>
                                                <th class="text-muted text-small text-uppercase">Nama Barang</th>
                                                <th class="text-muted text-small text-uppercase text-end">Harga</th>
                                                <th class="text-muted text-small text-uppercase text-center">Qty</th>
                                                <th class="text-muted text-small text-uppercase text-end">Subtotal</th>
                                                <th class="text-muted text-small text-uppercase text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbItems">
                                            <!-- Items injected via JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Invoice Details End -->

            <!-- Riwayat Revisi Start -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Daftar Riwayat Revisi Penjualan</h4>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-muted text-small text-uppercase">Tanggal</th>
                                            <th class="text-muted text-small text-uppercase">Invoice</th>
                                            <th class="text-muted text-small text-uppercase">Toko</th>
                                            <th class="text-muted text-small text-uppercase">Operator</th>
                                            <th class="text-muted text-small text-uppercase">Koreksi</th>
                                            <th class="text-muted text-small text-uppercase text-end">Selisih</th>
                                            <th class="text-muted text-small text-uppercase">Alasan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($riwayats as $r)
                                            <tr>
                                                <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                                <td><span class="font-weight-bold text-primary">{{ $r->kode_invoice }}</span></td>
                                                <td>{{ $r->kode_toko }}</td>
                                                <td>{{ $r->user->name ?? '-' }}</td>
                                                <td>
                                                    <span class="text-danger">{{ $r->barang_lama_nama }}</span> 
                                                    <i data-acorn-icon="arrow-right" class="mx-1 text-muted" style="width: 12px; height: 12px;"></i>
                                                    <span class="text-success">{{ $r->barang_baru_nama }}</span>
                                                </td>
                                                <td class="text-end font-weight-bold {{ $r->selisih_harga >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ ($r->selisih_harga >= 0 ? '+' : '') . number_format($r->selisih_harga, 0, ',', '.') }}
                                                </td>
                                                <td class="text-alternate">{{ $r->alasan ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat revisi penjualan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $riwayats->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Riwayat Revisi End -->

        </div>

        <!-- Modal Ganti Barang -->
        <div class="modal fade" id="modalGantiBarang" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        <h5 class="modal-title font-weight-bold d-flex align-items-center">
                            <i data-acorn-icon="edit" class="me-2 text-white"></i> Revisi Barang Kasir
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-warning border-0 d-flex align-items-center" role="alert">
                            <i data-acorn-icon="warning-hexagon" class="me-3"></i>
                            <div>
                                Stok barang lama akan dikembalikan, dan stok barang baru akan dikurangi. Jika ada selisih harga, total laporan penjualan akan otomatis disesuaikan!
                            </div>
                        </div>

                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body p-3">
                                <div class="text-muted text-small mb-1">Barang yang Salah (Akan Dihapus):</div>
                                <div class="font-weight-bold fs-6 text-danger" id="lblBarangSalah">-</div>
                                <div class="text-muted small">Qty: <span id="lblQtySalah"></span> | Harga: <span id="lblHargaSalah"></span></div>
                            </div>
                        </div>

                        <form id="formRevisi" data-no-spinner>
                            <input type="hidden" id="transaksi_id">
                            <input type="hidden" id="kode_toko_modal">
                            <input type="hidden" id="metode_modal">
                            <input type="hidden" id="qty_modal">
                            <input type="hidden" id="harga_lama_modal">
                            
                            <div class="mb-4">
                                <label class="form-label font-weight-bold">Cari Barang Pengganti (Barang yang Benar) <span class="text-danger">*</span></label>
                                <div class="input-group mb-2 shadow-sm rounded">
                                    <input type="text" id="cariBarangInput" class="form-control" placeholder="Ketik nama atau kode barang...">
                                    <button class="btn btn-outline-primary" type="button" id="btnCariBarang">Cari</button>
                                </div>
                                <div id="hasilPencarian" class="list-group rounded overflow-auto shadow-sm" style="max-height: 200px; display: none;">
                                    <!-- Results injected via JS -->
                                </div>
                                <input type="hidden" id="barang_baru_kode" required>
                                <div id="barangTerpilih" class="mt-2 p-2 border border-success rounded bg-success-light text-success font-weight-bold" style="display: none;">
                                    <i data-acorn-icon="check-circle" class="me-1" style="width: 14px; height: 14px;"></i> <span id="lblBarangTerpilih"></span>
                                </div>
                            </div>

                            <div class="mb-4" id="selectMetodeHargaContainer" style="display: none;">
                                <label class="form-label font-weight-bold">Metode Harga Barang Pengganti <span class="text-danger">*</span></label>
                                <select id="metode_harga_baru" class="form-select">
                                    <option value="umum">Harga Umum (Ecer)</option>
                                    <option value="grosir">Harga Grosir</option>
                                </select>
                            </div>

                            <!-- Kalkulasi Harga & Pembayaran Baru -->
                            <div id="sectionKalkulasi" class="card border-0 bg-light mb-3" style="display: none;">
                                <div class="card-body p-3">
                                    <h6 class="font-weight-bold mb-2 text-dark">Penyesuaian Pembayaran & Nominal</h6>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <span class="text-muted small">Total Belanja Baru:</span>
                                            <div class="font-weight-bold text-primary" id="lblTotalBaru" data-val="0">Rp. 0</div>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted small">Selisih Total:</span>
                                            <div class="font-weight-bold" id="lblSelisihTotal">Rp. 0</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-small">Nominal Pembayaran Baru (Uang Diterima) <span class="text-danger">*</span></label>
                                        <input type="text" id="inputPembayaranBaru" class="form-control" placeholder="Masukkan jumlah uang yang diterima...">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <span class="text-muted small">Kembalian Baru:</span>
                                            <div class="font-weight-bold text-success" id="lblKembalianBaru">Rp. 0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Alasan Revisi (Opsional)</label>
                                <textarea id="alasanRevisi" class="form-control" rows="2" placeholder="Contoh: Kasir salah scan varian ukuran S menjadi M"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light py-3">
                        <button type="button" class="btn btn-outline-secondary font-weight-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary font-weight-bold" id="btnProsesRevisi">
                            <i data-acorn-icon="save" class="me-1"></i> Proses Revisi
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        let currentPembayaran = null;
        const formatRupiah = (angka) => {
            return 'Rp. ' + parseInt(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        };

        const loadInvoice = () => {
            const kode = $('#inputKodeInvoice').val().trim();
            if (!kode) {
                Swal.fire('Perhatian', 'Silakan masukkan kode invoice terlebih dahulu!', 'warning');
                return;
            }

            $('#btnCariInvoice').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mencari...');

            $.get('{{ route("revisi.cari") }}', { kode_invoice: kode }, function(res) {
                $('#btnCariInvoice').prop('disabled', false).html('<i data-acorn-icon="search" class="me-2"></i>Cari Invoice');
                
                if (res.success) {
                    currentPembayaran = res.pembayaran;
                    $('#lblInvoice').text(res.pembayaran.kode_invoice);
                    $('#lblTanggal').text(new Date(res.pembayaran.created_at).toLocaleString('id-ID'));
                    $('#lblKasir').text(res.pembayaran.user_name);
                    $('#lblTotal').text(formatRupiah(res.pembayaran.total_harga));

                    let html = '';
                    res.transaksis.forEach(t => {
                        html += `
                            <tr>
                                <td><span class="text-alternate">${t.kode_barang}</span></td>
                                <td><span class="font-weight-bold">${t.nama_barang}</span></td>
                                <td class="text-end">${formatRupiah(t.harga)}</td>
                                <td class="text-center font-weight-bold">${t.jumlah}</td>
                                <td class="text-end font-weight-bold text-dark">${formatRupiah(t.harga_total)}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger btn-koreksi" 
                                        data-id="${t.id}" 
                                        data-kode="${t.kode_barang}" 
                                        data-nama="${t.nama_barang}" 
                                        data-qty="${t.jumlah}" 
                                        data-harga-raw="${t.harga}" 
                                        data-metode="${t.metode}" 
                                        data-toko="${t.kode_toko}">
                                        <i data-acorn-icon="edit" style="width:14px; height:14px;"></i> Koreksi
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    $('#tbItems').html(html);
                    $('#invoiceDetailContainer').fadeIn();
                    
                    if (typeof AcornIcons !== 'undefined') {
                        new AcornIcons().replace();
                    }
                } else {
                    $('#invoiceDetailContainer').hide();
                    Swal.fire('Gagal!', res.message, 'error');
                }
            }).fail(function() {
                $('#btnCariInvoice').prop('disabled', false).html('<i data-acorn-icon="search" class="me-2"></i>Cari Invoice');
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            });
        };

        $('#btnCariInvoice').on('click', loadInvoice);
        $('#inputKodeInvoice').on('keypress', function(e) {
            if (e.which === 13) loadInvoice();
        });

        $(document).on('click', '.btn-koreksi', function() {
            const btn = $(this);
            $('#transaksi_id').val(btn.data('id'));
            $('#kode_toko_modal').val(btn.data('toko'));
            $('#metode_modal').val(btn.data('metode'));
            $('#qty_modal').val(btn.data('qty'));
            $('#harga_lama_modal').val(btn.data('harga-raw'));
            $('#lblBarangSalah').text(btn.data('kode') + ' - ' + btn.data('nama'));
            $('#lblQtySalah').text(btn.data('qty'));
            $('#lblHargaSalah').text(formatRupiah(btn.data('harga-raw')));
            
            // Reset modal
            $('#cariBarangInput').val('');
            $('#hasilPencarian').hide().html('');
            $('#barang_baru_kode').val('');
            $('#barangTerpilih').hide();
            $('#selectMetodeHargaContainer').hide();
            $('#metode_harga_baru').val(btn.data('metode'));
            $('#alasanRevisi').val('');
 
            // Reset Kalkulasi
            $('#sectionKalkulasi').hide();
            $('#inputPembayaranBaru').val(currentPembayaran ? currentPembayaran.pembayaran.toLocaleString('id-ID') : '');
 
            $('#modalGantiBarang').modal('show');
        });
 
        const cariBarang = () => {
            const q = $('#cariBarangInput').val().trim();
            if (q.length < 2) return;
 
            $('#hasilPencarian').show().html('<div class="p-3 text-center text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Mencari...</div>');
            
            $.get('{{ route("revisi.cari_barang") }}', { query: q, kode_toko: $('#kode_toko_modal').val() }, function(res) {
                if (res.length === 0) {
                    $('#hasilPencarian').html('<div class="p-3 text-center text-muted">Barang tidak ditemukan.</div>');
                    return;
                }
                
                let html = '';
                res.forEach(b => {
                    html += `
                        <button type="button" class="list-group-item list-group-item-action item-pilih-barang" 
                            data-kode="${b.kode}" 
                            data-nama="${b.nama_barang}"
                            data-harga-jual="${b.harga_jual.replace(/\./g, '')}"
                            data-harga-grosir="${b.harga_grosir.replace(/\./g, '')}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="font-weight-bold">${b.nama_barang}</div>
                                    <div class="small text-muted">${b.kode} | Ecer: ${formatRupiah(b.harga_jual.replace(/\./g, ''))} | Grosir: ${formatRupiah(b.harga_grosir.replace(/\./g, ''))}</div>
                                </div>
                            </div>
                        </button>
                    `;
                });
                $('#hasilPencarian').html(html);
            });
        };
 
        $('#btnCariBarang').on('click', cariBarang);
        $('#cariBarangInput').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                cariBarang();
            }
        });

        const hitungKalkulasi = () => {
            const kodeBaru = $('#barang_baru_kode').val();
            if (!kodeBaru) return;

            const metodeHargaBaru = $('#metode_harga_baru').val();
            const qty = parseInt($('#qty_modal').val()) || 0;
            const hargaLama = parseInt($('#harga_lama_modal').val()) || 0;
            
            const hargaJual = parseInt($('#barang_baru_kode').data('harga-jual')) || 0;
            const hargaGrosir = parseInt($('#barang_baru_kode').data('harga-grosir')) || 0;
            
            const hargaBaru = metodeHargaBaru === 'grosir' ? hargaGrosir : hargaJual;
            
            const totalLamaItem = qty * hargaLama;
            const totalBaruItem = qty * hargaBaru;
            const selisih = totalBaruItem - totalLamaItem;
            
            const totalInvoiceLama = currentPembayaran ? currentPembayaran.total_harga : 0;
            const totalInvoiceBaru = totalInvoiceLama + selisih;
            
            $('#lblTotalBaru').text(formatRupiah(totalInvoiceBaru)).data('val', totalInvoiceBaru);
            
            const selisihText = (selisih >= 0 ? '+' : '') + formatRupiah(selisih);
            $('#lblSelisihTotal').text(selisihText);
            if (selisih >= 0) {
                $('#lblSelisihTotal').removeClass('text-danger').addClass('text-success');
            } else {
                $('#lblSelisihTotal').removeClass('text-success').addClass('text-danger');
            }
            
            // Hitung kembalian baru
            const pembayaranVal = $('#inputPembayaranBaru').val().replace(/\D/g, '');
            const pembayaranBaru = pembayaranVal ? parseInt(pembayaranVal) : 0;
            const kembalianBaru = pembayaranBaru - totalInvoiceBaru;
            $('#lblKembalianBaru').text(formatRupiah(kembalianBaru));
            
            $('#sectionKalkulasi').fadeIn();
        };

        $('#metode_harga_baru').on('change', hitungKalkulasi);
 
        $(document).on('click', '.item-pilih-barang', function() {
            const btn = $(this);
            const kode = btn.data('kode');
            const nama = btn.data('nama');
            
            $('#barang_baru_kode')
                .val(kode)
                .data('harga-jual', btn.data('harga-jual'))
                .data('harga-grosir', btn.data('harga-grosir'));

            $('#lblBarangTerpilih').text(kode + ' - ' + nama);
            $('#barangTerpilih').fadeIn();
            $('#selectMetodeHargaContainer').fadeIn();
            $('#hasilPencarian').hide();
            $('#cariBarangInput').val('');
 
            hitungKalkulasi();
        });
 
        $('#inputPembayaranBaru').on('input', function() {
            const val = $(this).val().replace(/\D/g, '');
            $(this).val(val ? parseInt(val).toLocaleString('id-ID') : '');
            
            const pembayaranBaru = val ? parseInt(val) : 0;
            const totalInvoiceBaru = parseInt($('#lblTotalBaru').data('val')) || 0;
            const kembalianBaru = pembayaranBaru - totalInvoiceBaru;
            
            $('#lblKembalianBaru').text(formatRupiah(kembalianBaru));
        });
 
        $('#btnProsesRevisi').on('click', function() {
            const trxId = $('#transaksi_id').val();
            const kodeBaru = $('#barang_baru_kode').val();
            const metodeHargaBaru = $('#metode_harga_baru').val();
            const alasan = $('#alasanRevisi').val();
            const pembayaranBaru = $('#inputPembayaranBaru').val().replace(/\D/g, '');
 
            if (!kodeBaru) {
                Swal.fire('Peringatan', 'Silakan pilih barang pengganti yang benar!', 'warning');
                return;
            }
 
            if (!pembayaranBaru) {
                Swal.fire('Peringatan', 'Nominal pembayaran baru wajib diisi!', 'warning');
                return;
            }
 
            Swal.fire({
                title: 'Konfirmasi Revisi?',
                text: "Pastikan barang pengganti sudah benar. Aksi ini akan mengubah laporan stok dan transaksi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btnProsesRevisi').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Memproses...');
                    
                    $.post('{{ route("revisi.proses") }}', {
                        _token: '{{ csrf_token() }}',
                        transaksi_id: trxId,
                        barang_baru_kode: kodeBaru,
                        metode_harga_baru: metodeHargaBaru,
                        pembayaran_baru: pembayaranBaru,
                        alasan: alasan
                    }, function(res) {
                        $('#btnProsesRevisi').prop('disabled', false).html('<i data-acorn-icon="save" class="me-1"></i> Proses Revisi');
                        
                        if (res.success) {
                            $('#modalGantiBarang').modal('hide');
                            Swal.fire('Berhasil!', res.message, 'success').then(() => {
                                loadInvoice(); // reload invoice data
                            });
                        } else {
                            Swal.fire('Gagal!', res.message, 'error');
                        }
                    }).fail(function() {
                        $('#btnProsesRevisi').prop('disabled', false).html('<i data-acorn-icon="save" class="me-1"></i> Proses Revisi');
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    });
                }
            });
        });

    });
</script>
@endpush
