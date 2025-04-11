@include('layout.head', ["title" => "Maps Tower"])
@include('layout.sidebar')
@include('layout.header')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.js"></script>
<style>
    html, body {
        height: 100%;
        margin: 0;
    }
    #map {
        height: 600px;
        /* width: 100%; */
    }

    /* CSS untuk tabel dengan garis */
    #marker-table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse; /* Menyatukan garis border antar sel */
    }

    #marker-table th, #marker-table td {
        border: 1px solid #dddddd; /* Garis border */
        text-align: left; /* Menyelaraskan teks ke tengah */
        padding: 5px; /* Memberi jarak di dalam sel */
    }

    #marker-table th {
        background-color: #f2f2f2; /* Memberi warna latar belakang pada header */
        font-weight: bold; /* Menebalkan teks header */
    }

    #marker-table td {
        background-color: #ffffff; /* Memberi warna latar belakang pada sel */
    }

    #marker-table tr:hover {
        background-color: #f1f1f1; /* Efek hover pada baris */
    }

    button {
        background-color: #4CAF50; /* Warna tombol */
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 4px; /* Membuat sudut tombol lebih halus */
    }

    button:hover {
        background-color: #45a049; /* Warna tombol saat hover */
    }
</style>

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Maps Tower</h4>
            </div>
            <div class="col-lg-6">
               <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Maps</a></li>
                    <li class="breadcrumb-item active">Maps Tower</li>
                </ol>
               </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="header-title mb-3">Basic</h4> --}}
                    <div id="map"></div>
                    <div id="marker-list">
                        <table id="marker-table" style="width:100%; margin-top: 20px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tower</th>
                                    <th>Koordinat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Daftar marker akan ditambahkan disini secara dinamis -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div> <!-- end row -->

