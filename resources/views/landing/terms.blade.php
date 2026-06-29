@extends('landing.app')

@section('title', 'Syarat & Ketentuan - Hijabkku')

@section('content')
    <!-- Header -->
    <section style="padding: 100px 0 80px; background: var(--bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <span class="section-label">Kebijakan</span>
                    <h1 class="section-title">Syarat & Ketentuan</h1>
                    <p class="section-desc mx-auto">Aturan dan panduan penggunaan website serta pembelian produk.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="section border-top">
        <div class="container" style="max-width: 780px;">
            <div class="card-clean">
                <p style="font-size: 0.82rem; color: var(--text-light); margin-bottom: 24px;">Terakhir diperbarui: {{ date('d F Y') }}</p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">1. Ketentuan Umum</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Dengan mengakses website Hijabkku, Anda menyatakan telah membaca, memahami, dan setuju untuk terikat oleh Syarat dan Ketentuan ini.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">2. Pembelian & Transaksi</h4>
                <ul style="padding-left: 18px; margin-bottom: 24px;">
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Produk tunduk pada ketersediaan stok aktual di masing-masing cabang.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Harga yang tertera adalah harga final dalam Rupiah.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Kesalahan input data menjadi tanggung jawab pembeli.</li>
                </ul>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">3. Metode Pembayaran</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Kami menggunakan payment gateway resmi. Mendukung QRIS, Virtual Account, dan E-Wallet. Pesanan baru diproses setelah notifikasi pelunasan diterima.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">4. Store Pickup</h4>
                <ul style="padding-left: 18px; margin-bottom: 24px;">
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Pembeli wajib mengambil pesanan di cabang outlet terpilih.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Tunjukkan bukti invoice lunas saat pengambilan.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Batas pengambilan: 7 hari kalender sejak pembayaran lunas.</li>
                </ul>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">5. Hak & Kewajiban Pengguna</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Pengguna wajib memberikan data yang valid. Dilarang melakukan manipulasi sistem checkout atau penipuan transaksi.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">6. Batasan Tanggung Jawab</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Hijabkku tidak bertanggung jawab atas gangguan jaringan internet, kegagalan sistem pembayaran, atau Force Majeure.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">7. Hukum yang Berlaku</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin: 0;">
                    Syarat & Ketentuan ini diatur sesuai hukum Negara Republik Indonesia. Perselisihan akan diselesaikan secara musyawarah atau melalui pengadilan negeri terdekat.
                </p>
            </div>
        </div>
    </section>
@endsection
