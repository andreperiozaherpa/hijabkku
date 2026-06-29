@extends('landing.app')

@section('title', 'Hubungi Kami - Hijabkku')

@section('content')
    <!-- Header -->
    <section style="padding: 100px 0 80px; background: var(--bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <span class="section-label">Kontak</span>
                    <h1 class="section-title">Hubungi Kami</h1>
                    <p class="section-desc mx-auto">Kami siap membantu menjawab pertanyaan Anda seputar produk dan layanan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Cards -->
    <section class="section border-top">
        <div class="container">
            <div class="row g-4 mb-5">
                <!-- WhatsApp -->
                <div class="col-md-4">
                    <div class="card-clean h-100 text-center" style="padding: 44px 28px;">
                        <div class="card-icon mx-auto">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                        </div>
                        <h5 style="font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 500; color: var(--text); margin-bottom: 10px;">WhatsApp</h5>
                        <p style="font-size: 0.85rem; color: var(--text-sub); margin-bottom: 20px;">Chat langsung via WhatsApp.</p>
                        <a href="https://wa.me/6282280783843" target="_blank" class="btn-primary px-4 py-2">0822 8078 3843</a>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-4">
                    <div class="card-clean h-100 text-center" style="padding: 44px 28px;">
                        <div class="card-icon mx-auto">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <h5 style="font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 500; color: var(--text); margin-bottom: 10px;">Email</h5>
                        <p style="font-size: 0.85rem; color: var(--text-sub); margin-bottom: 20px;">Kirim pertanyaan via email.</p>
                        <a href="mailto:andreperiozaherpa@gmail.com" class="text-accent" style="font-size: 0.88rem; font-weight: 500;">andreperiozaherpa@gmail.com</a>
                    </div>
                </div>

                <!-- Jam Operasional -->
                <div class="col-md-4">
                    <div class="card-clean h-100 text-center" style="padding: 44px 28px;">
                        <div class="card-icon mx-auto">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <h5 style="font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 500; color: var(--text); margin-bottom: 10px;">Jam Operasional</h5>
                        <p style="font-size: 0.85rem; color: var(--text-sub); margin-bottom: 20px;">Jam pelayanan support.</p>
                        <span style="font-size: 0.88rem; color: var(--text);">Senin - Sabtu<br>08.00 - 20.00 WIB</span>
                    </div>
                </div>
            </div>

            <!-- Address & Map -->
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card-clean h-100">
                        <h3 style="font-size: 1.2rem; color: var(--text); margin-bottom: 24px;">Kantor & Outlet</h3>

                        <h6 style="font-size: 0.75rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Alamat</h6>
                        <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.7; margin-bottom: 24px;">
                            Kelurahan Panaragan Jaya, Kecamatan Tulang Bawang Tengah, Kabupaten Tulang Bawang Barat, Provinsi Lampung. (Depan Masjid Baitul Sobur)
                        </p>

                        <h6 style="font-size: 0.75rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Store Pickup</h6>
                        <p style="font-size: 0.92rem; color: var(--text-sub); line-height: 1.7; margin-bottom: 24px;">
                            Ambil pesanan langsung di outlet kami setelah transaksi lunas.
                        </p>

                        <a href="https://wa.me/6282280783843" target="_blank" class="btn-primary w-100 py-3">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card-clean p-0 overflow-hidden" style="min-height: 350px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d497.16032827014175!2d105.0932495697505!3d-4.543065935237696!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sid!4v1780575807537!5m2!1sen!2sid"
                            width="100%" height="100%" style="border:0; min-height: 350px;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
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
