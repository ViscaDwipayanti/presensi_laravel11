@extends('pegawai.layout')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    
    <form method="POST" action="{{ route('pegawai.presensi_keluar_aksi', $id_presensi) }}">
        @csrf
        <input type="hidden" id="id_presensi" name="id_presensi" value="{{ $id_presensi }}">
        <input type="hidden" id="tanggal_keluar" name="tanggal_keluar" value="{{ $tanggal_keluar }}">
        <input type="hidden" id="jam_keluar" name="jam_keluar" value="{{ $jam_keluar }}">
        <input type="hidden" id="total_jam" name="total_jam" value="{{ $total_jam }}">

        <div id="my_camera"></div>
        <div id="my_result" class="mt-2"></div>

        <button type="button" class="btn btn-primary mt-3" id="ambil-foto-keluar">Simpan</button>
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

        document.getElementById('ambil-foto-keluar').addEventListener('click', function(){
            let tanggal_keluar = document.getElementById('tanggal_keluar').value;
            let jam_keluar = document.getElementById('jam_keluar').value;
            let total_jam = document.getElementById('total_jam').value;
            let token = '{{ csrf_token() }}';

            Webcam.snap(function(data_uri){
                document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '" />';
                
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        window.location.href = "{{ route('pegawai.dashboard') }}";
                    }
                };
                xhttp.open("POST", "{{ route('pegawai.presensi_keluar_aksi', $id_presensi) }}", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(
                    '_token=' + token +
                    '&foto_keluar=' + encodeURIComponent(data_uri) +
                    '&tanggal_keluar=' + encodeURIComponent(tanggal_keluar) +
                    '&jam_keluar=' + encodeURIComponent(jam_keluar) +
                    '&total_jam=' + encodeURIComponent(total_jam)
                );
            });
        });
    </script>
@endsection
