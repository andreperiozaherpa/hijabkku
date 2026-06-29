@extends('landing.app')

@section('title', 'Tentang Kami - Hijabkku')

@section('content')
    <!-- Header -->
    <section style="padding: 100px 0 80px; background: var(--bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <span class="section-label">Cerita Hijabkku</span>
                    <h1 class="section-title">Tentang Kami</h1>
                    <p class="section-desc mx-auto">Mengenal lebih dekat visi, misi, dan nilai-nilai yang kami bawa untuk Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="section border-top">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="section-label">Perjalanan Kami</span>
                    <h2 class="section-title">Awal Mula Perjalanan</h2>
                    <div class="section-line"></div>
                    <p style="font-size: 0.95rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 16px;">
                        Didirikan dengan kecintaan mendalam pada fashion Muslimah, <strong style="color: var(--text);">Hijabkku</strong> hadir untuk mendefinisikan ulang cara wanita mengenakan hijab.
                    </p>
                    <p style="font-size: 0.95rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 0;">
                        Kami mengkhususkan diri dalam penyediaan produk hijab berkualitas. Setiap helai hijab kami dikurasi dengan perhatian penuh terhadap detail demi kenyamanan maksimal aktivitas harian Anda.
                    </p>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="card-clean text-center" style="padding: 48px 32px;">
                        <div style="display: inline-block; padding: 6px 14px; border: 1px solid var(--border); font-size: 0.7rem; font-weight: 500; letter-spacing: 1px; color: var(--text-light); margin-bottom: 28px;">EST. 2017</div>
                        <img src="/img/logo.png" alt="Hijabkku" style="width: 90px; height: 90px; object-fit: cover; border: 1px solid var(--border); padding: 8px; margin-bottom: 24px;">
                        <h4 style="font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; letter-spacing: 2px; color: var(--text); margin-bottom: 12px;">HIJABKKU</h4>
                        <p style="font-size: 0.88rem; color: var(--text-sub); font-style: italic; margin: 0;">
                            "Menebar kebaikan dan kecantikan melalui balutan hijab yang elegan."
                        </p>
                        <div style="border-top: 1px solid var(--border-light); margin-top: 28px; padding-top: 24px; display: flex; justify-content: center; gap: 48px;">
                            <div>
                                <div style="font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: var(--text);">2017</div>
                                <div style="font-size: 0.75rem; color: var(--text-light);">Tahun Berdiri</div>
                            </div>
                            <div>
                                <div style="font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: var(--text);">100%</div>
                                <div style="font-size: 0.75rem; color: var(--text-light);">Katun Premium</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi -->
    <section class="section" style="background: var(--bg-warm); border-top: 1px solid var(--border-light);">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-label">Visi & Misi</span>
                <h2 class="section-title">Tujuan Kami</h2>
                <div class="section-line mx-auto"></div>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <div class="card-clean h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="card-icon me-3">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                            </div>
                            <h3 style="font-size: 1.2rem; color: var(--text); margin: 0;">Visi Kami</h3>
                        </div>
                        <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.8; margin: 0;">
                            Menjadi brand hijab lokal terkemuka yang menginspirasi Muslimah Indonesia untuk tampil anggun, modern, dan percaya diri melalui produk berkualitas tinggi.
                        </p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card-clean h-100">
                        <div class="d-flex align-items-center mb-4">
                            <div class="card-icon me-3">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <h3 style="font-size: 1.2rem; color: var(--text); margin: 0;">Misi Kami</h3>
                        </div>
                        <ul style="padding-left: 18px; margin: 0;">
                            <li style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.6; padding: 4px 0;">Menghadirkan hijab berkualitas dengan berbagai varian.</li>
                            <li style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.6; padding: 4px 0;">Memberikan pelayanan pelanggan yang responsif dan ramah.</li>
                            <li style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.6; padding: 4px 0;">Membangun ekosistem ritel terintegrasi melalui POS dan e-commerce.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Legal Information -->
    <section class="section border-top">
        <div class="container" style="max-width: 780px;">
            <div class="text-center mb-5">
                <span class="section-label">Informasi Legal</span>
                <h2 class="section-title">Informasi Usaha</h2>
                <div class="section-line mx-auto"></div>
            </div>

            <div class="card-clean">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td class="py-4" style="width: 35%; font-size: 0.82rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Nama Toko</td>
                            <td class="py-4" style="color: var(--text);">Hijabkku</td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border-light);">
                            <td class="py-4" style="font-size: 0.82rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Alamat</td>
                            <td class="py-4" style="color: var(--text); line-height: 1.7;">Kelurahan Panaragan Jaya, Kecamatan Tulang Bawang Tengah, Kabupaten Tulang Bawang Barat, Provinsi Lampung</td>
                        </tr>
                        <tr>
                            <td class="py-4" style="font-size: 0.82rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Metode Operasional</td>
                            <td class="py-4" style="color: var(--text);">Pemesanan online melalui catalog (Store Pickup) & penjualan offline di outlet resmi.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AcornIcons !== 'undefined') {
                new AcornIcons().replace();
            }
        });
    </script>
@endsection
