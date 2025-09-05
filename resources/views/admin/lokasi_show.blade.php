@extends('admin.layout', ['title' => 'Detail Lokasi'])

@section('content')

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

<style>
    #map {
        height: 400px !important;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 8px;
    }
</style>

<div class="row">
        <div class="col-md-6">
        <div class="card">
            <h5 class="card-header">Detail Lokasi Presensi</h5>
            <div class="card-body">
                <a href="{{ route('lokasi.index') }}" class="btn btn-secondary mb-3">← Kembali</a>
                <table class="table table-bordered">
                    <tr>
                        <th>Kode Lokasi</th>
                        <td>{{ $lokasi->kode_lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lokasi</th>
                        <td>{{ $lokasi->nama_lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $lokasi->alamat }}</td>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <td>{{ $lokasi->latitude }}</td>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <td>{{ $lokasi->longitude }}</td>
                    </tr>
                    <tr>
                        <th>Radius</th>
                        <td>{{ $lokasi->radius }} meter</td>
                    </tr>
                    <tr>
                        <th>Zona Waktu</th>
                        <td>{{ strtoupper($lokasi->zona_waktu) }}</td>
                    </tr>
                    <tr>
                        <th>Jam Masuk</th>
                        <td>{{ $lokasi->jam_masuk }}</td>
                    </tr>
                    <tr>
                        <th>Jam Keluar</th>
                        <td>{{ $lokasi->jam_keluar }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <h5 class="card-header">Peta Lokasi</h5>
            <div class="card-body">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var latitude = {{ $lokasi->latitude ?? 0 }};
        var longitude = {{ $lokasi->longitude ?? 0 }};
        var radius = {{ $lokasi->radius ?? 0 }};

        var map = L.map('map').setView([latitude, longitude], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([latitude, longitude])
            .addTo(map)
            .bindPopup("{{ $lokasi->nama_lokasi }}")
            .openPopup();

        
    });
</script>



@endsection
