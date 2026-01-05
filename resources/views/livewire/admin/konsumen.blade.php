<div>
    <div class="row text-center justify-content-between">
        <div class="col">
            <div class="row justify-content-between">
                <div class="col-auto">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i
                                class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control" placeholder="Cari Absen" aria-label="Cari Absen"
                            aria-describedby="basic-addon1" wire:model='searchabsensi' wire:input='resetKonsumenPage'>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-success d-flex m-auto mt-4"
                        wire:click.prevent='export'>Export Rekap</button>
                </div>
            </div>

            @if ($updateMode)
                <div class="row">
                    <div class="col">
                        <form>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Nama" aria-label="Nama"
                                    aria-describedby="basic-addon2" wire:model='nama'>
                                <input type="number" class="form-control" placeholder="Nomor Telp"
                                    wire:model='no_telp'>
                                <button class="btn btn-primary" type="button"
                                    wire:click.prevent='update'>Update</button>
                                <button class="btn btn-secondary" type="button"
                                    wire:click.prevent='cancel'>Batal</button>
                            </div>
                        </form>
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
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absensis as $absensi)
                                <tr>
                                    <td>
                                        @if ($absensi->photo_name && file_exists(public_path('storage/absensi/' . $absensi->photo_name)))
                                            <img src="{{ asset('storage/absensi/' . $absensi->photo_name) }}"
                                                width="80" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">No Photo</span>
                                        @endif
                                    </td>
                                    <td>{{ $absensi->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('H:i:s') }}</td>

                                    <td>
                                        <a href="javascript:void(0)" class="text-warning"
                                            wire:click.prevent='edit({{ $absensi->id }})'><i
                                                class="fa fa-pencil"></i></a>

                                        <a href="javascript:void(0)" class="text-danger"
                                            wire:click.prevent='destroy({{ $absensi->id }})'><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">Tidak Ada Rekap Terdata</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
