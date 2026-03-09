<div>
    <style>
        .glass-card { background: #ffffff; border-radius: 20px; border: 1px solid #f1f5f9; }
        .table-custom thead { background: #f8fafc; }
        .table-custom th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; border-top: none; padding: 15px; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; }
        
        /* foto mantul2 */
        .img-absensi { width: 50px; height: 50px; object-fit: cover; border-radius: 12px; transition: 0.2s; border: 2px solid #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .img-absensi:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); cursor: pointer; }

        /* badge */
        .b-pill { 
            padding: 4px 10px; 
            border-radius: 6px; 
            font-weight: 600; 
            font-size: 0.72rem; 
            display: inline-block;
            border: 1px solid transparent;
            letter-spacing: 0.02em;
        }

        /* outline status */
        .b-masuk { border-color: #4338ca; color: #4338ca; background: #eef2ff; }
        .b-pulang { border-color: #475569; color: #475569; background: #f8fafc; }

        /* otline keterangan */
        .b-tepat { border-color: #10b981; color: #047857; background: #ecfdf5; }
        .b-telat { border-color: #ef4444; color: #b91c1c; background: #fef2f2; }
        .b-izin { border-color: #f59e0b; color: #b45309; background: #fffbeb; }
        .b-info { border-color: #3b82f6; color: #1d4ed8; background: #eff6ff; }

        /* button */
        .btn-action { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; border: 1px solid #e2e8f0; background: white; text-decoration: none; }
        .btn-edit:hover { background: #fef9c3; color: #a16207; }
        .btn-del:hover { background: #fee2e2; color: #991b1b; }

        /* modal animasi */
        .modal-backdrop { background-color: rgba(15, 23, 42, 0.5) !important; backdrop-filter: none !important; }
        .modal.fade .modal-dialog { transform: scale(0.97); transition: transform 0.1s ease-out; }
        .modal.show .modal-dialog { transform: scale(1); }
    </style>

    <div class="glass-card">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-0">Absensi</h5>
                <p class="text-muted small mb-0">Rekap Absensi Karyawan.</p>
            </div>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm border rounded-3 overflow-hidden" style="width: 300px;">
                    <input type="date" class="form-control border-0" wire:model="exportFromDate">
                    <span class="input-group-text bg-white border-0">-</span>
                    <input type="date" class="form-control border-0" wire:model="exportToDate">
                    <button class="btn btn-dark border-0 px-3" wire:click="export"><i class="fa-solid fa-file-export"></i></button>
                </div>
            </div>
        </div>

        <div class="p-3 bg-light bg-opacity-50 border-bottom">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 shadow-none" placeholder="Cari Nama" wire:model.live.debounce.300ms="searchNama">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm shadow-none" wire:model.live="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="Absen Masuk">Absen Masuk</option>
                        <option value="Absen Pulang">Absen Pulang</option>
                        <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm shadow-none" wire:model.live="filterKeterangan">
                        <option value="">Semua Keterangan</option>
                        <option value="Tepat Waktu">Tepat Waktu</option>
                        <option value="Terlambat">Terlambat</option>
                        <option value="Lembur">Lembur</option>
                        <option value="Pulang Awal">Pulang Awal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm shadow-none" wire:model.live="filterTanggal">
                </div>
            </div>
        </div>

        @if ($updateMode)
        <div class="p-4 bg-primary bg-opacity-10 border-bottom" style="animation: slideDown 0.3s ease;">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="small fw-bold text-muted mb-1">Nama Karyawan</label>
                    <input type="text" class="form-control form-control-sm" wire:model="name">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">Status</label>
                    <select class="form-select form-select-sm" wire:model="status">
                        <option value="Absen Masuk">Absen Masuk</option>
                        <option value="Absen Pulang">Absen Pulang</option>
                        <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">Keterangan</label>
                    <select class="form-select form-select-sm" wire:model="keterangan">
                        <option value="Tepat Waktu">Tepat Waktu</option>
                        <option value="Terlambat">Terlambat</option>
                        <option value="Lembur">Lembur</option>
                        <option value="Pulang Awal">Pulang Awal</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-1">
                    <button class="btn btn-primary btn-sm w-100 fw-bold" wire:click="update">Simpan</button>
                    <button class="btn btn-outline-secondary btn-sm" wire:click="cancel">X</button>
                </div>
            </div>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-custom align-middle text-center mb-0">
                <thead>
                    <tr>
                        <th style="width: 100px;">Preview</th>
                        <th class="text-start">Nama</th>
                        <th>Waktu & Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody wire:loading.remove>
                    @forelse ($absensis as $absensi)
                    <tr>
                        <td>
                            @if ($absensi->photo_name)
                                <img src="{{ asset('storage/absensi/' . $absensi->photo_name) }}" 
                                     class="img-absensi" data-bs-toggle="modal" data-bs-target="#photoPreviewModal"
                                     onclick="showPreview('{{ asset('storage/absensi/' . $absensi->photo_name) }}')">
                            @else
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mx-auto" style="width:50px; height:50px;">
                                    <i class="fa fa-user-slash text-muted opacity-50"></i>
                                </div>
                            @endif
                        </td>
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $absensi->name }}</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('H:i') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('d M Y') }}</small>
                        </td>
                        <td>
                            <span class="b-pill {{ $absensi->status === 'Absen Masuk' ? 'b-masuk' : 'b-pulang' }}">
                                {{ $absensi->status }}
                            </span>
                        </td>
                        <td>
                            @php
                                $kClass = match($absensi->keterangan) {
                                    'Tepat Waktu' => 'b-tepat',
                                    'Terlambat'   => 'b-telat',
                                    'Sakit', 'Izin Tidak Masuk' => 'b-izin',
                                    'Lembur', 'Cuti' => 'b-info',
                                    default => 'b-pulang'
                                };
                            @endphp
                            <span class="b-pill {{ $kClass }}">
                                {{ $absensi->keterangan }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="#" wire:click.prevent="edit({{ $absensi->id }})" class="btn-action btn-edit text-warning">
                                    <i class="fa-solid fa-pencil-alt"></i>
                                </a>
                                <a href="#" wire:click.prevent="destroy({{ $absensi->id }})" class="btn-action btn-del text-danger">
                                    <i class="fa-solid fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-5 text-muted">Data absensi tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-light bg-opacity-25 d-flex justify-content-between align-items-center">
            <div class="small text-muted">Showing {{ $absensis->firstItem() }} to {{ $absensis->lastItem() }} of {{ $absensis->total() }} results</div>
            {{ $absensis->links() }}
        </div>
    </div>

    <div class="modal fade" id="photoPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" style="font-size: 0.7rem;"></button>
                </div>
                <div class="modal-body p-3 text-center">
                    <img id="previewImage" src="" class="img-fluid rounded-3" style="max-height: 80vh; opacity: 0; transition: 0.1s;">
                </div>
            </div>
        </div>
    </div>
</div>