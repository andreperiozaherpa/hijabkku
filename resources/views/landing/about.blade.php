@extends('landing.app')

@section('title', 'Tentang Kami - Hijabkku')

@section('content')
    <!-- Header Banner -->
    <section class="py-5 text-center" style="background: linear-gradient(135deg, #ffd1dc 0%, #fff 100%);">
        <div class="container py-4">
            <span class="text-gold fw-bold tracking-wide text-uppercase"
                style="letter-spacing: 2px; font-size: 0.9rem;">CERITA HIJABKKU</span>
            <h1 class="fw-bold display-5 mt-2 mb-2" style="color: #2c2c2c;">Tentang Kami</h1>
            <p class="text-muted lead">Mengenal lebih dekat visi, misi, dan nilai-nilai yang kami bawa untuk Anda.</p>
        </div>
    </section>

    <!-- Company Story Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4 font-serif text-dark" style="font-size: 2rem;">Awal Mula Perjalanan Kami</h2>
                    <p class="text-muted" style="line-height: 1.8;">
                        Didirikan dengan kecintaan mendalam pada fashion Muslimah, <strong>Hijabkku</strong> hadir untuk
                        mendefinisikan ulang cara wanita mengenakan hijab. Kami percaya bahwa berhijab tidak hanya tentang
                        menjalankan kewajiban, tetapi juga tentang mengekspresikan kepribadian, keanggunan, dan rasa percaya
                        diri.
                    </p>
                    <p class="text-muted" style="line-height: 1.8;">
                        Kami mengkhususkan diri dalam penyediaan produk hijab berkualitas. Setiap helai hijab kami dikurasi
                        dengan perhatian penuh terhadap detail demi
                        kenyamanan maksimal aktivitas harian Anda.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4 text-center position-relative overflow-hidden"
                        style="border-radius: 20px; background: #faf9f6;">
                        <div class="position-absolute top-0 end-0 bg-gold text-white px-3 py-1 font-serif"
                            style="border-bottom-left-radius: 15px; font-size: 0.8rem;">
                            EST. 2017
                        </div>
                        <img src="/img/logo.png" alt="Hijabku Logo" class="img-fluid mb-4 rounded-circle mx-auto border"
                            style="width: 120px; height: 120px; object-fit: cover;">
                        <h4 class="fw-bold text-gold font-serif mb-2">HIJABKKU</h4>
                        <p class="text-muted small px-3">
                            "Menebar kebaikan dan kecantikan melalui balutan hijab yang elegan dengan sentuhan warna."
                        </p>
                        <div class="d-flex justify-content-around border-top pt-4 mt-3">
                            {{-- <div>
                                <h5 class="fw-bold text-dark mb-0">10k+</h5>
                                <span class="small text-muted">Pelanggan</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">50+</h5>
                                <span class="small text-muted">Varian Warna</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">100%</h5>
                                <span class="small text-muted">Katun Premium</span>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi Section -->
    <section class="py-5" style="background-color: #faf9f6;">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-5">
                    <div class="card h-100 border-0 shadow-sm p-4" style="border-radius: 16px; background: white;">
                        <div class="d-flex align-items-center mb-3">
                            <span
                                class="d-flex align-items-center justify-content-center bg-light text-gold rounded-circle me-3"
                                style="width: 50px; height: 50px;">
                                <i data-acorn-icon="activity" style="font-size: 24px;"></i>
                            </span>
                            <h3 class="fw-bold font-serif mb-0 text-dark" style="font-size: 1.5rem;">Visi Kami</h3>
                        </div>
                        <p class="text-muted mb-0" style="line-height: 1.7;">
                            Menjadi brand hijab lokal terkemuka yang menginspirasi Muslimah Indonesia untuk tampil anggun,
                            modern, dan percaya diri tanpa mengabaikan nilai-nilai syariat Islam melalui produk busana
                            berkualitas tinggi.
                        </p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card h-100 border-0 shadow-sm p-4" style="border-radius: 16px; background: white;">
                        <div class="d-flex align-items-center mb-3">
                            <span
                                class="d-flex align-items-center justify-content-center bg-light text-gold rounded-circle me-3"
                                style="width: 50px; height: 50px;">
                                <i data-acorn-icon="check" style="font-size: 24px;"></i>
                            </span>
                            <h3 class="fw-bold font-serif mb-0 text-dark" style="font-size: 1.5rem;">Misi Kami</h3>
                        </div>
                        <ul class="text-muted ps-3 mb-0" style="line-height: 1.7;">
                            <li class="mb-2">Menghadirkan hijab berkualitas dengan berbagai varian.</li>
                            <li class="mb-2">Memberikan pelayanan pelanggan yang responsif, ramah, dan solutif.</li>
                            <li class="mb-0">Membangun ekosistem ritel terintegrasi melalui sistem Point of Sale (POS) dan
                                e-commerce modern.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Legal Business Information -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5 pb-2">
                <h2 class="fw-bold font-serif text-dark mb-2">Informasi Legal Usaha</h2>
                <div class="mx-auto" style="width: 60px; height: 3px; background-color: #D4AF37;"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="table-responsive bg-light p-4 rounded-4 border" style="border-radius: 16px;">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr class="border-bottom border-light">
                                    <td class="fw-bold text-muted py-3" style="width: 35%;">Nama Toko</td>
                                    <td class="text-dark py-3 font-weight-bold">Hijabkku</td>
                                </tr>
                                <tr class="border-bottom border-light">
                                    <td class="fw-bold text-muted py-3">Alamat Lengkap Kantor</td>
                                    <td class="text-dark py-3">Kelurahan Panaragan Jaya, Kecamatan Tulang Bawang Tengah,
                                        Kabupaten Tulang Bawang Barat, Provinsi Lampung (Depan Masjid Baitul Sobur)</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted py-3">Metode Operasional</td>
                                    <td class="text-dark py-3">Pemesanan online melalui catalog (Store Pickup) & penjualan
                                        offline di outlet resmi.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