</div>
@include('layout.footer')

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYWhtYWRmYWRpbGxsbGFoIiwiYSI6ImNsMDdydXM3eDJrbm0zaGxzcXEyOTljbmUifQ.BChqppsKGxQnbG2vUDOoww';
    const markerData = [
        { lat: -1.84810572222222, lng: 115.862794916667, name: "MEGA TOWER", popupContent: "<b>MEGA TOWER</b><br>Koordinat: -1.84810572222222, 115.862794916667" }, // 0
        { lat: -1.85021180555556, lng: 115.890537166667, name: "WORKSHOP TOWER", popupContent: "<b>WORKSHOP TOWER</b><br>Koordinat: -1.85021180555556, 115.890537166667" }, // 1
        { lat: -1.85021180555556, lng: 115.890537166667, name: "UT TOWER", popupContent: "<b>UT TOWER</b><br>Koordinat: -1.85021180555556, 115.890537166667" }, // 2
        // { lat: -1.8523968977442502, lng: 115.87728734924201, name: "TOWER 01", popupContent: "<b>TOWER 01</b><br>Koordinat: -1.8523968977442502, 115.87728734924201" }, // 3
        { lat: -1.851937, lng: 115.857533, name: "TOWER 02", popupContent: "<b>TOWER 02</b><br>Koordinat: -1.851937, 115.857533" }, // 4
        { lat: -1.86610833333333, lng: 115.873419444444, name: "TOWER 03", popupContent: "<b>TOWER 03</b><br>Koordinat: -1.86610833333333, 115.873419444444" }, // 5
        { lat: -1.8509, lng: 115.873908333333, name: "TOWER 04", popupContent: "<b>TOWER 04</b><br>Koordinat: -1.8509, 115.873908333333" }, // 6
        { lat: -1.85047677777778, lng: 115.878162277778, name: "TOWER 05", popupContent: "<b>TOWER 05</b><br>Koordinat: -1.85047677777778, 115.878162277778" }, // 7
        { lat: -1.85010, lng: 115.89897, name: "TOWER 06", popupContent: "<b>TOWER 06</b><br>Koordinat: -1.85010, 115.89897" }, // 8
        { lat: -1.85523, lng: 115.89767, name: "TOWER 07", popupContent: "<b>TOWER 07</b><br>Koordinat: -1.85523, 115.89767" }, // 9
        { lat: -1.84506286111111, lng: 115.907502305556, name: "TOWER 08", popupContent: "<b>TOWER 08</b><br>Koordinat: -1.84506286111111, 115.907502305556" }, // 10
        { lat: -1.8530693072713496, lng: 115.88956180311436, name: "TOWER 09", popupContent: "<b>TOWER 09</b><br>Koordinat: -1.8530693072713496, 115.88956180311436" }, // 11
        { lat: -1.8582976747238322, lng: 115.89170528277013, name: "TOWER 10", popupContent: "<b>TOWER 10</b><br>Koordinat: -1.8582976747238322, 115.89170528277013" }, // 12
        { lat: -1.86735375, lng: 115.876042, name: "TOWER 11", popupContent: "<b>TOWER 11</b><br>Koordinat: -1.86735375, 115.876042" }, // 13
        // { lat: -1.852306119231548, lng: 115.8778339128479, name: "TOWER 12", popupContent: "<b>TOWER 12</b><br>Koordinat: -1.852306119231548, 115.8778339128479" }, // 14
        // { lat: -1.85791, lng: 115.87876, name: "TOWER 13", popupContent: "<b>TOWER 13</b><br>Koordinat: -1.85791, 115.87876" }, // 15
        // { lat: -1.83437777777778, lng: 115.925208333333, name: "TOWER 14", popupContent: "<b>TOWER 14</b><br>Koordinat: -1.83437777777778, 115.925208333333" }, // 16
        { lat: -1.85292102777778, lng: 115.904099777778, name: "TOWER 15", popupContent: "<b>TOWER 15</b><br>Koordinat: -1.85292102777778, 115.904099777778" }, // 17
        { lat: -1.845900, lng: 115.865301, name: "TOWER 16", popupContent: "<b>TOWER 16</b><br>Koordinat: -1.845900, 115.865301" }, // 18
        { lat: -1.856342643490943, lng: 115.87639202969262, name: "TOWER 17", popupContent: "<b>TOWER 17</b><br>Koordinat: -1.856342643490943, 115.87639202969262" }, // 19
        { lat: -1.846080, lng: 115.859525, name: "TOWER 18", popupContent: "<b>TOWER 18</b><br>Koordinat: -1.846080, 115.859525" }, // 20
        { lat: -1.84810572222222, lng: 115.862794916667, name: "TOWER 19", popupContent: "<b>TOWER 19</b><br>Koordinat: -1.84810572222222, 115.862794916667" }, // 21
        // { lat: -1.8582203194904288, lng: 115.8770182506771, name: "TOWER 20", popupContent: "<b>TOWER 20</b><br>Koordinat: -1.8582203194904288, 115.8770182506771" }, // 22
        { lat: -1.85360, lng: 115.86475, name: "TOWER 21", popupContent: "<b>TOWER 21</b><br>Koordinat: -1.85360, 115.86475" }, // 23
        // { lat: -1.8491689401391072, lng: 115.87923410955386, name: "TOWER 22", popupContent: "<b>TOWER 22</b><br>Koordinat: -1.8491689401391072, 115.87923410955386" }, // 24
        // { lat: -1.8507096037049047, lng: 115.87253836204926, name: "TOWER 23", popupContent: "<b>TOWER 23</b><br>Koordinat: -1.8507096037049047, 115.87253836204926" }, // 25
        { lat: -1.843409, lng: 115.871140, name: "TOWER 24", popupContent: "<b>TOWER 24</b><br>Koordinat: -1.843409, 115.871140" }, // 26
        { lat: -1.85171388888889, lng: 115.887755555556, name: "TOWER VIEW POINT", popupContent: "<b>TOWER VIEW POINT</b><br>Koordinat: -1.85171388888889, 115.887755555556" }, // 27
        { lat: -1.84582861111111, lng: 115.905278638889, name: "TOWER RUKUN", popupContent: "<b>TOWER RUKUN</b><br>Koordinat: -1.84582861111111, 115.905278638889" } // 28
    ];

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/satellite-v9',
        projection: 'globe',
        zoom: 14.2,
        center: [115.8856434, -1.8540683],
        pitch: 30,
        bearing: 0,
    });

    map.addControl(new mapboxgl.NavigationControl());
    map.scrollZoom.disable();

    const markers = markerData.map((marker, index) => {
        const el = document.createElement('div');
        el.className = 'marker';
        el.style.backgroundImage = 'url("/tower.png")';
        el.style.backgroundSize = 'cover';
        el.style.width = '120px';
        el.style.height = '70px';

        // Membuat marker di peta
        const mapMarker = new mapboxgl.Marker(el)
            .setLngLat([marker.lng, marker.lat])
            .setPopup(new mapboxgl.Popup().setHTML(marker.popupContent))
            .addTo(map);

        return { marker, mapMarker };
    });

    let currentPopup = null; // Menyimpan pop-up yang terbuka

    markerData.forEach((marker, index) => {
        const table = document.getElementById('marker-table').getElementsByTagName('tbody')[0];
        const row = table.insertRow();
        row.insertCell(0).innerHTML = index + 1;
        row.insertCell(1).innerHTML = marker.name;
        row.insertCell(2).innerHTML = `Lat: ${marker.lat}, Lng: ${marker.lng}`;
        const actionCell = row.insertCell(3);
        const button = document.createElement('button');
        button.innerHTML = 'Lihat Marker';
        button.onclick = function() {
            // Menutup pop-up yang sebelumnya terbuka
            if (currentPopup) {
                currentPopup.remove();
            }

            // Memindahkan peta ke marker yang dipilih
            map.flyTo({ center: [marker.lng, marker.lat], zoom: 16 });

            // Menampilkan pop-up untuk marker yang dipilih
            markers[index].mapMarker.togglePopup();

            // Menyimpan pop-up yang baru terbuka
            currentPopup = markers[index].mapMarker.getPopup();
        };
        actionCell.appendChild(button);
    });

    function drawLineBetweenTowers(tower1, tower2, lineId) {
        const lineCoordinates = [
            [tower1.lng, tower1.lat],
            [tower2.lng, tower2.lat]
        ];

        map.addLayer({
            id: lineId,
            type: 'line',
            source: {
                type: 'geojson',
                data: {
                    type: 'Feature',
                    geometry: {
                        type: 'LineString',
                        coordinates: lineCoordinates
                    }
                }
            },
            paint: {
                'line-color': '#FF5733',
                'line-width': 3,
                'line-opacity': 0.8
            }
        });
    }

    drawLineBetweenTowers(markerData[0], markerData[1], 'line-0-1');
    drawLineBetweenTowers(markerData[1], markerData[2], 'line-1-2');
    // Tambahkan lebih banyak garis sesuai kebutuhan

</script>


