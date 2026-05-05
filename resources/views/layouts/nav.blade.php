<div id="nav" class="nav-container d-flex">
    <div class="nav-content d-flex">
        <!-- Logo Start -->
        <div class="logo position-relative">
            <a href="#">
                <!-- Logo can be added directly -->
                <!-- <img src="img/logo/logo-white.svg" alt="logo" /> -->

                <!-- Or added via css to provide different ones for different color themes -->
                {{-- <div class="img"></div> --}}
                <img src="/vendor/acorn/img/logo/hijabkku.png" alt="">
            </a>
        </div>
        <!-- Logo End -->

        <!-- User Menu Start -->
        <div class="user-container d-flex">
            <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="profile" alt="profile" src="/vendor/acorn/img/logo/hijabkku_user.png" />
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="name">{{ Auth::user()->role }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-end user-menu wide">
                <div class="row mb-1 ms-0 me-0">
                    <div class="col-6 ps-1 pe-1">
                    </div>
                    <div class="col-6 pe-1 ps-1">
                        <ul class="list-unstyled">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-background-alternate bg-transparent p-0 m-0">
                                        <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                        <span class="align-middle">Logout </span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- User Menu End -->

        <!-- Icons Menu Start -->
        <ul class="list-unstyled list-inline text-center menu-icons">
            <li class="list-inline-item">
                <a href="#" data-bs-toggle="modal" data-bs-target="#searchPagesModal">
                    <i data-acorn-icon="search" data-acorn-size="18"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <a href="#" id="pinButton" class="pin-button">
                    <i data-acorn-icon="lock-on" class="unpin" data-acorn-size="18"></i>
                    <i data-acorn-icon="lock-off" class="pin" data-acorn-size="18"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <a href="#" id="colorButton">
                    <i data-acorn-icon="light-on" class="light" data-acorn-size="18"></i>
                    <i data-acorn-icon="light-off" class="dark" data-acorn-size="18"></i>
                </a>
            </li>
            <li class="list-inline-item">
            </li>
        </ul>
        <!-- Icons Menu End -->

        <!-- Menu Start -->
        <div class="menu-container flex-grow-1">
            <ul id="menu" class="menu">
                <li>
                    <a href="/dashboard">
                        <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                        <span class="label">Dashboards</span>
                    </a>
                </li>
                <li>
                    <a href="#transaksi">
                        <i data-acorn-icon="cart" class="icon" data-acorn-size="18"></i>
                        <span class="label">Transaksi</span>
                    </a>
                    <ul id="transaksi">
                        <li>
                            <a href="/transaksi/penjualan">
                                <span class="label">Penjualan</span>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="/transaksi/daftar"> --}}
                            <a href="/laporan/penjualan">
                                <span class="label">Daftar Penjualan</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#buku">
                        <i data-acorn-icon="book" class="icon" data-acorn-size="18"></i>
                        <span class="label">Buku Panduan</span>
                    </a>
                    <ul id="buku">
                        <li>
                            <a href="/buku/panduan">
                                <span class="label">Table Panduan</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @if (Auth::user()->role == 'admin')
                    <li>
                        <a href="#manajemen">
                            <i data-acorn-icon="balance" class="icon" data-acorn-size="18"></i>
                            <span class="label">Manajemen</span>
                        </a>
                        <ul id="manajemen">
                            <li>
                                <a href="#barang">
                                    <i data-acorn-icon="handbag" class="icon" data-acorn-size="18"></i>
                                    <span class="label">Data Barang</span>
                                </a>
                                <ul id="barang">
                                    <li>
                                        <a href="/manajemen/barang/data/">
                                            <span class="label">Data Barang</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/manajemen/barang/jenis/">
                                            <span class="label">Jenis Barang</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/manajemen/barang/merek/">
                                            <span class="label">Merek Barang</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/manajemen/barang/model/">
                                            <span class="label">Model Barang</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/manajemen/barang/bahan/">
                                            <span class="label">Bahan Barang</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/manajemen/barang/variasi/">
                                            <span class="label">Variasi Barang</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/manajemen/barang/ukuran/">
                                            <span class="label">Ukuran Barang</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/manajemen/barang/packaging/">
                                            <span class="label">Packaging Barang</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="/manajemen/supplier/index">
                                    <i data-acorn-icon="factory" class="icon" data-acorn-size="18"></i>
                                    <span class="label">Supplier</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i data-acorn-icon="credit-card" class="icon" data-acorn-size="18"></i>
                                    <span class="label">Member</span>
                                </a>
                            </li>
                            <li>
                                <a href="#stock">
                                    <i data-acorn-icon="exchange" class="icon" data-acorn-size="18"></i>
                                    <span class="label">Stock</span>
                                </a>
                                <ul id="stock">
                                    <li>
                                        <a href="/manajemen/stock/inout/index">
                                            <span class="label">Stock In/Out</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/manajemen/stock/toko/index">
                                            <span class="label">Stock Toko/Warehouse</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#opname">
                                            <span class="label">Opname</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="/manajemen/warehouse/index">
                                    <i data-acorn-icon="signboard" class="icon" data-acorn-size="18"></i>
                                    <span class="label">Toko/Warehouse</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#laporan">
                            <i data-acorn-icon="book-open" class="icon" data-acorn-size="18"></i>
                            <span class="label">Laporan</span>
                        </a>
                        <ul id="laporan">
                            <li>
                                <a href="/laporan/barang">
                                    <span class="label">Laporan Barang</span>
                                </a>
                            </li>
                            <li>
                                <a href="/laporan/penjualan">
                                    <span class="label">Laporan Penjualan</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="label">Laporan Stock</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#user">
                            <i data-acorn-icon="user" class="icon" data-acorn-size="18"></i>
                            <span class="label">Manajemen User</span>
                        </a>
                        <ul id="user">
                            <li>
                                <a href="/user/index">
                                    <span class="label">Data Karyawan</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="label">Data Log</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Menu End -->

        <!-- Mobile Buttons Start -->
        <div class="mobile-buttons-container">
            <!-- Scrollspy Mobile Button Start -->
            <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
                <i data-acorn-icon="menu-dropdown"></i>
            </a>
            <!-- Scrollspy Mobile Button End -->

            <!-- Scrollspy Mobile Dropdown Start -->
            <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
            <!-- Scrollspy Mobile Dropdown End -->

            <!-- Menu Button Start -->
            <a href="#" id="mobileMenuButton" class="menu-button">
                <i data-acorn-icon="menu"></i>
            </a>
            <!-- Menu Button End -->
        </div>
        <!-- Mobile Buttons End -->
    </div>
    <div class="nav-shadow"></div>
</div>
