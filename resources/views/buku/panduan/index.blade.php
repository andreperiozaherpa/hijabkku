@extends('layouts.main')
@section('main')
    <style>
        .panduan-card {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }
        .panduan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08) !important;
        }
        .panduan-card .card-header {
            border-bottom: 1px solid var(--separator-light, #f0f0f0) !important;
            padding: 18px 20px !important;
            border-top-left-radius: 16px !important;
            border-top-right-radius: 16px !important;
            background-color: #f8f9fa;
        }
        .panduan-list-container {
            max-height: 320px;
            overflow-y: auto;
            flex-grow: 1;
        }
        .panduan-list-item {
            padding: 12px 20px !important;
            border-bottom: 1px solid var(--separator-light, #f0f0f0) !important;
            font-size: 0.85rem !important;
            color: var(--body, #2f2f2f) !important;
            transition: background-color 0.15s ease, padding-left 0.15s ease;
            display: flex;
            align-items: center;
        }
        .panduan-list-item:hover {
            background-color: rgba(var(--primary-rgb, 52, 84, 209), 0.03) !important;
            color: var(--primary, #3454d1) !important;
            padding-left: 24px !important;
        }
        .panduan-list-item:last-child {
            border-bottom: none !important;
        }
        .panduan-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .panduan-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .panduan-scrollbar::-webkit-scrollbar-thumb {
            background: var(--separator, #e4e4e4);
            border-radius: 4px;
        }
        .panduan-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--muted, #aeaeae);
        }
        .empty-state {
            font-size: 0.8rem;
            color: var(--muted, #aeaeae);
            text-align: center;
            padding: 24px 0;
        }
    </style>
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container mb-4">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12">
                        <h1 class="mb-1 pb-0 display-4 fw-bold text-primary" id="title">Buku Panduan</h1>
                        <p class="text-muted mb-0">Panduan komponen atribut master produk dan barang.</p>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-4 mb-5">
                <!-- Jenis Card -->
                <div class="col">
                    <div class="card panduan-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i data-acorn-icon="tag" class="text-primary me-2" data-acorn-size="18"></i>
                                <span class="fw-bold text-uppercase text-primary font-heading" style="letter-spacing: 0.5px; font-size: 0.85rem;">Jenis</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ count($jenis) }}</span>
                        </div>
                        <div class="panduan-list-container panduan-scrollbar">
                            @forelse ($jenis as $j)
                                <div class="panduan-list-item text-capitalize">
                                    <i data-acorn-icon="chevron-right" class="text-muted me-2" data-acorn-size="12"></i>
                                    {{ $j->jenis }}
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data jenis</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Merek Card -->
                <div class="col">
                    <div class="card panduan-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i data-acorn-icon="award" class="text-primary me-2" data-acorn-size="18"></i>
                                <span class="fw-bold text-uppercase text-primary font-heading" style="letter-spacing: 0.5px; font-size: 0.85rem;">Merek</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ count($merek) }}</span>
                        </div>
                        <div class="panduan-list-container panduan-scrollbar">
                            @forelse ($merek as $mrk)
                                <div class="panduan-list-item text-capitalize">
                                    <i data-acorn-icon="chevron-right" class="text-muted me-2" data-acorn-size="12"></i>
                                    {{ $mrk->jenis }}
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data merek</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Model Card -->
                <div class="col">
                    <div class="card panduan-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i data-acorn-icon="grid" class="text-primary me-2" data-acorn-size="18"></i>
                                <span class="fw-bold text-uppercase text-primary font-heading" style="letter-spacing: 0.5px; font-size: 0.85rem;">Model</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ count($model) }}</span>
                        </div>
                        <div class="panduan-list-container panduan-scrollbar">
                            @forelse ($model as $mdl)
                                <div class="panduan-list-item text-capitalize">
                                    <i data-acorn-icon="chevron-right" class="text-muted me-2" data-acorn-size="12"></i>
                                    {{ $mdl->jenis }}
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data model</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Bahan Card -->
                <div class="col">
                    <div class="card panduan-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i data-acorn-icon="database" class="text-primary me-2" data-acorn-size="18"></i>
                                <span class="fw-bold text-uppercase text-primary font-heading" style="letter-spacing: 0.5px; font-size: 0.85rem;">Bahan</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ count($bahan) }}</span>
                        </div>
                        <div class="panduan-list-container panduan-scrollbar">
                            @forelse ($bahan as $bhn)
                                <div class="panduan-list-item text-capitalize">
                                    <i data-acorn-icon="chevron-right" class="text-muted me-2" data-acorn-size="12"></i>
                                    {{ $bhn->jenis }}
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data bahan</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Variasi Card -->
                <div class="col">
                    <div class="card panduan-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i data-acorn-icon="palette" class="text-primary me-2" data-acorn-size="18"></i>
                                <span class="fw-bold text-uppercase text-primary font-heading" style="letter-spacing: 0.5px; font-size: 0.85rem;">Variasi</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ count($variasi) }}</span>
                        </div>
                        <div class="panduan-list-container panduan-scrollbar">
                            @forelse ($variasi as $vri)
                                <div class="panduan-list-item text-capitalize">
                                    <i data-acorn-icon="chevron-right" class="text-muted me-2" data-acorn-size="12"></i>
                                    {{ $vri->jenis }}
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data variasi</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Ukuran Card -->
                <div class="col">
                    <div class="card panduan-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i data-acorn-icon="ruler" class="text-primary me-2" data-acorn-size="18"></i>
                                <span class="fw-bold text-uppercase text-primary font-heading" style="letter-spacing: 0.5px; font-size: 0.85rem;">Ukuran</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ count($ukuran) }}</span>
                        </div>
                        <div class="panduan-list-container panduan-scrollbar">
                            @forelse ($ukuran as $uk)
                                <div class="panduan-list-item text-capitalize">
                                    <i data-acorn-icon="chevron-right" class="text-muted me-2" data-acorn-size="12"></i>
                                    {{ $uk->jenis }}
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data ukuran</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Packaging Card -->
                <div class="col">
                    <div class="card panduan-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i data-acorn-icon="box" class="text-primary me-2" data-acorn-size="18"></i>
                                <span class="fw-bold text-uppercase text-primary font-heading" style="letter-spacing: 0.5px; font-size: 0.85rem;">Packaging</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ count($packaging) }}</span>
                        </div>
                        <div class="panduan-list-container panduan-scrollbar">
                            @forelse ($packaging as $pack)
                                <div class="panduan-list-item text-capitalize">
                                    <i data-acorn-icon="chevron-right" class="text-muted me-2" data-acorn-size="12"></i>
                                    {{ $pack->jenis }}
                                </div>
                            @empty
                                <div class="empty-state">Belum ada data packaging</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content End -->
        </div>
    </main>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            // Replace Acorn icons
            if (typeof AcornIcons !== 'undefined') {
                new AcornIcons().replace();
            }
        });
    </script>
@endpush
