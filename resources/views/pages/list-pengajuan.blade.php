@extends('layouts.main')

@push('css')
<style>
    body { background-color: #f4f7fe; }
    .page-title { font-weight: 800; color: #1B2559; letter-spacing: -0.02em; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h2 class="page-title mb-1">List Pengajuan</h2>
            <p class="text-muted">Kelola data kehadiran dan izin karyawan V-Office</p>
        </div>
    </div>

    @livewire('admin.list-pengajuan')
</div>
@endsection

@push('js')
<script>
    function showPreview(src) {
         document.getElementById('previewImage').src = src;
    }
</script>
@endpush