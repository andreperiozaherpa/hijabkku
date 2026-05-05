@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Buku Panduan</h1>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12">
                    <section class="scroll-section" id="hover">
                        <div class="card">
                            <div class="card-body">
                                <div style="list-style-type: none;" class="d-flex gap-2 flex-row overflow-auto">
                                    <ul scope="col">
                                        <span class="fw-bold text-capitalize">jenis</span>
                                        <br>
                                        <br>
                                        <div style="list-style-type: none;" class="d-flex gap-2 flex-column">
                                            @foreach ($jenis as $j)
                                                <li scope="col"> {{ $j->jenis }}</li>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </ul>
                                    <ul scope="col">
                                        <span class="fw-bold text-capitalize">merek</span>
                                        <br>
                                        <br>
                                        <div style="list-style-type: none;" class="d-flex gap-2 flex-column">
                                            @foreach ($merek as $mrk)
                                                <li scope="col"> {{ $mrk->jenis }}</li>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </ul>
                                    <ul scope="col">
                                        <span class="fw-bold text-capitalize">model</span>
                                        <br>
                                        <br>
                                        <div style="list-style-type: none;" class="d-flex gap-2 flex-column">
                                            @foreach ($model as $mdl)
                                                <li scope="col"> {{ $mdl->jenis }}</li>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </ul>
                                    <ul scope="col">
                                        <span class="fw-bold text-capitalize">bahan</span>
                                        <br>
                                        <br>
                                        <div style="list-style-type: none;" class="d-flex gap-2 flex-column">
                                            @foreach ($bahan as $bhn)
                                                <li scope="col"> {{ $bhn->jenis }}</li>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </ul>
                                    <ul scope="col">
                                        <span class="fw-bold text-capitalize">variasi</span>
                                        <br>
                                        <br>
                                        <div style="list-style-type: none;" class="d-flex gap-2 flex-column">
                                            @foreach ($variasi as $vri)
                                                <li scope="col"> {{ $vri->jenis }}</li>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </ul>
                                    <ul scope="col">
                                        <span class="fw-bold text-capitalize">ukuran</span>
                                        <br>
                                        <br>
                                        <div style="list-style-type: none;" class="d-flex gap-2 flex-column">
                                            @foreach ($ukuran as $uk)
                                                <li scope="col"> {{ $uk->jenis }}</li>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </ul>
                                    <ul scope="col">
                                        <span class="fw-bold text-capitalize">packaging</span>
                                        <br>
                                        <br>
                                        <div style="list-style-type: none;" class="d-flex gap-2 flex-column">
                                            @foreach ($packaging as $pack)
                                                <li scope="col"> {{ $pack->jenis }}</li>
                                                <hr>
                                            @endforeach
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- Content End -->
        </div>
    </main>
@endsection
@push('script')
    <script></script>
@endpush
