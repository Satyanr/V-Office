<div>
    <div class="row text-center justify-content-between">
        <div class="col">
            <div class="row mb-3 align-items-end">
                <div class="col-lg-12">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Cari Nama"
                                wire:model.live.debounce.300ms="searchNama">
                        </div>

                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterStatus">
                                <option value="">Semua Status</option>
                                <option value="Absen Masuk">Absen Masuk</option>
                                <option value="Absen Pulang">Absen Pulang</option>
                                <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Cuti">Cuti</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filterKeterangan">
                                <option value="">Semua Keterangan</option>
                                <option value="Tepat Waktu">Tepat Waktu</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Lembur">Lembur</option>

                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="date" class="form-control" wire:model.live="filterTanggal">
                        </div>
                    </div>
                </div>
            </div>




            @if ($updateMode)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" class="form-control" wire:model="name">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" wire:model="status">
                                    <option value="Absen Masuk">Absen Masuk</option>
                                    <option value="Absen Pulang">Absen Pulang</option>
                                    <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Cuti">Cuti</option>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" wire:model="keterangan">
                                    <option value="Tepat Waktu">Tepat Waktu</option>
                                    <option value="Terlambat">Terlambat</option>
                                    <option value="Lembur">Lembur</option>
                                    <option value="Cuti">Cuti</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button class="btn btn-primary" wire:click="update">Update</button>
                            <button class="btn btn-secondary" wire:click="cancel">Batal</button>
                        </div>
                    </div>
                </div>
            @endif


            <div class="row mb-3">
                <div class="col-auto">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ session('message') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="row" wire:loading.remove>
                        @forelse ($absensis as $absensi)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">

                                        {{-- FOTO --}}
                                        <div class="text-center mb-3">
                                            @if ($absensi->photo_name && file_exists(public_path('storage/absensi/' . $absensi->photo_name)))
                                                <img src="{{ asset('storage/absensi/' . $absensi->photo_name) }}"
                                                    class="img-thumbnail preview-img"
                                                    style="width:100px; cursor:pointer" data-bs-toggle="modal"
                                                    data-bs-target="#photoPreviewModal" onclick="showPreview(this.src)">
                                            @else
                                                <span class="text-muted">No Photo</span>
                                            @endif
                                        </div>

                                        {{-- NAMA --}}
                                        <h6 class="text-center fw-bold mb-1">
                                            {{ $absensi->name }}
                                        </h6>

                                        {{-- TANGGAL --}}
                                        <p class="text-center text-muted mb-2">
                                            {{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('d M Y') }} <br>
                                            <small>{{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('H:i:s') }}</small>
                                        </p>

                                        {{-- STATUS --}}
                                        <div class="text-center mb-2">
                                            <span class="badge bg-primary">
                                                {{ $absensi->status }}
                                            </span>
                                        </div>

                                        {{-- KETERANGAN --}}
                                        @php
                                            $badgeClass = match ($absensi->keterangan) {
                                                'Terlambat' => 'danger',
                                                'Lembur' => 'info',
                                                'Izin Tidak Masuk' => 'warning',
                                                'Cuti' => 'primary',
                                                'Sakit' => 'secondary',
                                                default => 'success',
                                            };
                                        @endphp

                                        <div class="text-center mb-3">
                                            <span class="badge bg-{{ $badgeClass }}">
                                                {{ $absensi->keterangan }}
                                            </span>
                                        </div>

                                        {{-- ACTION HR --}}
                                        <div class="d-flex justify-content-between gap-2">
                                            <button class="btn btn-success btn-sm w-100"
                                                wire:click="approve({{ $absensi->id }})">
                                                <i class="fa fa-check"></i> Approve
                                            </button>

                                            <button class="btn btn-danger btn-sm w-100"
                                                wire:click="reject({{ $absensi->id }})">
                                                <i class="fa fa-times"></i> Reject
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col">
                                <div class="alert alert-warning text-center">
                                    Tidak ada data absensi
                                </div>
                            </div>
                        @endforelse
                    </div>


                    <!-- MODAL PREVIEW FOTO -->
                    <div class="modal fade" id="photoPreviewModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Preview Foto Absensi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="previewImage" src="" class="img-fluid rounded">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="text-white bg-warning w-100 border-0 rounded-pill text-center" wire:loading>
                        Loading...
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    {{ $absensis->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
