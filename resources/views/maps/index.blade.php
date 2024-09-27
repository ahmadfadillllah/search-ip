@include('layout.head', ["title" => "Maps Tower"])
@include('layout.sidebar')
@include('layout.header')
<style>
    html, body {
        height: 100%;
        margin: 0;
    }
    #map {
        height: 900px;
        /* width: 100%; */
    }
</style>

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Google Maps</h4>
            </div>
            <div class="col-lg-6">
               <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Maps</a></li>
                    <li class="breadcrumb-item active">Google Maps</li>
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
                    <h4 class="header-title mb-3">Basic</h4>
                    <div id="map"></div>
                </div>
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div> <!-- end row -->

</div>
@include('layout.footer')
<script>
    // Membuat peta
    var map = L.map('map').setView([-1.8497925, 115.8889686], 10);

    // Menambahkan layer peta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 22,
        attribution: '© Adhyyy'
    }).addTo(map);

    // Fungsi untuk mengambil data dari server
    function fetchMarkers() {
        fetch('{{ route('maps.api') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                var markers = {};
                var connections = [];

                // Menambahkan marker ke peta dengan ikon berdasarkan data
                data.markers.forEach(function(markerData) {
                    var latlng = [markerData.lat, markerData.long];

                    // Cek apakah icon ada
                    if (markerData.icon) {
                        var customIcon = L.icon({
                            iconUrl: markerData.icon, // Ambil ikon dari data marker
                            iconSize: [38, 38], // Ukuran ikon
                            iconAnchor: [19, 38], // Titik yang akan dipasang ke koordinat
                            popupAnchor: [0, -38] // Titik yang akan dipasang ke popup
                        });

                        var marker = L.marker(latlng, { icon: customIcon }).addTo(map);
                        marker.bindPopup(markerData.name);
                        markers[markerData.id] = latlng;
                    } else {
                        // console.warn('Icon URL not found for marker:', markerData.id);
                        // Jika tidak ada ikon, bisa menggunakan ikon default atau skip
                        // Gunakan ikon default jika diperlukan
                        var defaultIcon = L.icon({
                            iconUrl: '../tower.png', // Ganti dengan path ke ikon default
                            iconSize: [38, 38],
                            iconAnchor: [19, 38],
                            popupAnchor: [0, -38]
                        });

                        var marker = L.marker(latlng, { icon: defaultIcon }).addTo(map);
                        marker.bindPopup(markerData.name);
                        markers[markerData.id] = latlng;
                    }
                });

                // Menambahkan garis berdasarkan koneksi
                data.connections.forEach(function(connection) {
                    var from = markers[connection.from];
                    var to = markers[connection.to];
                    if (from && to) {
                        L.polyline([from, to], { color: 'blue' }).addTo(map);
                    }
                });

                // Memperbesar tampilan peta agar semua marker terlihat
                if (Object.keys(markers).length > 0) {
                    var allLatLngs = Object.values(markers);
                    map.fitBounds(L.latLngBounds(allLatLngs));
                }
            })
            .catch(error => console.error('Error fetching markers:', error));
    }

    // Panggil fungsi untuk mengambil data
    fetchMarkers();
</script>



