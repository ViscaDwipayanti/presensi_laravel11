@extends('pegawai.layout')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    
    <form method="POST" action="{{ route('pegawai.presensi_masuk_aksi') }}">
        @csrf
        <input type="hidden" id="id_pegawai" name="id_pegawai" value="{{ $id_pegawai }}">
        <input type="hidden" id="tanggal_masuk" name="tanggal_masuk" value="{{ $tanggal_masuk }}">
        <input type="hidden" id="jam_masuk" name="jam_masuk" value="{{ $jam_masuk }}">
        <input type="hidden" id="terlambat" name="terlambat" value="{{ $terlambat }}">

        <div id="my_camera"></div>
        <div id="my_result" class="mt-2"></div>

        <button type="button" class="btn btn-primary mt-3" id="ambil-foto">Simpan</button>
    </form>

    <script>
        Webcam.set({
            width : 320,
            height : 240,
            dest_width : 320,
            dest_height : 240,
            image_format : 'jpeg',
            jpeg_quality : 90
        });

        Webcam.attach('#my_camera');

        document.getElementById('ambil-foto').addEventListener('click', function(){
            let id = document.getElementById('id_pegawai').value;
            let tanggal_masuk = document.getElementById('tanggal_masuk').value;
            let jam_masuk = document.getElementById('jam_masuk').value;
            let terlambat = document.getElementById('terlambat').value;
            let token = '{{ csrf_token() }}';

            Webcam.snap(function(data_uri){
                document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '" />';
                
                //Ajax untuk mengirimkan data
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        window.location.href = "{{ route('pegawai.dashboard') }}";
                    }
                };
                xhttp.open("POST", "{{ route('pegawai.presensi_masuk_aksi') }}", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(
                    '_token=' + token + 
                    '&foto_masuk=' + encodeURIComponent(data_uri) +
                    '&id_pegawai=' + encodeURIComponent(id) +
                    '&tanggal_masuk=' + encodeURIComponent(tanggal_masuk) +
                    '&jam_masuk=' + encodeURIComponent(jam_masuk) +
                    '&terlambat=' + encodeURIComponent(terlambat)
                );
            });
        });
    </script>
@endsection
