@extends('layouts.main')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
@endpush

@section('content')
    <div class="container my-5">
        <div class="shadow p-3 mb-5 bg-body-tertiary rounded-4">
            <form method="POST" action="#"">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Name" /> <br />
                        <input type="text" name="email" class="form-control" placeholder="Email Address" /><br />
                        <input type="password" name="password" class="form-control" placeholder="Password" /><br />
                        <input type="button" class="btn btn-sm btn-primary" id="open_camera" value ="Open Camera"><br />
                        <div id="my_camera" class="d-none"></div>
                        <br />
                        <input type=button value="Take Snapshot" onClick="take_snapshot()">
                        <input type="hidden" name="image" class="image-tag">
                    </div>
                    <div class="col-md-6">
                        <div id="results"></div>
                    </div>
                    <div class="col-md-12 text-center">
                        <br />
                        <button class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection

@push('js')
    <script language="JavaScript">
        $("#open_camera").click(function() {
            $("#my_camera").removeClass('d-none');
            $("#take_snap").removeClass('d-none');

            Webcam.set({
                width: 250,
                height: 190,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#my_camera');
        });

        function take_snapshot() {
            Webcam.snap(function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
            });
        }
    </script>
@endpush
