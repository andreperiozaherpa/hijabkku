@extends('landing.app')

@section('title', 'Kebijakan Pengembalian & Refund - Hijabkku')

@section('content')
    <!-- Header -->
    <section style="padding: 100px 0 80px; background: var(--bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <span class="section-label">Kebijakan</span>
                    <h1 class="section-title">Kebijakan Pengembalian</h1>
                    <p class="section-desc mx-auto">Syarat, tata cara, dan estimasi waktu pengembalian barang atau dana.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="section border-top">
        <div class="container" style="max-width: 780px;">
            <div class="card-clean">
                <p style="font-size: 0.82rem; color: var(--text-light); margin-bottom: 24px;">Terakhir diperbarui: {{ date('d F Y') }}</p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">1. Jaminan Kualitas</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Di Hijabkku, kami berkomitmen menghadirkan produk berkualitas terbaik. Jika produk mengalami cacat produksi atau kesalahan pengiriman, kami menyediakan layanan retur dan refund.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">2. Syarat Pengajuan Retur & Refund</h4>
                <ul style="padding-left: 18px; margin-bottom: 24px;">
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;"><strong style="color: var(--text);">Waktu Pengajuan:</strong> Maksimal 2 x 24 jam sejak pesanan diambil atau diterima.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;"><strong style="color: var(--text);">Kondisi Barang:</strong> Belum dicuci, hangtag terpasang utuh, tanpa aroma parfum.</li>
                </ul>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">3. Kasus yang Disetujui</h4>
                <ul style="padding-left: 18px; margin-bottom: 24px;">
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Cacat produksi (robekan, noda permanen, jahitan terlepas).</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Produk berbeda dengan detail pesanan di invoice.</li>
                </ul>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">4. Alur Pengajuan</h4>
                <ol style="padding-left: 18px; margin-bottom: 24px;">
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Kirim bukti invoice dan video unboxing ke CS WhatsApp <strong style="color: var(--text);">082280783843</strong>.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Tunggu konfirmasi verifikasi (maksimal 1 x 24 jam kerja).</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Kembalikan barang ke outlet atau via kurir.</li>
                    <li style="font-size: 0.92rem; color: var(--text-sub); padding: 4px 0;">Kami kirim barang pengganti atau proses refund.</li>
                </ol>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">5. Estimasi Waktu</h4>
                <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 24px;">
                    Pengiriman barang pengganti paling lambat 2 hari kerja. Refund diselesaikan dalam 3-5 hari kerja.
                </p>

                <h4 style="font-size: 1.2rem; color: var(--text); margin-bottom: 16px;">6. Hubungi Support</h4>
                <div class="p-4" style="background: var(--bg-warm); border: 1px solid var(--border-light);">
                    <p style="font-size: 0.92rem; color: var(--text); margin-bottom: 8px;"><strong>Layanan Pelanggan Hijabkku</strong></p>
                    <p style="font-size: 0.88rem; color: var(--text-sub); margin-bottom: 4px;">WhatsApp: 082280783843</p>
                    <p style="font-size: 0.88rem; color: var(--text-sub); margin: 0;">Email: andreperiozaherpa@gmail.com</p>
                </div>
            </div>
        </div>
    </section>
@endsection
