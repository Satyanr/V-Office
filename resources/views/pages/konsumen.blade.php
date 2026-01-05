@extends('layouts.main')

@push('css')
    <style>
        .preview-img {
            transition: transform .2s ease;
        }

        .preview-img:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@section('content')
    <div class="container my-5">
        <div class="shadow p-3 mb-5 bg-body-tertiary rounded-4">
            <div class="row text-center mb-5">
                <div class="col text-center">
                    <h4>Rekap Absensi</h4>
                </div>
            </div>
            @livewire('admin.konsumen')
        </div>
    </div>
@endsection

@push('js')
    <script>
        function showPreview(src) {
            document.getElementById('previewImage').src = src;
        }
    </script>
@endpush
