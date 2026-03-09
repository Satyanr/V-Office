<nav class="navbar fixed-top navbar-expand-lg" style="background-color: #2F5296 ">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('transaksi') }}">
            <img src="/favicon.png" alt="Logo" width="32" height="50">
            <span class="text-white">
                V-Office Prototype
            </span>
        </a>
        <button class="navbar-toggler pb-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <div class="row pe-5" style="margin-left: auto;">
                @if (auth()->user())
                    @if (auth()->user()->role == 'Admin')
                        <div class="col d-flex mt-2">
                            <div class="dropdown">
                                <a class="btn btn-outline-light border-0 dropdown-toggle" href="javascript:void:(0)"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('pengguna') }}"><i
                                                class="fa-solid fa-user-group"></i> Pengguna</a></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                            <i class="fa fa-key"></i> Log Out</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        {{-- <div class="col d-flex">
                            <a class="btn btn-outline-light border-0" href="{{ route('layanan') }}">
                                <i class="fa-solid fa-clipboard-list"></i> <br> Layanan
                            </a>
                        </div> --}}
                    @else
                        <div class="col d-flex">
                            <a class="btn btn-outline-light border-0" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                <i class="fa fa-key"></i> <br> Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @endif

                    @if (session('original_user_id'))
                        <div class="col d-flex">
                            <a class="btn btn-outline-light border-0" href="{{ route('admin.stop-impersonating') }}">
                                <i class="fa fa-key"></i> <span>Berhenti</span>
                            </a>
                        </div>
                    @endif

                    <div class="col d-flex me-2" style="margin-left: auto;">
                        <a href="{{ route('rekap-absensi') }}" class="btn btn-outline-light border-0">
                            <div class="row">
                                <div class="col">
                                    <i class="fa-solid fa-users-between-lines"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    Absensi
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col d-flex me-2" style="margin-left: auto;">
                        <a href="{{ route('list-pengajuan') }}"
                            class="btn btn-outline-light border-0 position-relative">

                            {{-- Icon --}}
                            <div class="row">
                                <div class="col">
                                    <i class="fa-solid fa-users-between-lines"></i>

                                    {{-- Badge Pending --}}
                                    @if ($pendingCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle 
                                 badge rounded-pill bg-danger">
                                            {{ $pendingCount }}
                                        </span>
                                    @endif

                                </div>
                            </div>

                            {{-- Text --}}
                            <div class="row">
                                <div class="col">
                                    Pengajuan
                                </div>
                            </div>

                        </a>
                    </div>
                    @if (auth()->user()->role == 'Admin')
                        <div class="col d-flex me-2" style="margin-left: auto;">
                            <a href="{{ route('transaksi') }}" class="btn btn-outline-light border-0">
                                <div class="row">
                                    <div class="col">
                                        <i class="fa-solid fa-repeat"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Transaksi
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endif
                @if (auth()->user())
                    @if (auth()->user()->role != 'Petugas')
                        <div class="col d-flex me-2" style="margin-left: auto;">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light border-0" type="button">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-home"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Beranda
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    {{-- Button Pengajuan / Absen --}}
                    @if (request()->routeIs('pengajuan'))
                        <div class="col d-flex me-2" style="margin-left: auto;">
                            <a href="{{ route('absensi') }}" class="btn btn-outline-light border-0 ms-2">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Absen
                                    </div>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="col d-flex me-2" style="margin-left: auto;">
                            <a href="{{ route('pengajuan') }}" class="btn btn-outline-light border-0 ms-2">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Pengajuan
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="col d-flex me-2" style="margin-left: auto;">

                        {{-- Button Login --}}
                        <a href="{{ route('login') }}" class="btn btn-outline-light border-0">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    Login
                                </div>
                            </div>
                        </a>

                        {{-- Button Pengajuan / Absen --}}
                        @if (request()->routeIs('pengajuan'))
                            <a href="{{ route('absensi') }}" class="btn btn-outline-light border-0 ms-2">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Absen
                                    </div>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('pengajuan') }}" class="btn btn-outline-light border-0 ms-2">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Pengajuan
                                    </div>
                                </div>
                            </a>
                        @endif

                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
