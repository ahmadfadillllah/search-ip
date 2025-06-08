<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>All Not Realtime</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://api.mapbox.com/mapbox-gl-js/v3.11.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.11.0/mapbox-gl.js"></script>
<style>
body { margin: 0; padding: 0; }
#map { position: absolute; top: 0; bottom: 0; width: 100%; }
</style>
</head>
<body>
<div id="map"></div>
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYWhtYWRmYWRpbGxsbGFoIiwiYSI6ImNsMDdydXM3eDJrbm0zaGxzcXEyOTljbmUifQ.BChqppsKGxQnbG2vUDOoww';

    const roadData = @json($dataMaps);
    const markerData = @json($data);
    const markerDataDisposal = @json($dataMapsDisposal);
    const markerDataFront = @json($dataMapsFront);

    const markerTower = [
        { lat: -1.8491457, lng: 115.8889063, name: "MEGA TOWER", popupContent: "<b>MEGA TOWER</b><br>Koordinat: -1.8491457, 115.8889063" }, // 0
        { lat: -1.8487747, lng: 115.8885949, name: "WORKSHOP TOWER", popupContent: "<b>WORKSHOP TOWER</b><br>Koordinat: -1.8487747, 115.8885949" }, // 1
        { lat: -1.8470510292009323, lng: 115.88880068702747, name: "UT TOWER", popupContent: "<b>UT TOWER</b><br>Koordinat: -1.8470510292009323, 115.88880068702747" }, // 2
        { lat: -1.8523968977442502, lng: 115.87728734924201, name: "TOWER 01", popupContent: "<b>TOWER 01</b><br>Koordinat: -1.8523968977442502, 115.87728734924201" }, // 3
        { lat: -1.851937, lng: 115.857533, name: "TOWER 02", popupContent: "<b>TOWER 02</b><br>Koordinat: -1.851937, 115.857533" }, // 4
        { lat: -1.8571332698140215, lng: 115.87187862808199, name: "TOWER 03", popupContent: "<b>TOWER 03</b><br>Koordinat: -1.8571332698140215, 115.87187862808199" }, // 5
        { lat: -1.8498514659030518, lng: 115.87066309013716, name: "TOWER 04", popupContent: "<b>TOWER 04</b><br>Koordinat: -1.8498514659030518, 115.87066309013716" }, // 6
        { lat: -1.8492322977684816, lng: 115.87139624909676, name: "TOWER 05", popupContent: "<b>TOWER 05</b><br>Koordinat: -1.8492322977684816, 115.87139624909676" }, // 7
        { lat: -1.8523306134917488, lng: 115.89467781229963, name: "TOWER 06", popupContent: "<b>TOWER 06</b><br>Koordinat: -1.8523306134917488, 115.89467781229963" }, // 8
        { lat: -1.855895938059835, lng: 115.89481252726716, name: "TOWER 07", popupContent: "<b>TOWER 07</b><br>Koordinat: -1.855895938059835, 115.89481252726716" }, // 9
        { lat: -1.8541177236610662, lng: 115.90728844774414, name: "TOWER 08", popupContent: "<b>TOWER 08</b><br>Koordinat: -1.8541177236610662, 115.90728844774414" }, // 10
        { lat: -1.8530693072713496, lng: 115.88956180311436, name: "TOWER 09", popupContent: "<b>TOWER 09</b><br>Koordinat: -1.8530693072713496, 115.88956180311436" }, // 11
        { lat: -1.8582976747238322, lng: 115.89170528277013, name: "TOWER 10", popupContent: "<b>TOWER 10</b><br>Koordinat: -1.8582976747238322, 115.89170528277013" }, // 12
        { lat: -1.8589318073486247, lng: 115.87510947594703, name: "TOWER 11", popupContent: "<b>TOWER 11</b><br>Koordinat: -1.8589318073486247, 115.87510947594703" }, // 13
        { lat: -1.852306119231548, lng: 115.8778339128479, name: "TOWER 12", popupContent: "<b>TOWER 12</b><br>Koordinat: -1.852306119231548, 115.8778339128479" }, // 14
        { lat: -1.853336332866685, lng: 115.87770897314037, name: "TOWER 13", popupContent: "<b>TOWER 13</b><br>Koordinat: -1.853336332866685, 115.87770897314037" }, // 15
        { lat: -1.8557668042728035, lng: 115.89791082577015, name: "TOWER 14", popupContent: "<b>TOWER 14</b><br>Koordinat: -1.8557668042728035, 115.89791082577015" }, // 16
        { lat: -1.8586360278433387, lng: 115.90605094485427, name: "TOWER 15", popupContent: "<b>TOWER 15</b><br>Koordinat: -1.8586360278433387, 115.90605094485427" }, // 17
        { lat: -1.845900, lng: 115.865301, name: "TOWER 16", popupContent: "<b>TOWER 16</b><br>Koordinat: -1.845900, 115.865301" }, // 18
        { lat: -1.856342643490943, lng: 115.87639202969262, name: "TOWER 17", popupContent: "<b>TOWER 17</b><br>Koordinat: -1.856342643490943, 115.87639202969262" }, // 19
        { lat: -1.846080, lng: 115.859525, name: "TOWER 18", popupContent: "<b>TOWER 18</b><br>Koordinat: -1.846080, 115.859525" }, // 20
        { lat: -1.846737577686091, lng: 115.86680603168061, name: "TOWER 19", popupContent: "<b>TOWER 19</b><br>Koordinat: -1.846737577686091, 115.86680603168061" }, // 21
        { lat: -1.8582203194904288, lng: 115.8770182506771, name: "TOWER 20", popupContent: "<b>TOWER 20</b><br>Koordinat: -1.8582203194904288, 115.8770182506771" }, // 22
        { lat: -1.8522984116070589, lng: 115.8639880372815, name: "TOWER 21", popupContent: "<b>TOWER 21</b><br>Koordinat: -1.8522984116070589, 115.8639880372815" }, // 23
        { lat: -1.8491689401391072, lng: 115.87923410955386, name: "TOWER 22", popupContent: "<b>TOWER 22</b><br>Koordinat: -1.8491689401391072, 115.87923410955386" }, // 24
        { lat: -1.8507096037049047, lng: 115.87253836204926, name: "TOWER 23", popupContent: "<b>TOWER 23</b><br>Koordinat: -1.8507096037049047, 115.87253836204926" }, // 25
        { lat: -1.843409, lng: 115.871140, name: "TOWER 24", popupContent: "<b>TOWER 24</b><br>Koordinat: -1.843409, 115.871140" }, // 26
        { lat: -1.8503514866536446, lng: 115.88760047083625, name: "TOWER VIEW POINT", popupContent: "<b>TOWER VIEW POINT</b><br>Koordinat: -1.8503514866536446, 115.88760047083625" }, // 27
        { lat: -1.853250379035633, lng: 115.90498845940964, name: "TOWER RUKUN", popupContent: "<b>TOWER RUKUN</b><br>Koordinat: -1.853250379035633, 115.90498845940964" } // 28
    ];

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/satellite-v9',
        projection: 'globe',
        zoom: 14.7,
        center: [115.885858, -1.8569421],
        pitch: 30,
        bearing: 0,
    });

    map.addControl(new mapboxgl.NavigationControl());
    map.scrollZoom.disable();

    let currentPopup = null;

    // Create marker map function
    const markers = markerTower.map((marker, index) => {
        const el = document.createElement('div');
        el.className = 'marker';
        el.style.backgroundImage = 'url("/tower.png")';
        el.style.backgroundSize = 'cover';
        el.style.width = '120px';
        el.style.height = '70px';

        // Create marker on the map
        const mapMarker = new mapboxgl.Marker(el)
            .setLngLat([marker.lng, marker.lat])
            .setPopup(new mapboxgl.Popup().setHTML(marker.popupContent))
            .addTo(map);

        return { marker, mapMarker };
    });

    // Adding rows to the table for each marker
    map.on('style.load', () => {
        markerData.forEach(coord => {
            let markerColor = "";

            if (coord.latency < 5.0) {
                markerColor = "green";
            } else if (coord.latency >= 5.0 && coord.latency <= 10.0) {
                markerColor = "orange";
            } else {
                markerColor = "red";
            }

            new mapboxgl.Marker({
                color: markerColor,
                draggable: false
            })
            .setLngLat([coord.lng, coord.lat])
            .addTo(map);
        });

        // Draw roads on map
        roadData.forEach(dataMaps => {
            if (Array.isArray(dataMaps.GEOPOINTS) && dataMaps.GEOPOINTS.length > 0) {
                const coordinates = dataMaps.GEOPOINTS.map(coord => [coord[0], coord[1]]);
                map.addLayer({
                    id: `road-line-${dataMaps.MAPOBJECTID}`,
                    type: 'line',
                    source: {
                        type: 'geojson',
                        data: {
                            type: 'Feature',
                            geometry: {
                                type: 'LineString',
                                coordinates: coordinates
                            }
                        }
                    },
                    paint: {
                        'line-color': '#0077B6',
                        'line-width': 5
                    }
                });
            }
        });

        // Disposal markers
        let disposalMarkers = [];
        markerDataDisposal.forEach(coord => {
            const el = document.createElement('div');
            el.className = 'circle-marker';
            el.style.backgroundColor = '#6FC276';
            el.style.width = '30px';
            el.style.height = '30px';
            el.style.borderRadius = '50%';
            el.style.cursor = 'pointer';
            el.style.display = 'flex';
            el.style.alignItems = 'center';
            el.style.justifyContent = 'center';
            el.style.color = 'white';
            el.style.fontSize = '12px';
            el.style.fontWeight = 'bold';
            el.style.textAlign = 'center';

            el.innerText = coord.TITLE;

            const marker = new mapboxgl.Marker(el)
                .setLngLat([coord.LONGITUDE, coord.LATITUDE]);

            disposalMarkers.push(marker);
            marker.addTo(map);
        });

        // Front markers
        let frontMarkers = [];
        markerDataFront.forEach(coord => {
            const el = document.createElement('div');
            el.className = 'circle-marker';
            el.style.backgroundColor = '#800080';
            el.style.width = '30px';
            el.style.height = '30px';
            el.style.borderRadius = '50%';
            el.style.cursor = 'pointer';
            el.style.display = 'flex';
            el.style.alignItems = 'center';
            el.style.justifyContent = 'center';
            el.style.color = 'white';
            el.style.fontSize = '12px';
            el.style.fontWeight = 'bold';
            el.style.textAlign = 'center';

            el.innerText = coord.TITLE;

            const marker = new mapboxgl.Marker(el)
                .setLngLat([coord.LONGITUDE, coord.LATITUDE]);

            frontMarkers.push(marker);
            marker.addTo(map);
        });

        // Button for disposal markers
        let disposalVisible = true;
        const disposalBtn = document.createElement('button');
        disposalBtn.innerHTML = 'Turn Off Disposal Markers';
        disposalBtn.className = 'turn-on-off-btn';
        disposalBtn.style.position = 'absolute';
        disposalBtn.style.top = '10px';
        disposalBtn.style.left = '10px';
        disposalBtn.style.backgroundColor = '#0077B6';
        disposalBtn.style.color = '#fff';
        disposalBtn.style.padding = '10px';
        disposalBtn.style.borderRadius = '5px';
        document.body.appendChild(disposalBtn);

        disposalBtn.addEventListener('click', () => {
            if (disposalVisible) {
                disposalMarkers.forEach(marker => marker.remove());
                disposalBtn.innerHTML = 'Turn On Disposal Markers';
            } else {
                disposalMarkers.forEach(marker => marker.addTo(map));
                disposalBtn.innerHTML = 'Turn Off Disposal Markers';
            }
            disposalVisible = !disposalVisible;
        });

        // Button for front markers
        let frontVisible = true;
        const frontBtn = document.createElement('button');
        frontBtn.innerHTML = 'Turn Off Front Markers';
        frontBtn.className = 'turn-on-off-btn';
        frontBtn.style.position = 'absolute';
        frontBtn.style.top = '50px';
        frontBtn.style.left = '10px';
        frontBtn.style.backgroundColor = '#800080';
        frontBtn.style.color = '#fff';
        frontBtn.style.padding = '10px';
        frontBtn.style.borderRadius = '5px';
        document.body.appendChild(frontBtn);

        frontBtn.addEventListener('click', () => {
            if (frontVisible) {
                frontMarkers.forEach(marker => marker.remove());
                frontBtn.innerHTML = 'Turn On Front Markers';
            } else {
                frontMarkers.forEach(marker => marker.addTo(map));
                frontBtn.innerHTML = 'Turn Off Front Markers';
            }
            frontVisible = !frontVisible;
        });

        let markerTowerVisible = true;
        const markerTowerBtn = document.createElement('button');
        markerTowerBtn.innerHTML = 'Turn Off Tower Markers';
        markerTowerBtn.className = 'turn-on-off-btn';
        markerTowerBtn.style.position = 'absolute';
        markerTowerBtn.style.top = '90px'; // Adjust this to place the button below the others
        markerTowerBtn.style.left = '10px';
        markerTowerBtn.style.backgroundColor = '#ff4500';
        markerTowerBtn.style.color = '#fff';
        markerTowerBtn.style.padding = '10px';
        markerTowerBtn.style.borderRadius = '5px';
        document.body.appendChild(markerTowerBtn);

        markerTowerBtn.addEventListener('click', () => {
            if (markerTowerVisible) {
                markers.forEach(markerObj => markerObj.mapMarker.remove());
                markerTowerBtn.innerHTML = 'Turn On Tower Markers';
            } else {
                markers.forEach(markerObj => markerObj.mapMarker.addTo(map));
                markerTowerBtn.innerHTML = 'Turn Off Tower Markers';
            }
            markerTowerVisible = !markerTowerVisible;
        });

    });
</script>

</body>
</html>
