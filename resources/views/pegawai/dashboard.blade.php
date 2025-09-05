@extends('pegawai.layout')

@section('content')

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

<style>
    .parent-clock {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 32px;
        font-weight: bold;
        justify-content: center;
    }

    .my-swal-title {
        font-size: 24px !important;
        font-weight: bold;
    }

    .my-swal-content {
        font-size: 18px !important;
    }

    #map {
        height: 400px !important;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 8px;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">Dashboard</h5>
                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row justify-content-center">
                        {{-- PRESENSI MASUK --}}
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header bg-secondary text-white fs-5 fw-bold text-center">Presensi Masuk</div>
                                <div class="card-body text-center">
                                    @if ($ambil_presensi_masuk)
                                        <i class="fa-regular fa-circle-check mt-2" style="color: #28a745; font-size: 60px;"></i>
                                        <p class="text-success mt-2">Anda sudah berhasil melakukan presensi masuk hari ini</p>
                                    @else
                                    <div class="tanggal fw-bold fs-4">{{ date('d F Y') }}</div>
                                    <div class="parent-clock">
                                        <div id="jam-masuk"></div>
                                        <div>:</div>
                                        <div id="menit-masuk"></div>
                                        <div>:</div>
                                        <div id="detik-masuk"></div>
                                    </div>

                                        <form action="{{ route('pegawai.ambil_foto') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="latitude_kantor" value="{{ $lokasi_presensi['latitude'] }}" required>
                                            <input type="hidden" name="longitude_kantor" value="{{ $lokasi_presensi['longitude'] }}" required>
                                            <input type="hidden" name="radius" value="{{ $lokasi_presensi['radius'] }}">
                                            <input type="hidden" name="latitude_pegawai" id="latitude_pegawai_masuk">
                                            <input type="hidden" name="longitude_pegawai" id="longitude_pegawai_masuk">
                                            <input type="hidden" name="tanggal_masuk" value="{{ date('Y-m-d') }}">
                                            <input type="hidden" name="jam_masuk" value="{{ $waktu_sekarang }}">
                                            <input type="hidden" name="id_pegawai" value="{{ Auth::user()->id }}">

                                            <button class="btn btn-primary mt-4">Masuk</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- PRESENSI KELUAR --}}
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header bg-secondary text-white fs-5 fw-bold text-center">Presensi Keluar</div>
                                <div class="card-body text-center">
                                    @if ($ambil_presensi_masuk)
                                        @if ($ambil_presensi_masuk->jam_keluar)
                                            <i class="fa-regular fa-circle-check mt-2" style="color: #28a745; font-size: 60px;"></i>
                                            <p class="text-success mt-2">Anda sudah berhasil melakukan presensi keluar hari ini</p>
                                        @else
                                            <div class="tanggal fw-bold fs-4">{{ date('d F Y') }}</div>
                                            <div class="parent-clock">
                                                <div id="jam-keluar"></div>
                                                <div>:</div>
                                                <div id="menit-keluar"></div>
                                                <div>:</div>
                                                <div id="detik-keluar"></div>
                                            </div>

                                            <form action="{{ route('pegawai.ambil_foto_keluar', ['id' => $ambil_presensi_masuk->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="latitude_kantor" value="{{ $lokasi_presensi['latitude'] }}" required>
                                                <input type="hidden" name="longitude_kantor" value="{{ $lokasi_presensi['longitude'] }}" required>
                                                <input type="hidden" name="radius" value="{{ $lokasi_presensi['radius'] }}">
                                                <input type="hidden" name="latitude_pegawai" id="latitude_pegawai_keluar">
                                                <input type="hidden" name="longitude_pegawai" id="longitude_pegawai_keluar">
                                                <input type="hidden" name="jam_keluar" value="{{ $waktu_sekarang }}">
                                                <button class="btn btn-danger mt-4">Keluar</button>
                                            </form>
                                        @endif
                                    @else
                                        <i class="fa-regular fa-circle-xmark mt-2" style="color: #ff0000; font-size: 60px;"></i>
                                        <p class="text-danger mt-2">Anda belum melakukan presensi masuk hari ini.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
    </div>

    <div id="map"></div>
</div>


{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>


{{-- Script --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        updateJam();
        setInterval(updateJam, 1000); //Jam akan diupdate per 1000ms/1detik
        getLocation();
    });

    function updateJam() {
        const waktu = new Date();
        const jam = formatWaktu(waktu.getHours());
        const menit = formatWaktu(waktu.getMinutes());
        const detik = formatWaktu(waktu.getSeconds());

        if (document.getElementById("jam-masuk")) {
            document.getElementById("jam-masuk").innerHTML = jam;
            document.getElementById("menit-masuk").innerHTML = menit;
            document.getElementById("detik-masuk").innerHTML = detik;
        }

        if (document.getElementById("jam-keluar")) {
            document.getElementById("jam-keluar").innerHTML = jam;
            document.getElementById("menit-keluar").innerHTML = menit;
            document.getElementById("detik-keluar").innerHTML = detik;
        }
    }

    function formatWaktu(waktu) {
        return waktu < 10 ? "0" + waktu : waktu;
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Browser Anda tidak mendukung geolocation.");
        }
    }

    function showPosition(position) {
    const lat = position.coords.latitude;
    const lon = position.coords.longitude;

        if (document.getElementById('latitude_pegawai_masuk')) {
            document.getElementById('latitude_pegawai_masuk').value = lat;
        }
        if (document.getElementById('longitude_pegawai_masuk')) {
            document.getElementById('longitude_pegawai_masuk').value = lon;
        }

        if (document.getElementById('latitude_pegawai_keluar')) {
            document.getElementById('latitude_pegawai_keluar').value = lat;
        }
        if (document.getElementById('longitude_pegawai_keluar')) {
            document.getElementById('longitude_pegawai_keluar').value = lon;
        } 

        initMap(lat,lon);
    }


    // Menampilkan Maps
    function initMap(lat,lon){
        var latitudeKantor = {{ $lokasi_presensi->latitude }};
        var longitudeKantor = {{ $lokasi_presensi->longitude }};
        var radius = {{ $lokasi_presensi->radius }};
        var namaLokasi = "{{ $lokasi_presensi->nama_lokasi }}";

        var map = L.map('map').setView([latitudeKantor, longitudeKantor], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(map);

        // Posisi kantor
        L.marker([latitudeKantor, longitudeKantor])
            .addTo(map)
            .bindPopup(namaLokasi)
            .openPopup();

        
        L.circle([latitudeKantor, longitudeKantor], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);

        // Posisi pegawai
        L.marker([lat, lon])
           .addTo(map)
           .bindPopup("Lokasi Anda")
           .openPopup();



    }
</script>

{{-- Switch Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('alert-type') && session('alert-message'))
        Swal.fire({
            title: "{{ session('alert-message') }}",
            icon: "{{ session('alert-type') }}",
            customClass: {
                title: 'my-swal-title',
                content: 'my-swal-content'
            }
        });
    @endif
</script>


@endsection
