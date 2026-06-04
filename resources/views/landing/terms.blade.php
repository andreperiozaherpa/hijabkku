@extends('landing.app')

@section('title', 'Syarat & Ketentuan - Hijabkku')

@section('content')
    <!-- Header Banner -->
    <section class="py-5 text-center"
        style="background: linear-gradient(135deg, #ffd1dc 0%, #fff 100%); font-family: 'Playfair Display', serif;">
        <div class="container py-4">
            <span class="text-gold fw-bold tracking-wide text-uppercase"
                style="letter-spacing: 2px; font-size: 0.9rem; font-family: 'Montserrat', sans-serif;">SYARAT &
                KETENTUAN</span>
            <h1 class="fw-bold display-5 mt-2 mb-2" style="color: #2c2c2c;">Syarat & Ketentuan</h1>
            <p class="text-muted lead" style="font-family: 'Montserrat', sans-serif;">Aturan dan panduan penggunaan website
                serta pembelian produk Hijabkku.</p>
        </div>
    </section>

    <!-- Content Terms -->
    <section class="py-5 bg-white">
        <div class="container" style="max-width: 800px;">
            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #faf9f6;">
                <p class="text-muted small">Terakhir diperbarui: {{ date('d F Y') }}</p>
                <hr class="border-light my-4">

                <h4 class="fw-bold font-serif text-dark mb-3">1. Ketentuan Umum</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Dengan mengakses, menelusuri, dan menggunakan website Hijabkku ini, Anda menyatakan bahwa Anda telah
                    membaca, memahami, dan setuju untuk terikat oleh Syarat dan Ketentuan ini. Jika Anda tidak menyetujui
                    seluruh ketentuan di dalam halaman ini, Anda tidak diperkenankan menggunakan layanan kami.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">2. Ketentuan Pembelian & Transaksi</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Pembelian produk di website kami tunduk pada ketentuan berikut:
                </p>
                <ul class="text-muted" style="line-height: 1.7;">
                    <li>Semua produk yang ditawarkan di website kami tunduk pada ketersediaan stok aktual di masing-masing
                        cabang toko terpilih.</li>
                    <li>Harga yang tertera adalah harga mutlak dalam Rupiah (IDR) dan dapat berubah sewaktu-waktu tanpa
                        pemberitahuan sebelumnya.</li>
                    <li>Kesalahan input data pemesanan (nama, nomor HP, email) oleh pembeli sepenuhnya menjadi tanggung
                        jawab pihak pembeli.</li>
                </ul>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">3. Metode Pembayaran</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Kami menggunakan payment gateway resmi untuk memproses seluruh transaksi pembayaran online. Kami
                    mendukung metode pembayaran menggunakan QRIS, Virtual Account (VA), dan E-Wallet. Seluruh proses
                    pembayaran tunduk pada kebijakan dan ketentuan sistem verifikasi payment gateway. Pesanan baru akan
                    diproses setelah sistem kami menerima notifikasi pelunasan secara real-time dari payment gateway.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">4. Kebijakan Store Pickup (Pengambilan di Toko)</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Layanan pemesanan melalui website kami saat ini menggunakan metode <strong>Store Pickup (Ambil di
                        Toko)</strong>:
                </p>
                <ul class="text-muted" style="line-height: 1.7;">
                    <li>Pembeli wajib mengambil pesanan di cabang outlet terpilih yang tertera pada invoice pembelian.</li>
                    <li>Saat pengambilan, pembeli wajib menunjukkan bukti invoice lunas asli yang dikirimkan ke email atau
                        WhatsApp.</li>
                    <li>Batas maksimal pengambilan barang adalah 7 (tujuh) hari kalender sejak status pembayaran dinyatakan
                        lunas. Pesanan yang tidak diambil melebihi batas waktu tersebut tidak dapat dibatalkan, namun kami
                        akan berupaya mengontak pembeli.</li>
                </ul>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">5. Hak & Kewajiban Pengguna</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Pengguna berkewajiban memberikan data pribadi yang valid dan akurat demi kelancaran proses transaksi.
                    Pengguna dilarang keras melakukan manipulasi sistem checkout, melakukan tindakan penipuan transaksi,
                    atau menggunakan layanan kami untuk tujuan ilegal yang melanggar hukum Negara Republik Indonesia.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">6. Batasan Tanggung Jawab</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Hijabkku tidak bertanggung jawab atas kerugian tidak langsung atau masalah teknis yang disebabkan oleh
                    gangguan jaringan internet pembeli, kegagalan sistem pembayaran perbankan/e-wallet, atau keadaan memaksa
                    (Force Majeure) seperti bencana alam, pemadaman listrik massal, dan kebijakan darurat pemerintah.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">7. Hukum yang Berlaku</h4>
                <p class="text-muted mb-0" style="line-height: 1.7;">
                    Syarat & Ketentuan ini diatur dan ditafsirkan sesuai dengan hukum yang berlaku di Negara Republik
                    Indonesia. Setiap perselisihan yang timbul dari syarat ketentuan ini akan diselesaikan secara musyawarah
                    mufakat atau melalui yurisdiksi pengadilan negeri terdekat.
                </p>
            </div>
        </div>
    </section>
@endsection
