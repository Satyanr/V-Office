@extends('layouts.main')

@push('css')
    <style>
        body { background-color: #f1f5f9; }
        .page-header { margin-bottom: 2rem; }
        #nprogress .bar { background: #6366f1 !important; height: 3px !important; }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        

        @livewire('admin.rekapabsensi')
    </div>
@endsection

@push('js')
    <script>
        function showPreview(src) {
            const img = document.getElementById('previewImage');
            img.style.opacity = '0';
            img.src = src;
            img.onload = function() {
                img.style.opacity = '1';
            }
        }
    </script>
@endpush