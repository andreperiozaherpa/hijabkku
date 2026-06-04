@extends('landing.app')

@section('title', 'Hubungi Kami - Hijabkku')

@section('content')
    <!-- Header Banner -->
    <section class="py-5 text-center"
        style="background: linear-gradient(135deg, #ffd1dc 0%, #fff 100%); font-family: 'Playfair Display', serif;">
        <div class="container py-4">
            <span class="text-gold fw-bold tracking-wide text-uppercase"
                style="letter-spacing: 2px; font-size: 0.9rem; font-family: 'Montserrat', sans-serif;">HUBUNGI KAMI</span>
            <h1 class="fw-bold display-5 mt-2 mb-2" style="color: #2c2c2c;">Hubungi Kami</h1>
            <p class="text-muted lead" style="font-family: 'Montserrat', sans-serif;">Kami siap membantu dan menjawab
                pertanyaan Anda seputar produk dan layanan kami.</p>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4 mb-5">
                <!-- WhatsApp/Phone Card -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-4 text-center"
                        style="border-radius: 16px; background: #faf9f6;">
                        <span
                            class="d-flex align-items-center justify-content-center bg-white text-gold rounded-circle mx-auto mb-3 shadow-sm"
                            style="width: 60px; height: 60px;">
                            <i data-acorn-icon="phone" style="font-size: 24px;"></i>
                        </span>
                        <h4 class="fw-bold font-serif mb-2 text-dark">WhatsApp / Telepon</h4>
                        <p class="text-muted small mb-3">Hubungi customer support kami secara langsung via aplikasi
                            WhatsApp.</p>
                        <a href="https://wa.me/6282280783843" target="_blank"
                            class="btn btn-gold btn-sm px-4 rounded-pill mt-auto">
                            0822 8078 3843
                        </a>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-4 text-center"
                        style="border-radius: 16px; background: #faf9f6;">
                        <span
                            class="d-flex align-items-center justify-content-center bg-white text-gold rounded-circle mx-auto mb-3 shadow-sm"
                            style="width: 60px; height: 60px;">
                            <i data-acorn-icon="email" style="font-size: 24px;"></i>
                        </span>
                        <h4 class="fw-bold font-serif mb-2 text-dark">Customer Support Email</h4>
                        <p class="text-muted small mb-3">Kirimkan penawaran kerjasama, kritik, atau pertanyaan formal
                            melalui email.</p>
                        <a href="mailto:andreperiozaherpa@gmail.com"
                            class="text-gold font-weight-bold text-decoration-none mt-auto">
                            andreperiozaherpa@gmail.com
                        </a>
                    </div>
                </div>

                <!-- Jam Operasional Card -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-4 text-center"
                        style="border-radius: 16px; background: #faf9f6;">
                        <span
                            class="d-flex align-items-center justify-content-center bg-white text-gold rounded-circle mx-auto mb-3 shadow-sm"
                            style="width: 60px; height: 60px;">
                            <i data-acorn-icon="clock" style="font-size: 24px;"></i>
                        </span>
                        <h4 class="fw-bold font-serif mb-2 text-dark">Jam Operasional</h4>
                        <p class="text-muted small mb-3">Jam pelayanan toko online, store pickup, dan tanggapan support
                            pelanggan.</p>
                        <span class="fw-bold text-dark mt-auto">Senin – Sabtu<br>08.00 – 20.00 WIB</span>
                    </div>
                </div>
            </div>

            <!-- Address and Iframe Map -->
            <div class="row g-4 align-items-stretch">
                <!-- Address detail -->
                <div class="col-lg-5">
                    <div class="card h-100 border-0 shadow-sm p-4" style="border-radius: 16px; background: #faf9f6;">
                        <h3 class="fw-bold font-serif text-dark mb-4"><i data-acorn-icon="shop"
                                class="text-gold me-2"></i>Kantor & Outlet Utama</h3>

                        <h6 class="fw-bold text-gold mb-1">Alamat Lengkap:</h6>
                        <p class="text-muted mb-4" style="line-height: 1.7;">
                            Kelurahan Panaragan Jaya, Kecamatan Tulang Bawang Tengah, Kabupaten Tulang Bawang Barat,
                            Provinsi Lampung. (Depan Masjid Baitul Sobur Tulang Bawang Barat)
                        </p>

                        <h6 class="fw-bold text-gold mb-1">Pengambilan Pesanan (Store Pickup):</h6>
                        <p class="text-muted mb-4 small" style="line-height: 1.6;">
                            Untuk pesanan yang dibeli via online catalog, Anda dapat melakukan pengambilan pesanan secara
                            offline di outlet resmi kami setelah transaksi dinyatakan lunas oleh payment gateway.
                        </p>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="https://wa.me/6282280783843" target="_blank"
                                class="btn btn-gold w-100 rounded-pill py-2.5">
                                <i data-acorn-icon="message" class="me-2"></i>Chat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Google Maps/Visual Map Widget -->
                <div class="col-lg-7">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden"
                        style="border-radius: 16px; min-height: 350px;">
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
