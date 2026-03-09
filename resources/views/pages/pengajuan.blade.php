@extends('layouts.main')

@push('css')
    <style>
        :root {
            --primary-blue: #2563eb;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Inter', sans-serif;
        }

        .main-card {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .form-group-custom {
            margin-bottom: 1rem;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: #64748b;
            margin-bottom: 0.4rem;
            display: block;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.55rem 0.8rem;
            font-size: 0.9rem;
            color: var(--text-dark);
            background-color: #fff !important;
        }

        .equal-height-row {
            display: flex;
            flex-wrap: wrap;
        }

        .form-side {
            display: flex;
            flex-direction: column;
        }

        .flex-textarea-container {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .flex-textarea-container textarea {
            flex-grow: 1;
            min-height: 120px;
            resize: none;
        }

        .preview-box {
            background: #0f172a;
            border-radius: 12px;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 0.75rem;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .upload-zone {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            background: var(--bg-light);
            cursor: pointer;
            transition: all 0.2s;
            height: 110px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .upload-zone:hover {
            border-color: var(--primary-blue);
            background: #f1f5f9;
        }

        .btn-submit {
            background: var(--primary-blue);
            border: none;
            border-radius: 10px;
            padding: 0.8rem;
            font-weight: 600;
            color: white;
            margin-top: 1.5rem;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">Pengajuan Izin Absensi</h4>
            <p class="text-muted small">Ambil foto hanya sebagai validasi (tidak wajib).</p>
        </div>

        <div class="card main-card">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('absen.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4 equal-height-row">
                        <div class="col-lg-6 form-side">
                            <div class="form-group-custom">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" list="list-karyawan"
                                    placeholder="Ketik nama anda..." required>
                                <datalist id="list-karyawan">
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->name }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="form-group-custom">
                                <label class="form-label">Jenis Pengajuan</label>
                                <select name="status" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih --</option>
                                    <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Cuti">Cuti</option>
                                </select>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label">Tanggal Mulai Izin</label>
                                        <input type="date" name="tanggal_mulai_izin" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label">Tanggal Berakhir Izin</label>
                                        <input type="date" name="tanggal_berakhir_izin" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-textarea-container">
                                <label class="form-label">Keterangan</label>
                                <textarea name="deskripsi" class="form-control" placeholder="Masukkan keterangan tambahan..."></textarea>
                            </div>
                        </div>

                        <div class="col-lg-6 d-flex flex-column">
                            <label class="form-label">Preview Lampiran</label>
                            <div class="preview-box" id="previewBox">
                                <div class="text-center text-white-50">
                                    <i class="fa-solid fa-image fs-1 mb-2"></i>
                                    <p class="small mb-0">Belum ada Gambar</p>
                                </div>
                            </div>

                            <div class="upload-zone" onclick="document.getElementById('imageInput').click()">
                                <i class="fa-solid fa-cloud-arrow-up text-primary fs-4 mb-1"></i>
                                <span class="fw-bold text-dark small">Upload Foto / Dokumen</span>
                                <span class="text-muted" style="font-size: 0.7rem;">JPG / PNG, maksimal 2MB</span>
                                <input type="file" name="image" id="imageInput" class="d-none" accept="image/*"
                                    onchange="previewFoto(this)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-submit w-100">
                                <i class="fa-solid fa-paper-plane me-2"></i> Submit Pengajuan
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
        function previewFoto(input) {
            const box = document.getElementById('previewBox');
            box.innerHTML = '';
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    box.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                box.innerHTML =
                    '<div class="text-center text-white-50"><i class="fa-solid fa-image fs-1 mb-2"></i><p class="small mb-0">Belum ada Gambar</p></div>';
            }
        }
    </script>
@endpush
