<div class="row">
    <div class="col">
        <form>
            <div class="row text-center">
                <div class="col">
                    <label class="form-label"><strong> Identitas </strong></label>
                </div>
            </div>
            <div class="row text-center pb-5 fs-5">
                <div class="col">
                    <div class="form-input">
                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                            wire:input="searchResult" wire:model="nama" placeholder="Nama">
                        @if ($showresult)
                            <ul class="list-group">
                                @if (!empty($konsumenlist))
                                    @foreach ($konsumenlist as $name)
                                        <a class="" href="javascript:void(0)">
                                            <li class="list-group-item" wire:click="pilihkonsumen({{ $name->id }})">
                                                {{ $name->nama }},
                                                {{ $name->email }}
                                            </li>
                                        </a>
                                    @endforeach
                                @endif
                            </ul>
                        @endif
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="form-input">
                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                            wire:model="email" placeholder="Email" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label class="form-label"><strong>Total Harga</strong></label>
                    <input type="number" class="form-control @error('total_harga') is-invalid @enderror"
                        wire:model="total_harga" placeholder="Masukkan total harga">

                    @error('total_harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label class="form-label"><strong>Tanggal Pengingat</strong></label>
                    <input type="date" class="form-control" wire:model="tanggal_pengingat">
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Keterangan</strong></label>
                    <textarea class="form-control" wire:model="keterangan"></textarea>
                </div>

            </div>

            <div class="row">
                <div class="col-auto text-center mt-2" style="margin-left: auto">
                    @if ($updatemode)
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary d-flex m-auto mt-4"
                                    wire:loading.attr="disabled" wire:target='total'
                                    wire:click.prevent='cancel'>Batal</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary d-flex m-auto mt-4"
                                    wire:loading.attr="disabled" wire:target='total'
                                    wire:click.prevent='update'>Update</button>
                            </div>
                        </div>
                    @else
                        <button type="button" class="btn btn-primary d-flex m-auto mt-4" wire:loading.attr="disabled"
                            wire:target='total' wire:click.prevent='store'>Tambahkan</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
