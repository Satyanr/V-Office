<div>
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 p-md-4 bg-white">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="small fw-bold text-muted mb-1">Cari Nama</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-0" placeholder="Ketik nama..." wire:model.live.debounce.300ms="searchNama">
                </div>
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Status Absen</label>
                <select class="form-select bg-light border-0" wire:model.live="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="Absen Masuk">Absen Masuk</option>
                    <option value="Absen Pulang">Absen Pulang</option>
                    <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Cuti">Cuti</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Keterangan</label>
                <select class="form-select bg-light border-0" wire:model.live="filterKeterangan">
                    <option value="">Semua Keterangan</option>
                    <option value="Tepat Waktu">Tepat Waktu</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Lembur">Lembur</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-1">Tanggal</label>
                <input type="date" class="form-control bg-light border-0" wire:model.live="filterTanggal">
            </div>
        </div>
    </div>

    {{-- Update Mode --}}
    @if ($updateMode)
    <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden border-start border-primary border-5">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="fa fa-edit me-2"></i>Edit Pengajuan: <span class="text-primary">{{ $name }}</span></h5>
                <button class="btn-close" wire:click="cancel"></button>
            </div>
            <div class="row g-3">
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
                    </select>
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
                <button class="btn btn-primary rounded-pill px-4" wire:click="update">Simpan Perubahan</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Grid Content --}}
    <div class="row g-4">
        @forelse ($approvals as $approval)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative pengajuan-card">
                
                {{-- button delet dan edit --}}
                <div class="position-absolute" style="top: 15px; right: 15px; z-index: 10;">
                    <button class="btn btn-white btn-sm shadow-sm rounded-circle me-1" 
                            wire:click="edit({{ $approval->id }})" title="Edit" 
                            style="width: 32px; height: 32px; padding: 0; background: white; border: 1px solid #eee;">
                        <i class="fa fa-edit text-warning small"></i>
                    </button>
                    <button class="btn btn-white btn-sm shadow-sm rounded-circle" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteConfirmModal"
                            onclick="setDeleteId({{ $approval->id }})"
                            title="Hapus" 
                            style="width: 32px; height: 32px; padding: 0; background: white; border: 1px solid #eee;">
                        <i class="fa fa-trash text-danger small"></i>
                    </button>
                </div>

                {{-- Status bar --}}
                @php
                    $barColor = match ($approval->approval) {
                        'Approved' => '#10b981', // hejo
                        'Rejected' => '#ef4444', // berem
                        default => '#f59e0b', // koneng
                    };
                @endphp
                <div style="height: 5px; background-color: {{ $barColor }};"></div>

                <div class="card-body p-4 pt-5">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            @if ($approval->photo_name && file_exists(public_path('storage/absensi/' . $approval->photo_name)))
                                <img src="{{ asset('storage/absensi/' . $approval->photo_name) }}" 
                                     class="rounded-3 shadow-sm" style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                     data-bs-toggle="modal" data-bs-target="#photoPreviewModal" onclick="showPreview(this.src)">
                            @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                                    <i class="fa fa-camera text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ms-3 pe-5">
                            <h6 class="fw-bold mb-0 text-dark">{{ $approval->name }}</h6>
                            <span class="text-muted small">{{ $approval->status }}</span>
                        </div>
                    </div>

                    <div class="bg-light rounded-4 p-3 mb-3 border-0">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Tanggal:</span>
                            <span class="small fw-bold text-dark">{{ \Carbon\Carbon::parse($approval->tanggal_awal)->format('d M Y') }}</span>
                        </div>
                        @if($approval->tanggal_akhir)
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Sampai:</span>
                            <span class="small fw-bold text-dark">{{ \Carbon\Carbon::parse($approval->tanggal_akhir)->format('d M Y') }}</span>
                        </div>
                        @endif
                        <div class="mt-3">
                            <span class="text-muted small d-block mb-1">Keterangan:</span>
                            <div class="p-2 bg-light rounded-3 border-start border-primary border-3" 
                                style="max-height: 80px; overflow-y: auto; font-size: 0.85rem;">
                                <p class="mb-0 text-dark italic">
                                    "{{ $approval->keterangan }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="small text-muted"><i class="fa fa-clock me-1"></i> {{ $approval->created_at->diffForHumans() }}</span>
                        <span class="badge bg-light text-{{ $approval->approval == 'Approved' ? 'success' : ($approval->approval == 'Rejected' ? 'danger' : 'warning') }} border py-1 px-3 rounded-pill">
                            {{ $approval->approval }}
                        </span>
                    </div>

                    {{-- button approve dan reject --}}
                    <div class="row g-2">
                        <div class="col-6">
                            <button class="btn btn-success btn-sm w-100 rounded-pill py-2 fw-bold" wire:click="approve({{ $approval->id }})">
                                <i class="fa fa-check me-1"></i> Approve
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-danger btn-sm w-100 rounded-pill py-2 fw-bold" wire:click="reject({{ $approval->id }})">
                                <i class="fa fa-times me-1"></i> Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fa-regular fa-folder-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">Tidak ada data ditemukan.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $approvals->links() }}
    </div>

    {{-- preview image --}}
    <div class="modal fade" id="photoPreviewModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white border-0">
                    <h6 class="modal-title">Bukti Lampiran</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 bg-dark text-center">
                    <img id="previewImage" src="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    {{-- delete confirmation modal --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-body p-4 text-center">
                    <div class="text-danger mb-3">
                        <i class="fa-solid fa-circle-exclamation fa-3x"></i>
                    </div>
                    <h5 class="fw-bold">Hapus Data?</h5>
                    <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                    
                    <input type="hidden" id="idToDelete">
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-center mt-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger rounded-pill px-4" onclick="executeDelete()">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    function executeDelete() {
        if (deleteId) {
            @this.call('destroy', deleteId);
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
        }
    }
</script>