@extends('layouts.main')

@section('main')
    <style>
        /* Card & Layout Styles */
        .premium-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .premium-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        /* Image Previews */
        .photo-thumbnail {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            cursor: zoom-in;
        }

        .photo-thumbnail:hover {
            transform: scale(1.1);
            border-color: #8b5cf6;
        }

        .modal-photo-preview {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #cbd5e1;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.06);
        }

        /* Dropzone premium styling */
        .dropzone {
            border: 2px dashed #a78bfa;
            background: rgba(245, 243, 255, 0.4);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 20px;
        }

        .dropzone:hover {
            border-color: #7c3aed;
            background: rgba(237, 233, 254, 0.6);
            transform: translateY(-2px);
        }

        .dropzone.dz-drag-hover {
            border-style: solid;
            border-color: #7c3aed;
            background: rgba(237, 233, 254, 0.9);
            box-shadow: 0 0 20px rgba(124, 58, 237, 0.15);
            transform: scale(1.01);
        }

        .dropzone.dz-started {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: flex-start;
            gap: 15px;
        }

        .dropzone.dz-started .dz-message {
            display: none !important;
        }

        .dropzone .dz-message {
            margin: 0;
            padding: 2rem;
            text-align: center;
            color: #4b5563;
            transition: all 0.2s ease;
        }

        .dropzone .dz-message .dz-button {
            color: #6d28d9;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
        }

        /* Subtle bounce animation for the cloud icon on hover */
        @keyframes bounce-subtle {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .dropzone:hover .dz-message i {
            animation: bounce-subtle 1.6s infinite ease-in-out;
            color: #7c3aed !important;
        }

        /* Custom Dropzone Preview Styling */
        .dropzone .dz-preview {
            margin: 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .dropzone .dz-preview:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #a78bfa;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 12px;
            overflow: hidden;
            width: 120px;
            height: 120px;
        }

        .dropzone .dz-preview .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .dropzone .dz-preview:hover .dz-image img {
            transform: scale(1.08);
        }

        .dropzone .dz-preview .dz-details {
            padding: 8px;
            font-size: 11px;
            background: rgba(255, 255, 255, 0.95);
        }

        .dropzone .dz-preview .dz-progress {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            height: 6px;
            border-radius: 3px;
            background: #f3f4f6;
            overflow: hidden;
            opacity: 1;
            transition: opacity 0.3s ease;
            z-index: 10;
        }

        .dropzone .dz-preview .dz-progress .dz-upload {
            background: linear-gradient(90deg, #8b5cf6, #6d28d9);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .dropzone .dz-preview .dz-error-message {
            background: #ef4444;
            color: #fff;
            padding: 8px;
            border-radius: 8px;
            font-size: 11px;
        }

        .dropzone .dz-preview .dz-remove {
            display: block;
            text-align: center;
            padding: 6px 0;
            font-size: 11px;
            font-weight: 600;
            color: #ef4444;
            text-decoration: none;
            border-top: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .dropzone .dz-preview .dz-remove:hover {
            color: #b91c1c;
            background: #fef2f2;
        }

        /* Tabs styling */
        .nav-tabs-line .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 600;
            color: #64748b;
            padding: 10px 20px;
            transition: all 0.2s ease;
        }

        .nav-tabs-line .nav-link.active {
            color: #7c3aed;
            border-bottom-color: #7c3aed;
            background: transparent;
        }

        .nav-tabs-line .nav-link:hover:not(.active) {
            color: #475569;
            border-bottom-color: #cbd5e1;
        }

        /* Custom Badges */
        .badge-verified {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
            font-weight: 600;
        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
            font-weight: 600;
        }

        .badge-main {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(109, 40, 217, 0.2);
        }

        /* Glassmorphic Lightbox Styles */
        .modal-photo-preview {
            cursor: zoom-in;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-photo-preview:hover {
            transform: scale(1.06);
            border-color: #8b5cf6 !important;
            box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2) !important;
        }

        #lightboxModal {
            backdrop-filter: blur(15px);
            background-color: rgba(15, 23, 42, 0.7); /* Deep dark slate with low opacity */
        }

        #lightboxModal .modal-content {
            background: transparent !important;
        }

        #lightboxCarousel {
            background: transparent;
        }

        #lightboxCarousel .carousel-item {
            padding: 20px;
            text-align: center;
        }

        #lightboxCarousel .carousel-item img {
            max-height: 75vh;
            max-width: 100%;
            object-fit: contain;
            border-radius: 12px;
            margin: 0 auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            border: 4px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        /* Float info card at the bottom of the active image */
        .lightbox-caption {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 10px 24px;
            color: #f8fafc;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        #lightboxModal .btn-close-white {
            background-color: rgba(15, 23, 42, 0.75) !important;
            backdrop-filter: blur(4px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            padding: 14px;
            opacity: 0.95;
            transition: all 0.25s ease;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        }

        #lightboxModal .btn-close-white:hover {
            opacity: 1;
            transform: scale(1.1) rotate(90deg);
            background-color: rgba(139, 92, 246, 0.95) !important; /* Brand purple on hover */
            border-color: rgba(255, 255, 255, 0.7);
        }

        #lightboxCarousel .carousel-control-prev,
        #lightboxCarousel .carousel-control-next {
            width: 56px;
            height: 56px;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.75); /* Dark slate background */
            backdrop-filter: blur(4px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            margin: 0 20px;
            transition: all 0.25s ease;
            opacity: 0.95;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        }

        #lightboxCarousel .carousel-control-prev-icon,
        #lightboxCarousel .carousel-control-next-icon {
            width: 24px;
            height: 24px;
        }

        #lightboxCarousel .carousel-control-prev:hover,
        #lightboxCarousel .carousel-control-next:hover {
            opacity: 1;
            background: rgba(139, 92, 246, 0.95); /* Brand purple on hover */
            border-color: rgba(255, 255, 255, 0.7);
            transform: translateY(-50%) scale(1.1);
        }
    </style>

    <main>
        <div class="container">
            <!-- Title and Subtitle -->
            <div class="page-title-container mb-4">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-1 pb-0 display-4" id="title">Foto & Deskripsi Produk</h1>
                        <p class="text-muted mb-0">Kelola foto dan deskripsi produk Anda secara terpusat, lakukan verifikasi, dan atur foto
                            utama.</p>
                    </div>
                </div>
            </div>

            <!-- Products List Table -->
            <div class="card premium-card mb-5">
                <div class="card-body p-4">
                    <div class="data-table-responsive-wrapper">
                        <table class="table table-hover align-middle" id="tbProdukFoto" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase" style="width: 5%">No</th>
                                    <th class="text-muted text-small text-uppercase" style="width: 15%">Kode Produk</th>
                                    <th class="text-muted text-small text-uppercase" style="width: 30%">Nama Produk</th>
                                    <th class="text-muted text-small text-uppercase" style="width: 15%">Foto Utama</th>
                                    <th class="text-muted text-small text-uppercase" style="width: 15%">Total Foto</th>
                                    <th class="text-muted text-small text-uppercase" style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Upload and Manage Modal -->
    <div class="modal fade" id="uploadPhotoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header bg-light border-0 py-3 px-4">
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="uploadPhotoModalLabel">Kelola Foto Produk</h5>
                        <p class="text-muted small mb-0 id-subtitle">Unggah, verifikasi, atau atur foto produk</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-line card-header-tabs mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab"
                                data-bs-target="#upload-panel" type="button" role="tab" aria-controls="upload-panel"
                                aria-selected="true">
                                <i data-acorn-icon="upload" class="me-1" data-acorn-size="14"></i> Unggah Foto Baru
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery-panel"
                                type="button" role="tab" aria-controls="gallery-panel" aria-selected="false">
                                <i data-acorn-icon="image" class="me-1" data-acorn-size="14"></i> Galeri & Histori
                                Unggahan
                            </button>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Upload Panel -->
                        <div class="tab-pane fade show active" id="upload-panel" role="tabpanel"
                            aria-labelledby="upload-tab">
                            <div class="p-2">
                                <div id="product-photos-dropzone" class="dropzone">
                                    <div class="dz-message" data-dz-message>
                                        <i data-acorn-icon="upload-cloud" class="text-primary mb-3"
                                            data-acorn-size="40"></i>
                                        <div><span class="dz-button">Tarik berkas foto ke sini atau klik untuk
                                                memilih</span></div>
                                        <div class="text-muted small mt-2">Mendukung format JPG, PNG, WEBP (Maksimal 5MB
                                            per berkas).</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery & History Panel -->
                        <div class="tab-pane fade" id="gallery-panel" role="tabpanel" aria-labelledby="gallery-tab">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle" id="modalPhotosTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th style="width: 15%">Preview</th>
                                            <th style="width: 20%">Pengunggah</th>
                                            <th style="width: 25%">Waktu Unggah</th>
                                            <th style="width: 15%">Status</th>
                                            <th style="width: 20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalPhotosBody">
                                        <!-- Dynamic content -->
                                    </tbody>
                                </table>

                                <!-- Empty state inside modal -->
                                <div id="modal-empty-state" class="text-center py-5" style="display: none;">
                                    <i data-acorn-icon="picture" class="text-muted mb-2" data-acorn-size="48"></i>
                                    <h5 class="fw-bold text-dark mb-1">Belum Ada Foto</h5>
                                    <p class="text-muted small mb-0">Gunakan tab unggah di sebelah untuk menambahkan foto
                                        produk ini.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Description Modal -->
    <div class="modal fade" id="manageDescriptionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="manageDescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header bg-light border-0 py-3 px-4">
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="manageDescriptionModalLabel">Kelola Deskripsi Produk</h5>
                        <p class="text-muted small mb-0 desc-modal-subtitle">Atur deskripsi detail produk</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="description-form" class="p-2">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Deskripsi Produk</label>
                            <div class="html-editor-container" style="border-radius: 10px; overflow: hidden; border: 1px solid rgba(0,0,0,0.15);">
                                <div id="product-description-editor" style="height: 300px;"></div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="btn-save-description">
                                <i data-acorn-icon="save" class="me-1" data-acorn-size="14"></i> Simpan Deskripsi
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 p-0 position-absolute top-0 end-0 m-3" style="z-index: 10;">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="lightboxCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
                        <div class="carousel-inner" id="lightboxCarouselInner">
                            <!-- Populated dynamically via JS -->
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Sebelumnya</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Selanjutnya</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/vendor/acorn/js/vendor/dropzone.min.js"></script>
@endsection

