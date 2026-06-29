@extends('landing.app')

@section('title', 'Kebijakan Privasi - Hijabkku')

@section('content')
    <!-- Header -->
    <section style="padding: 100px 0 80px; background: var(--bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <span class="section-label">Kebijakan</span>
                    <h1 class="section-title">Kebijakan Privasi</h1>
                    <p class="section-desc mx-auto">Bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="section border-top">
        <div class="container" style="max-width: 780px;">
            <div class="card-clean">
                <p style="font-size: 0.82rem; color: var(--text-light); margin-bottom: 24px;">Terakhir diperbarui: {{ date('d F Y') }}</p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">1. Pendahuluan</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Selamat datang di website resmi Hijabkku. Kami berkomitmen untuk melindungi informasi pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana informasi pribadi Anda dikumpulkan, digunakan, disimpan, dan dilindungi.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">2. Informasi yang Kami Kumpulkan</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); margin-bottom: 12px;">Ketika Anda melakukan pembelian, kami mengumpulkan:</p>
                <ul style="padding-left: 18px; margin-bottom: 24px;">
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;"><strong style="color: var(--text);">Nama Lengkap:</strong> Untuk identifikasi saat pengambilan.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;"><strong style="color: var(--text);">Nomor WhatsApp:</strong> Untuk verifikasi dan notifikasi.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;"><strong style="color: var(--text);">Email:</strong> Untuk pengiriman invoice.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;"><strong style="color: var(--text);">Detail Pesanan:</strong> Jenis barang, jumlah, dan nilai pembelian.</li>
                </ul>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">3. Penggunaan Informasi</h4>
                <ul style="padding-left: 18px; margin-bottom: 24px;">
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Memproses transaksi dan menerbitkan tagihan.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Menyediakan notifikasi status pesanan.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Mengelola administrasi Store Pickup.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Mencegah penipuan dan penyalahgunaan sistem.</li>
                </ul>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">4. Keamanan Data</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Kami menerapkan standar keamanan teknis dan organisasional yang ketat. Seluruh data transaksi dienkripsi menggunakan SSL. Kami tidak menyimpan data sensitif kartu kredit atau detail perbankan.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">5. Pengungkapan kepada Pihak Ketiga</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Kami tidak menjual, menyewakan, atau memperdagangkan data pribadi Anda. Informasi hanya dibagikan kepada pihak yang berwenang untuk memproses pesanan.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">6. Hak Pengguna</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Anda memiliki hak untuk meminta akses, perbaikan, atau penghapusan informasi pribadi dari sistem kami.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">7. Hubungi Kami</h4>
                <div class="p-4" style="background: var(--bg-warm); border: 1px solid var(--border-light);">
                    <p style="font-size: 0.92rem; color: var(--text); margin-bottom: 8px;"><strong>Hijabkku Retail Indonesia</strong></p>
                    <p style="font-size: 0.88rem; color: var(--text-sub); margin-bottom: 4px;">Email: andreperiozaherpa@gmail.com</p>
                    <p style="font-size: 0.88rem; color: var(--text-sub); margin: 0;">WhatsApp: 082280783843</p>
                </div>
            </div>
        </div>
    </section>
@endsection
