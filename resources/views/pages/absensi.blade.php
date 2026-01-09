@extends('layouts.main')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

    <style>
        .camera-box {
            background: #0b1220;
            border-radius: 14px;
            overflow: hidden;
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #my_camera video,
        #my_camera canvas,
        #my_camera object,
        #my_camera embed {
            border-radius: 14px;
        }

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

        #preview_placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1rem;
        }

        .preview-loading {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.4);
            z-index: 10;
        }


        #results {
            position: absolute;
            inset: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0 fw-bold">Absensi</h4>
                <div class="text-muted small">Ambil foto selfie, lalu submit untuk mencatat absensi.</div>
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

                <form method="POST" action="{{ route('absen.store') }}" id="absenForm">
                    @csrf
                    <input type="text" name="waktu_masuk" id="waktu_masuk" hidden>
                    <input type="hidden" name="lokasi" id="lokasi">

                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-semibold">Nama</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Masukkan nama"
                                    required>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Status Absensi</label>
                            <select name="status" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Absen Masuk">Absen Masuk</option>
                                <option value="Absen Keluar">Absen Keluar</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="d-flex gap-2">
                                <span class="badge text-bg-light w-100 text-center py-2" id="cameraStatus">Kamera: belum
                                    aktif</span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="image" class="image-tag" id="imageTag">

                    <div class="row g-3">
                        {{-- kamera --}}
                        <div class="col-12 col-lg-6">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="fw-semibold">
                                        <i class="fa-solid fa-video me-1"></i> Kamera
                                    </div>
                                    <span class="badge text-bg-light" id="snapStatus">Gambar: belum ada</span>
                                </div>

                                <div class="camera-box">
                                    <div id="my_camera" class="d-none"></div>
                                    <div id="camera_placeholder" class="text-white-50 text-center px-3">
                                        Klik <span class="text-white fw-semibold">Open Camera</span> untuk mulai.
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-3 flex-wrap">
                                    <button type="button" class="btn btn-primary" id="open_camera">
                                        <i class="fa-solid fa-play me-1"></i> Buka Camera
                                    </button>

                                    <button type="button" class="btn btn-outline-secondary d-none" id="take_snap">
                                        <i class="fa-solid fa-camera-retro me-1"></i> Ambil Gambar
                                    </button>

                                    <button type="button" class="btn btn-outline-danger ms-auto d-none" id="stop_camera">
                                        <i class="fa-solid fa-stop me-1"></i> Stop
                                    </button>
                                </div>

                                <div class="form-text mt-2">
                                    Pastikan wajah terlihat jelas dan pencahayaan cukup.
                                </div>
                            </div>
                        </div>

                        {{-- preview --}}
                        <div class="col-12 col-lg-6">
                            <div class="border rounded-4 p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="fw-semibold">
                                        <i class="fa-solid fa-image me-1"></i> Preview
                                    </div>
                                </div>

                                <div class="preview-box" id="previewSection">
                                    <div id="results" class="w-100 h-100"></div>
                                    <div id="preview_placeholder" class="text-muted text-center px-3">
                                        Gambar akan muncul di sini.
                                    </div>

                                    <div id="preview_loading" class="preview-loading d-none">
                                        <div class="spinner-border text-light" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-grid mt-3">
                                    <button class="btn btn-success btn-lg" id="btnSubmit" disabled>
                                        <i class="fa-solid fa-paper-plane me-1"></i> Submit Absensi
                                    </button>
                                </div>

                                <div class="form-text mt-2" id="submitInfo">
                                    ⏰ Absensi hanya dapat dilakukan mulai pukul <b>07:30 WIB</b>.
                                </div>

                                <div class="form-text mt-1 fw-semibold" id="countdown0730"></div>


                                <div class="form-text mt-2">
                                    Tombol submit akan aktif setelah gambar diambil.
                                </div>
                            </div>
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
        async function getAlamatJalan() {
            return new Promise((resolve) => {
                if (!navigator.geolocation) {
                    resolve('Lokasi tidak tersedia');
                    return;
                }

                navigator.geolocation.getCurrentPosition(async position => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`
                        );
                        const data = await response.json();

                        let address = data.address;
                        let jalan = address.road || address.pedestrian || address.neighbourhood ||
                            '';
                        let kota = address.city || address.town || address.village || '';
                        let negara = address.country || '';

                        resolve(`${jalan}, ${kota}`);
                    } catch (e) {
                        resolve('Lokasi tidak diketahui');
                    }
                }, () => {
                    resolve('Izin lokasi ditolak');
                });
            });
        }
    </script>


    <script>
        let userLocationText = 'Lokasi tidak diketahui';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude.toFixed(6);
                    const lng = pos.coords.longitude.toFixed(6);
                    userLocationText = `Lat ${lat}, Lng ${lng}`;
                },
                () => {
                    userLocationText = 'Lokasi ditolak';
                }
            );
        }
    </script>

    <script language="JavaScript">
        const $cameraStatus = $('#cameraStatus');
        const $snapStatus = $('#snapStatus');
        const $btnSubmit = $('#btnSubmit');

        function setBadge($el, text, variant = 'light') {
            $el.removeClass().addClass('badge text-bg-' + variant).text(text);
        }

        $('#open_camera').on('click', function() {
            $('#my_camera').removeClass('d-none');
            $('#camera_placeholder').addClass('d-none');

            $('#take_snap').removeClass('d-none');
            $('#stop_camera').removeClass('d-none');

            const isMobile = window.innerWidth < 768;

            Webcam.set({
                width: isMobile ? 360 : 640,
                height: isMobile ? 480 : 480,
                image_format: 'jpeg',
                jpeg_quality: 85,
                constraints: {
                    facingMode: "user"
                }
            });

            Webcam.attach('#my_camera');
            setBadge($cameraStatus, 'Kamera: aktif', 'success');
        });

        $('#stop_camera').on('click', function() {
            Webcam.reset();
            $('#my_camera').addClass('d-none');
            $('#camera_placeholder').removeClass('d-none');

            $('#take_snap').addClass('d-none');
            $('#stop_camera').addClass('d-none');

            setBadge($cameraStatus, 'Kamera: berhenti', 'secondary');
        });

        $('#take_snap').on('click', function() {
            if (window.innerWidth < 768) {
                setTimeout(() => {
                    document.getElementById('previewSection')
                        .scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                }, 150);
            }

            $('#preview_loading').removeClass('d-none');

            Webcam.snap(async function(data_uri) {

                const lokasi = await getAlamatJalan();
                document.getElementById('lokasi').value = lokasi;

                const img = new Image();
                img.src = data_uri;

                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    canvas.width = img.width;
                    canvas.height = img.height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0);

                    const waktu = document.getElementById('waktu_masuk').value;

                    const padding = 16;
                    const lineHeight = 26;
                    const isPortrait = canvas.height > canvas.width;
                    const fontSize = isPortrait ? 16 : 20;

                    ctx.font = `${fontSize}px Arial`;

                    const watermarkHeight = lineHeight * 2 + padding * 2;
                    const startY = canvas.height - watermarkHeight;

                    ctx.fillStyle = 'rgba(0,0,0,0.65)';
                    ctx.fillRect(0, startY, canvas.width, watermarkHeight);

                    ctx.fillStyle = '#fff';
                    ctx.textBaseline = 'top';

                    ctx.fillText(`📍 ${lokasi}`, padding, startY + padding);
                    ctx.fillText(`🕒 ${waktu} WIB`, padding, startY + padding + lineHeight);

                    const finalImage = canvas.toDataURL('image/jpeg', 0.85);

                    $('#imageTag').val(finalImage);
                    $('#results').html('<img src="' + finalImage + '">');
                    $('#preview_placeholder').addClass('d-none');

                    setBadge($snapStatus, 'Gambar: siap', 'success');
                    if (isAfter0730WIB()) {
                        $btnSubmit.prop('disabled', false);
                    }



                    $('#preview_loading').addClass('d-none');
                };
            });
        });


        window.addEventListener('beforeunload', () => {
            try {
                Webcam.reset();
            } catch (e) {}
        });

        // badge
        setBadge($cameraStatus, 'Kamera: belum aktif', 'light');
        setBadge($snapStatus, 'Gambar: belum ada', 'light');
    </script>

    <script>
        function isAfter0730WIB() {
            const now = new Date().toLocaleString('en-US', {
                timeZone: 'Asia/Jakarta'
            });
            const date = new Date(now);

            const hours = date.getHours();
            const minutes = date.getMinutes();

            return (hours > 7 || (hours === 7 && minutes >= 30));
        }

        function updateSubmitTimeRule() {
            if (!isAfter0730WIB()) {
                $('#btnSubmit').prop('disabled', true);
                $('#submitInfo')
                    .removeClass('text-muted')
                    .addClass('text-danger')
                    .html('⛔ Absensi hanya dapat dilakukan mulai pukul <b>07:30 WIB</b>.');
            } else {
                $('#submitInfo')
                    .removeClass('text-danger')
                    .addClass('text-muted')
                    .html('✅ Waktu absensi sudah dibuka.');
            }
        }

        updateSubmitTimeRule();
        setInterval(updateSubmitTimeRule, 30000); // update tiap 30 detik
    </script>

    <script>
        function getWIBDate() {
            return new Date(new Date().toLocaleString('en-US', {
                timeZone: 'Asia/Jakarta'
            }));
        }

        function updateCountdown0730() {
            const now = getWIBDate();

            const target = new Date(now);
            target.setHours(7, 30, 0, 0);

            const countdownEl = $('#countdown0730');

            // jika sudah lewat 07:30
            if (now >= target) {
                countdownEl.html('');
                $('#submitInfo')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .html('✅ Waktu absensi sudah dibuka.');
                return;
            }

            const diff = target - now;

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            countdownEl
                .removeClass('text-success')
                .addClass('text-danger')
                .html(
                    `⏳ Dibuka dalam <b>${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}</b>`
                    );
        }

        updateCountdown0730();
        setInterval(updateCountdown0730, 1000);
    </script>
@endpush