@push('script')
    <link rel="stylesheet" href="/vendor/acorn/css/vendor/quill.snow.css">
    <script src="/vendor/acorn/js/vendor/quill.min.js"></script>
    <script>
        // Disable Dropzone auto-discover
        Dropzone.autoDiscover = false;

        const isAdmin = {{ Auth::user()->role === 'admin' ? 'true' : 'false' }};

        $(document).ready(function() {
            // Setup CSRF Token for Ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize Quill Editor
            var quill = new Quill('#product-description-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });

            // Initialize Datatable for Products using Yajra ServerSide
            let table = $('#tbProdukFoto').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/manajemen/barang/foto/data',
                columns: [{
                        data: null,
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'kode',
                        name: 'kode',
                        render: function(data) {
                            return `<span class="badge bg-light text-dark px-2.5 py-1.5 fs-7 fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang',
                        className: 'fw-bold text-dark'
                    },
                    {
                        data: 'foto_utama',
                        name: 'foto_utama',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_foto',
                        name: 'total_foto',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
                paging: true,
                destroy: true,
                responsive: true,
                pageLength: 10,
                sDom: '<"row"<"col-sm-12 col-md-6"f><"col-sm-12 col-md-6 text-end"l>><"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                language: {
                    paginate: {
                        previous: '<i class="cs-chevron-left"></i>',
                        next: '<i class="cs-chevron-right"></i>',
                    },
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ baris",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ produk",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 produk",
                },
                drawCallback: function(settings) {
                    if (typeof AcornIcons !== 'undefined') {
                        new AcornIcons().replace();
                    }
                }
            });

            let selectedProductId = null;
            let hasChanges = false;
            let currentProductFotos = [];

            // Initialize Dropzone
            let myDropzone = new Dropzone("#product-photos-dropzone", {
                url: "/manajemen/barang/foto/upload",
                paramName: "file",
                maxFilesize: 5,
                acceptedFiles: "image/*",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                init: function() {
                    this.on("sending", function(file, xhr, formData) {
                        formData.append("data_barang_id", selectedProductId);
                    });
                    this.on("success", function(file, response) {
                        this.removeFile(file);
                        hasChanges = true;

                        // Show success alert
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: isAdmin ? 'Foto berhasil diunggah.' :
                                'Foto berhasil diunggah. Menunggu verifikasi admin.'
                        });

                        // Reload modal photos gallery
                        loadProductPhotos(selectedProductId);

                        // Switch to gallery and history tab
                        $('#gallery-tab').tab('show');
                    });
                    this.on("error", function(file, message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: typeof message === 'object' ? message.message :
                                message
                        });
                        this.removeFile(file);
                    });
                }
            });

            // Open manage photos modal
            $(document).on('click', '.btn-manage-photos', function() {
                selectedProductId = $(this).attr('data-id');
                let productKode = $(this).attr('data-kode');
                let productName = $(this).attr('data-name');
                hasChanges = false;

                // Set titles
                $('#uploadPhotoModalLabel').text(`Kelola Foto: ${productName}`);
                $('.id-subtitle').text(`Kode Produk: ${productKode}`);

                // Clean dropzone queue
                myDropzone.removeAllFiles(true);

                // Show default upload tab
                $('#upload-tab').tab('show');

                // Load photos
                loadProductPhotos(selectedProductId);

                // Show modal
                $('#uploadPhotoModal').modal('show');
            });

            // Reload table on modal close if changes were made without full page refresh
            $('#uploadPhotoModal').on('hidden.bs.modal', function() {
                if (hasChanges) {
                    table.ajax.reload(null, false);
                    hasChanges = false;
                }
            });

            // Function to load photos via AJAX in modal
            function loadProductPhotos(productId) {
                $.ajax({
                    url: '/manajemen/barang/foto/show/' + productId,
                    type: 'GET',
                    success: function(response) {
                        let tbody = $('#modalPhotosBody');
                        tbody.empty();

                        let fotos = response.fotos;
                        currentProductFotos = fotos; // Save to global variable

                        if (fotos.length > 0) {
                            $('#modal-empty-state').hide();
                            $('#modalPhotosTable').show();

                            fotos.forEach(function(foto, index) {
                                // Format upload time
                                let date = new Date(foto.created_at);
                                let formattedDate = date.toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                // Build Uploader Name & Role
                                let uploaderName = foto.user ? foto.user.name :
                                    '<span class="text-muted small">System / Legacy</span>';
                                let uploaderRole = foto.user ?
                                    `<span class="badge bg-light text-muted fs-8 mt-1">${foto.user.role.toUpperCase()}</span>` :
                                    '';

                                // Build Status Badge
                                let statusBadge = '';
                                if (!foto.is_verified) {
                                    statusBadge =
                                        `<span class="badge badge-pending">Pending Verifikasi</span>`;
                                } else if (foto.is_main) {
                                    statusBadge =
                                        `<span class="badge badge-main"><i class="cs-star me-1"></i> Foto Utama</span>`;
                                } else {
                                    statusBadge =
                                        `<span class="badge badge-verified">Terverifikasi</span>`;
                                }

                                // Build Action buttons
                                let actionButtons = '';

                                // Admin Verify Button
                                if (!foto.is_verified && isAdmin) {
                                    actionButtons += `
                                        <button class="btn btn-sm btn-icon btn-icon-only btn-outline-success btn-verify-photo me-1" data-id="${foto.id}" title="Verifikasi Foto">
                                            <i data-acorn-icon="check" data-acorn-size="13"></i>
                                        </button>
                                    `;
                                }

                                // Set Main Button (Only if verified and not already main)
                                if (foto.is_verified && !foto.is_main) {
                                    actionButtons += `
                                        <button class="btn btn-sm btn-icon btn-icon-only btn-outline-warning btn-set-main me-1" data-id="${foto.id}" title="Set Foto Utama">
                                            <i data-acorn-icon="star" data-acorn-size="13"></i>
                                        </button>
                                    `;
                                }

                                // Delete Button (Always available)
                                actionButtons += `
                                    <button class="btn btn-sm btn-icon btn-icon-only btn-outline-danger btn-delete-photo" data-id="${foto.id}" title="Hapus Foto">
                                        <i data-acorn-icon="bin" data-acorn-size="13"></i>
                                    </button>
                                `;

                                let tr = `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>
                                            <img src="/${foto.path}" class="modal-photo-preview gallery-trigger-img"
                                                 data-index="${index}" style="cursor: zoom-in;" alt="Foto Produk">
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark mb-0">${uploaderName}</div>
                                            ${uploaderRole}
                                        </td>
                                        <td class="text-muted small">${formattedDate}</td>
                                        <td>${statusBadge}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                ${actionButtons}
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                tbody.append(tr);
                            });

                            // Re-render AcornIcons
                            if (typeof AcornIcons !== 'undefined') {
                                new AcornIcons().replace();
                            }
                        } else {
                            $('#modalPhotosTable').hide();
                            $('#modal-empty-state').show();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat foto produk.'
                        });
                    }
                });
            }

            // Verify photo handler (Admin only)
            $(document).on('click', '.btn-verify-photo', function() {
                let id = $(this).attr('data-id');
                $.ajax({
                    url: '/manajemen/barang/foto/verify',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        hasChanges = true;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: response.icon,
                            title: response.text
                        });
                        loadProductPhotos(selectedProductId);
                    },
                    error: function(xhr) {
                        let msg = 'Gagal memverifikasi foto.';
                        if (xhr.responseJSON && xhr.responseJSON.text) {
                            msg = xhr.responseJSON.text;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });

            // Set main photo handler
            $(document).on('click', '.btn-set-main', function() {
                let id = $(this).attr('data-id');
                $.ajax({
                    url: '/manajemen/barang/foto/set-main',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        hasChanges = true;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: response.icon,
                            title: response.text
                        });
                        loadProductPhotos(selectedProductId);
                    },
                    error: function(xhr) {
                        let msg = 'Gagal memperbarui foto utama.';
                        if (xhr.responseJSON && xhr.responseJSON.text) {
                            msg = xhr.responseJSON.text;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });

            // Delete photo handler
            $(document).on('click', '.btn-delete-photo', function() {
                let id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Hapus foto ini?',
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/manajemen/barang/foto/delete',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                hasChanges = true;
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                                Toast.fire({
                                    icon: response.icon,
                                    title: response.text
                                });
                                loadProductPhotos(selectedProductId);
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Gagal menghapus foto.'
                                });
                            }
                        });
                    }
                });
            });

            // Reusable Lightbox function
            function showLightbox(fotos, clickedIndex) {
                let carouselHtml = '';
                currentProductFotos = fotos;

                fotos.forEach(function(foto, i) {
                    let activeClass = (i === clickedIndex) ? 'active' : '';
                    let uploader = foto.user ? foto.user.name : 'System';
                    let date = new Date(foto.created_at);
                    let formattedDate = date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    let statusText = foto.is_main ? 'Foto Utama' : (foto.is_verified ? 'Terverifikasi' : 'Pending');
                    let statusClass = foto.is_main ? 'text-warning' : (foto.is_verified ? 'text-success' : 'text-info');
                    
                    carouselHtml += `
                        <div class="carousel-item ${activeClass}">
                            <img src="/${foto.path}" class="d-block img-fluid" alt="Foto Produk">
                            <div class="lightbox-caption">
                                <span class="fw-bold text-white"><i data-acorn-icon="user" class="me-1 text-white" data-acorn-size="13"></i> ${uploader}</span>
                                <span class="text-white-50">|</span>
                                <span class="text-white-50"><i data-acorn-icon="clock" class="me-1 text-white-50" data-acorn-size="13"></i> ${formattedDate}</span>
                                <span class="text-white-50">|</span>
                                <span class="${statusClass} fw-bold">${statusText}</span>
                            </div>
                        </div>
                    `;
                });

                $('#lightboxCarouselInner').html(carouselHtml);
                
                if (typeof AcornIcons !== 'undefined') {
                    new AcornIcons().replace();
                }

                // Show lightbox modal
                $('#lightboxModal').modal('show');

                // Set active slide in carousel
                let carouselEl = document.getElementById('lightboxCarousel');
                if (carouselEl) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
                        let carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
                        carousel.to(clickedIndex);
                    } else if (typeof window.bootstrap !== 'undefined' && window.bootstrap.Carousel) {
                        let carousel = window.bootstrap.Carousel.getOrCreateInstance(carouselEl);
                        carousel.to(clickedIndex);
                    } else {
                        try {
                            $('#lightboxCarousel').carousel(clickedIndex);
                        } catch (e) {
                            console.error('Failed to slide carousel:', e);
                        }
                    }
                }
            }

            // Lightbox Modal trigger on image click (inside Manage Photos modal)
            $(document).on('click', '.gallery-trigger-img', function() {
                let clickedIndex = parseInt($(this).attr('data-index') || $(this).data('index'), 10);
                if (currentProductFotos && currentProductFotos.length > 0) {
                    showLightbox(currentProductFotos, clickedIndex);
                }
            });

            // Trigger lightbox from main product table preview click
            $(document).on('click', '.main-table-preview-img', function() {
                let rowData = table.row($(this).closest('tr')).data();
                if (rowData && rowData.fotos && rowData.fotos.length > 0) {
                    let clickedPath = $(this).attr('data-path');
                    let clickedIndex = 0;
                    
                    rowData.fotos.forEach(function(foto, i) {
                        if (foto.path === clickedPath) {
                            clickedIndex = i;
                        }
                    });
                    
                    showLightbox(rowData.fotos, clickedIndex);
                }
            });

            // Save Description
            $('#description-form').on('submit', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation(); // Prevent the global document form submit spinner from triggering
                
                // Get HTML content from Quill
                let desc = quill.root.innerHTML;
                
                // If it is empty (only empty tags), let's make it empty string
                if (quill.getText().trim().length === 0) {
                    desc = '';
                }
                
                $('#btn-save-description').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...');
                
                $.ajax({
                    url: '/manajemen/barang/foto/update-desc',
                    type: 'POST',
                    data: {
                        id: selectedProductId,
                        deskripsi: desc
                    },
                    success: function(response) {
                        $('#btn-save-description').prop('disabled', false).html('<i data-acorn-icon="save" class="me-1" data-acorn-size="14"></i> Simpan Deskripsi');
                        
                        if (typeof AcornIcons !== 'undefined') {
                            new AcornIcons().replace();
                        }
                        
                        // Close modal on success
                        $('#manageDescriptionModal').modal('hide');
                        table.ajax.reload(null, false);

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Deskripsi produk berhasil diperbarui.'
                        });
                    },
                    error: function(xhr) {
                        $('#btn-save-description').prop('disabled', false).html('<i data-acorn-icon="save" class="me-1" data-acorn-size="14"></i> Simpan Deskripsi');
                        
                        if (typeof AcornIcons !== 'undefined') {
                            new AcornIcons().replace();
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal memperbarui deskripsi produk.'
                        });
                    }
                });
            });

            // Open manage description modal
            $(document).on('click', '.btn-manage-desc', function() {
                selectedProductId = $(this).attr('data-id');
                let productKode = $(this).attr('data-kode');
                let productName = $(this).attr('data-name');

                // Set titles
                $('#manageDescriptionModalLabel').text(`Kelola Deskripsi: ${productName}`);
                $('.desc-modal-subtitle').text(`Kode Produk: ${productKode}`);

                // Load description via AJAX
                quill.enable(false);
                quill.setText('Memuat...');
                $('#btn-save-description').prop('disabled', true);

                $.ajax({
                    url: '/manajemen/barang/foto/show/' + selectedProductId,
                    type: 'GET',
                    success: function(response) {
                        quill.enable(true);
                        let desc = response.product.deskripsi || '';
                        quill.root.innerHTML = desc;
                        $('#btn-save-description').prop('disabled', false);
                    },
                    error: function() {
                        quill.enable(true);
                        quill.root.innerHTML = '';
                        $('#btn-save-description').prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat deskripsi produk.'
                        });
                    }
                });

                // Show modal
                $('#manageDescriptionModal').modal('show');
            });
        });
    </script>
@endpush
