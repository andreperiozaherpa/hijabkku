@extends('landing.app')

@section('title', 'Kebijakan Privasi - Hijabkku')

@section('content')
    <!-- Header Banner -->
    <section class="py-5 text-center"
        style="background: linear-gradient(135deg, #ffd1dc 0%, #fff 100%); font-family: 'Playfair Display', serif;">
        <div class="container py-4">
            <span class="text-gold fw-bold tracking-wide text-uppercase"
                style="letter-spacing: 2px; font-size: 0.9rem; font-family: 'Montserrat', sans-serif;">KEBIJAKAN
                PRIVASI</span>
            <h1 class="fw-bold display-5 mt-2 mb-2" style="color: #2c2c2c;">Kebijakan Privasi</h1>
            <p class="text-muted lead" style="font-family: 'Montserrat', sans-serif;">Bagaimana kami mengumpulkan,
                menggunakan, dan melindungi data pribadi Anda.</p>
        </div>
    </section>

    <!-- Content Policy -->
    <section class="py-5 bg-white">
        <div class="container" style="max-width: 800px;">
            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #faf9f6;">
                <p class="text-muted small">Terakhir diperbarui: {{ date('d F Y') }}</p>
                <hr class="border-light my-4">

                <h4 class="fw-bold font-serif text-dark mb-3">1. Pendahuluan</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Selamat datang di website resmi Hijabkku. Kami sangat menghargai kepercayaan Anda dan berkomitmen untuk
                    melindungi informasi pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana informasi pribadi Anda
                    dikumpulkan, digunakan, disimpan, dan dilindungi ketika Anda mengunjungi atau melakukan transaksi
                    pembelian di website kami.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">2. Informasi yang Kami Kumpulkan</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Ketika Anda melakukan proses pembelian melalui katalog online kami, kami mengumpulkan beberapa data
                    pribadi yang diperlukan untuk menyelesaikan transaksi, meliputi:
                </p>
                <ul class="text-muted" style="line-height: 1.7;">
                    <li><strong>Nama Lengkap:</strong> Untuk keperluan identifikasi saat pengambilan barang (Store Pickup).
                    </li>
                    <li><strong>Nomor WhatsApp/HP:</strong> Untuk verifikasi pesanan dan notifikasi status transaksi.</li>
                    <li><strong>Alamat Email:</strong> Untuk pengiriman tagihan resmi (invoice) melalui payment gateway
                        terintegrasi.</li>
                    <li><strong>Detail Pesanan:</strong> Jenis barang, variasi, jumlah, dan nilai total pembelian Anda.</li>
                </ul>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">3. Penggunaan Informasi Anda</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Kami menggunakan informasi yang dikumpulkan untuk tujuan-tujuan berikut:
                </p>
                <ul class="text-muted" style="line-height: 1.7;">
                    <li>Memproses transaksi pembelian Anda dan menerbitkan tagihan digital melalui mitra payment gateway
                        kami.</li>
                    <li>Menyediakan notifikasi pembaruan status pesanan Anda.</li>
                    <li>Mengelola administrasi program pengambilan barang di toko (Store Pickup).</li>
                    <li>Mencegah aktivitas penipuan, transaksi mencurigakan, dan penyalahgunaan sistem kami.</li>
                </ul>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">4. Penyimpanan & Keamanan Data</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Kami menerapkan standar keamanan teknis dan organisasional yang ketat untuk memastikan data pribadi Anda
                    aman dari akses tanpa izin, kehilangan, atau penyalahgunaan. Seluruh data transaksi dienkripsi
                    menggunakan protokol Secure Sockets Layer (SSL) selama pengiriman data ke payment gateway resmi. Kami
                    tidak menyimpan data sensitif kartu kredit atau detail perbankan Anda di server kami.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">5. Pengungkapan kepada Pihak Ketiga</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Kami tidak akan menjual, menyewakan, atau memperdagangkan data pribadi Anda kepada pihak ketiga manapun.
                    Kami hanya membagikan informasi Anda kepada pihak ketiga yang berwenang demi memproses pesanan Anda,
                    seperti:
                </p>
                <ul class="text-muted" style="line-height: 1.7;">
                    <li>Mitra Payment Gateway resmi untuk memproses pembayaran Anda secara aman.</li>
                    <li>Otoritas penegak hukum jika diwajibkan oleh peraturan perundang-undangan yang berlaku di Negara
                        Republik Indonesia.</li>
                </ul>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">6. Hak-Hak Pengguna</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Anda memiliki hak penuh untuk meminta akses, melakukan perbaikan, atau meminta penghapusan informasi
                    pribadi Anda dari sistem kami. Silakan hubungi tim perwakilan DPO kami melalui detail kontak di bawah
                    ini untuk mengajukan permohonan tersebut.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">7. Hubungi Kami</h4>
                <p class="text-muted mb-0" style="line-height: 1.7;">
                    Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini atau pengelolaan data pribadi di Hijabkku,
                    silakan hubungi kami di:
                </p>
                <div class="mt-3 p-3 bg-white rounded-3 border">
                    <p class="mb-1 text-dark"><strong>Hijabkku Retail Indonesia</strong></p>
                    <p class="mb-1 text-muted">Email: andreperiozaherpa@gmail.com</p>
                    <p class="mb-0 text-muted">WhatsApp: 082280783843</p>
                </div>
            </div>
        </div>
    </section>
@endsection
