@extends('landing.app')

@section('title', 'Kebijakan Pengembalian & Refund - Hijabkku')

@section('content')
    <!-- Header Banner -->
    <section class="py-5 text-center"
        style="background: linear-gradient(135deg, #ffd1dc 0%, #fff 100%); font-family: 'Playfair Display', serif;">
        <div class="container py-4">
            <span class="text-gold fw-bold tracking-wide text-uppercase"
                style="letter-spacing: 2px; font-size: 0.9rem; font-family: 'Montserrat', sans-serif;">KEBIJAKAN REFUND &
                RETUR</span>
            <h1 class="fw-bold display-5 mt-2 mb-2" style="color: #2c2c2c;">Kebijakan Pengembalian</h1>
            <p class="text-muted lead" style="font-family: 'Montserrat', sans-serif;">Syarat, tata cara, dan estimasi waktu
                pengembalian barang atau dana (refund).</p>
        </div>
    </section>

    <!-- Content Refund Policy -->
    <section class="py-5 bg-white">
        <div class="container" style="max-width: 800px;">
            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #faf9f6;">
                <p class="text-muted small">Terakhir diperbarui: {{ date('d F Y') }}</p>
                <hr class="border-light my-4">

                <h4 class="fw-bold font-serif text-dark mb-3">1. Jaminan Kualitas</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Di Hijabkku, kami berkomitmen untuk selalu menghadirkan produk dengan kualitas terbaik demi kepuasan
                    Anda. Namun jika produk yang Anda terima mengalami cacat produksi atau terjadi kesalahan pengiriman oleh
                    tim kami, kami menyediakan layanan pengembalian barang (retur) dan pengembalian dana (refund) secara
                    penuh.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">2. Syarat Pengajuan Retur & Refund</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Pengajuan pengembalian barang dan dana hanya akan diproses apabila memenuhi seluruh kriteria berikut:
                </p>
                <ul class="text-muted" style="line-height: 1.7;">
                    <li><strong>Waktu Pengajuan:</strong> Maksimal 2 x 24 jam terhitung sejak pesanan diambil (Store Pickup)
                        atau diterima.</li>
                    <li><strong>Kondisi Barang:</strong> Barang wajib dalam kondisi belum dicuci, hangtag label masih
                        terpasang utuh, tidak ada aroma parfum tambahan, dan tidak rusak karena kesalahan pemakaian pembeli.
                    </li>
                </ul>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">3. Kasus yang Disetujui untuk Retur/Refund</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Kami menyetujui pengembalian untuk kondisi berikut:
                </p>
                <ul class="text-muted" style="line-height: 1.7;">
                    <li>Terdapat robekan kain, noda permanen, jahitan terlepas, atau cacat bahan bawaan pabrik (Cacat
                        Produksi).</li>
                    <li>Produk yang dikirimkan berbeda dengan detail pesanan di invoice (Salah Varian Warna/Ukuran/Model).
                    </li>
                </ul>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">4. Alur & Proses Pengajuan</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Silakan ikuti langkah-langkah berikut untuk mengajukan retur atau refund:
                </p>
                <ol class="text-muted" style="line-height: 1.7;">
                    <li>Kirimkan bukti invoice lunas dan video unboxing utuh ke CS WhatsApp kami di
                        <strong>082280783843</strong> atau email kami di <strong>andreperiozaherpa@gmail.com</strong>.
                    </li>
                    <li>Tunggu konfirmasi verifikasi dari tim kami (maksimal 1 x 24 jam kerja).</li>
                    <li>Setelah pengajuan disetujui, pembeli dapat membawa barang tersebut kembali ke outlet cabang pickup
                        asli atau mengirimkannya via kurir logistik (ongkos kirim sepenuhnya ditanggung oleh Hijabkku jika
                        kesalahan di pihak kami).</li>
                    <li>Kami akan mengirimkan barang pengganti yang sesuai atau memproses pengembalian dana (refund).</li>
                </ol>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">5. Estimasi Waktu Penyelesaian</h4>
                <p class="text-muted" style="line-height: 1.7;">
                    Proses pengiriman barang pengganti akan dilakukan paling lambat 2 (dua) hari kerja setelah barang retur
                    kami terima di outlet. Untuk opsi pengembalian dana (refund), transfer dana akan diselesaikan dalam
                    kurun waktu 3 sampai 5 hari kerja ke rekening bank asal atau e-wallet yang ditunjuk oleh pembeli secara
                    resmi.
                </p>

                <h4 class="fw-bold font-serif text-dark mt-4 mb-3">6. Hubungi Support</h4>
                <p class="text-muted mb-0" style="line-height: 1.7;">
                    Apabila ada kendala atau pertanyaan lanjutan seputar kebijakan ini, jangan ragu untuk menghubungi tim
                    bantuan kami:
                </p>
                <div class="mt-3 p-3 bg-white rounded-3 border">
                    <p class="mb-1 text-dark"><strong>Layanan Pelanggan Hijabkku</strong></p>
                    <p class="mb-1 text-muted">WhatsApp CS: 082280783843</p>
                    <p class="mb-0 text-muted">Email Support: andreperiozaherpa@gmail.com</p>
                </div>
            </div>
        </div>
    </section>
@endsection
