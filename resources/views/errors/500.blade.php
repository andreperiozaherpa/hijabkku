@extends('landing.app')

@section('title', '500 - Masalah Internal Server | Hijabkku')

@section('content')
    <section class="py-5 my-5 text-center" style="background: radial-gradient(circle, #fff0f5 0%, #ffffff 100%);">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center bg-white text-danger rounded-circle shadow-sm" style="width: 100px; height: 100px;">
                            <i data-acorn-icon="shield" style="font-size: 45px;"></i>
                        </span>
                    </div>
                    <h1 class="display-1 fw-bold text-danger font-serif">500</h1>
                    <h3 class="fw-bold mb-3 font-serif text-dark">Masalah Internal Server</h3>
                    <p class="text-muted mb-5 leading-relaxed" style="line-height: 1.8;">
                        Maaf, terjadi kesalahan tak terduga pada server kami. 
                        Tim teknis kami sedang berusaha menyelesaikannya secepat mungkin. Silakan coba beberapa saat lagi.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="/" class="btn btn-gold rounded-pill px-4 py-2.5 text-uppercase small" style="font-size: 0.8rem; letter-spacing: 0.5px;">Kembali ke Beranda</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 text-uppercase small" style="font-size: 0.8rem; letter-spacing: 0.5px;">Hubungi Support</a>
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
