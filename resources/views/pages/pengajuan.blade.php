@extends('layouts.main')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

    <style>
        .preview-box {
            position: relative;
            background: #f8fafc;
            border-radius: 14px;
            min-height: 320px;
            overflow: hidden;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #000;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0 fw-bold">Pengajuan Izin Absensi</h4>
                <div class="text-muted small">Ambil foto hanya sebagai validasi (tidak wajib).</div>
            </div>
            <span class="badge text-bg-primary px-3 py-2">
                <i class="fa-solid fa-camera me-1"></i> Absensi
            </span>
        </div>

        {{-- alert success/error --}}
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="fw-semibold mb-1">Gagal:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('absen.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" class="form-control" list="list-karyawan" required>

                            <datalist id="list-karyawan">
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->name }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Pengajuan</label>
                            <select name="status" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Cuti">Cuti</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Mulai Izin</label>
                                    <input type="date" name="tanggal_mulai_izin" id="tanggal_mulai_izin"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Berakhir Izin</label>
                                    <input type="date" name="tanggal_berakhir_izin" id="tanggal_berakhir_izin"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="form-label fw-semibold">
                                        Upload Foto (opsional)
                                    </label>
                                    <input type="file" name="foto" class="form-control" accept="image/*"
                                        onchange="previewFoto(this)">
                                    <div class="form-text">
                                        JPG / PNG, maksimal 2MB
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="form-label fw-semibold">
                                        Keterangan 
                                    </label>
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan keterangan tambahan..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Preview</label>
                            <div class="preview-box" id="previewBox">
                                <span class="text-muted">Belum ada gambar</span>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12">
                            <button class="btn btn-success btn-lg w-100">
                                <i class="fa-solid fa-paper-plane me-1"></i>
                                Submit Pengajuan
                            </button>
                        </div>

                    </div>
                </form>


            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        const nowWIB = new Date().toLocaleString('sv-SE', {
            timeZone: 'Asia/Jakarta'
        }).replace('T', ' ');

        document.getElementById('waktu_masuk').value = nowWIB;
    </script>

    <script>
        function previewFoto(input) {
            const box = document.getElementById('previewBox');
            box.innerHTML = '';

            if (!input.files || !input.files[0]) {
                box.innerHTML = '<span class="text-muted">Belum ada gambar</span>';
                return;
            }

            const img = document.createElement('img');
            img.src = URL.createObjectURL(input.files[0]);
            box.appendChild(img);
        }
    </script>
@endpush
