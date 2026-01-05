<div>
    <div class="row text-center justify-content-between">
        <div class="col">
            <div class="row mb-3 align-items-end">
                <div class="col-lg-8">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Cari Nama" wire:model="searchNama"
                                wire:input="resetPageCustom">
                        </div>

                        <div class="col-md-3">
                            <select class="form-select" wire:model="filterStatus" wire:change="resetPageCustom">
                                <option value="">Semua Status</option>
                                <option value="Absen Masuk">Absen Masuk</option>
                                <option value="Absen Keluar">Absen Keluar</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select class="form-select" wire:model="filterKeterangan" wire:change="resetPageCustom">
                                <option value="">Semua Keterangan</option>
                                <option value="Tepat Waktu">Tepat Waktu</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Lembur">Lembur</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="date" class="form-control" wire:model="filterTanggal"
                                wire:change="resetPageCustom">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="border rounded p-2 bg-light">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" class="form-control" wire:model="exportFromDate">
                                @error('exportFromDate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-6">
                                <input type="date" class="form-control" wire:model="exportToDate">
                                @error('exportToDate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="col-12">
                                <button class="btn btn-success w-100" wire:click="export">
                                    <i class="fa-solid fa-file-excel me-1"></i>
                                    Export Excel
                                </button>
                            </div>
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
                            <div class="col-m d-4">
                                <select class="form-select" wire:model="status">
                                    <option value="Absen Masuk">Absen Masuk</option>
                                    <option value="Absen Keluar">Absen Keluar</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" wire:model="keterangan">
                                    <option value="Tepat Waktu">Tepat Waktu</option>
                                    <option value="Terlambat">Terlambat</option>
                                    <option value="Lembur">Lembur</option>
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
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Preview</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absensis as $absensi)
                                <tr>
                                    <td>
                                        @if ($absensi->photo_name && file_exists(public_path('storage/absensi/' . $absensi->photo_name)))
                                            <img src="{{ asset('storage/absensi/' . $absensi->photo_name) }}"
                                                width="70" class="img-thumbnail preview-img" style="cursor:pointer"
                                                data-bs-toggle="modal" data-bs-target="#photoPreviewModal"
                                                onclick="showPreview(this.src)">
                                        @else
                                            <span class="text-muted">No Photo</span>
                                        @endif
                                    </td>

                                    <td>{{ $absensi->name }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('Y-m-d') }}<br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('H:i:s') }}
                                        </small>
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $absensi->status === 'masuk' ? 'primary' : 'dark' }}">
                                            {{ ucfirst($absensi->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span
                                            class="badge bg-{{ $absensi->keterangan === 'Terlambat' ? 'danger' : ($absensi->keterangan === 'Lembur' ? 'info' : 'success') }}">
                                            {{ $absensi->keterangan }}
                                        </span>
                                    </td>

                                    <td>
                                        <a href="#" wire:click.prevent="edit({{ $absensi->id }})"
                                            class="text-warning me-2">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="#" wire:click.prevent="destroy({{ $absensi->id }})"
                                            class="text-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

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
                    {{ $absensis->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="text-white bg-warning w-100 border-0 rounded-pill text-center mt-2" wire:loading
                wire:target='destroy'>
                Loading...
            </div>
        </div>
    </div>
</div>
