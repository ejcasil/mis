<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<!-- Control Buttons -->
<div class="container-custom">
    <!-- Search Box -->
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="search-input" placeholder="Search location by remarks" aria-label="Recipient's username" aria-describedby="btnSearch">
        <button class="btn btn-sm btn-success border-1 border-white" onclick="searchLocation()" data-bs-toggle="tooltip" data-bs-title="Search" id="btnSearch">
            <i class="fas fa-search"></i>
        </button>
        <!-- Route Button -->
        <button class="btn btn-sm btn-success border-1 border-white" onclick="displayRoute()" data-bs-toggle="tooltip" data-bs-title="Display Route">
            <i class="fas fa-route"></i>
        </button>

        <!-- Geolocation Button -->
        <button class="btn btn-sm btn-success border-1 border-white" onclick="showUserLocation()" data-bs-toggle="tooltip" data-bs-title="Show My Location">
            <i class="fas fa-location-arrow"></i>
        </button>

        <!-- Clear Route Button -->
        <button class="btn btn-sm btn-success border-1 border-white" onclick="clearRoute()" data-bs-toggle="tooltip" data-bs-title="Clear Route">
            <i class="fas fa-eraser"></i>
        </button>
    </div>

    <select class="form-select" id="map-type-selector" onchange="changeMapType()">
        <option value="osm">Street Map (OpenStreetMap)</option>
        <option value="terrain">Terrain</option>
        <option value="satellite">Satellite Imagery</option>
    </select>

</div>

<!-- Main Map Area -->
<div id="map"></div>

<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    // Initialize the map
    var map = L.map('map').setView([18.563365067, 121.226950233], 13);

    // Default tile layer (OpenStreetMap)
    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Terrain tile layer (OpenTopoMap)
    var terrainLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://opentopomap.org/copyright">OpenTopoMap</a>'
    });

    // Satellite tile layer (Using Mapbox for satellite imagery)
    var satelliteLayer = L.tileLayer('https://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    // Layer control (currentLayer will hold the selected map type)
    var currentLayer = osmLayer; // Initial layer is OpenStreetMap

    // Function to change map layer based on user selection
    function changeMapType() {
        var selectedMapType = document.getElementById('map-type-selector').value;

        // Remove the current layer
        map.removeLayer(currentLayer);

        // Set the new layer based on the selection
        switch (selectedMapType) {
            case 'osm':
                currentLayer = osmLayer;
                break;
            case 'terrain':
                currentLayer = terrainLayer;
                break;
            case 'satellite':
                currentLayer = satelliteLayer;
                break;
            default:
                currentLayer = osmLayer;
        }

        // Add the selected layer to the map
        map.addLayer(currentLayer);
    }

    // Leaflet Routing Machine (same as your original code)
    var routingControl = null;
    var geoJsonData;
    var markersLayer = L.layerGroup().addTo(map); // Group for markers
    var selectedDestination = null; // Store selected destination for route

    // Load GeoJSON data
    $.ajax({
        url: '<?= site_url('maps/getGeoJson3'); ?>', // Adjust with your controller's URL
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            geoJsonData = data;
            displayMarkers(geoJsonData); // Display markers when data loads
        },
        error: function(error) {
            console.error('Error loading GeoJSON data:', error);
        }
    });

    // Function to display markers on the map
    function displayMarkers(data) {
        markersLayer.clearLayers(); // Clear previous markers
        L.geoJSON(data, {
            onEachFeature: function(feature, layer) {
                var popupContent = "<b>Remarks:</b> " + feature.properties.Remarks + "<br>" +
                    "<b>Time:</b> " + feature.properties.Time + "<br>" +
                    "<b>Photo:</b><br><img src='" + feature.properties.Photo + "' style='width: 200px; height: 200px;'>";

                layer.bindPopup(popupContent);
                layer.on('click', function() {
                    selectedDestination = layer.getLatLng();
                });
            }
        }).addTo(markersLayer);
    }

    // Search for a location based on remarks
    function searchLocation() {
        var query = document.getElementById('search-input').value.toLowerCase();
        var filteredData = {
            type: "FeatureCollection",
            features: []
        };

        geoJsonData.features.forEach(function(feature) {
            if (feature.properties.Remarks.toLowerCase().includes(query)) {
                filteredData.features.push(feature);
            }
        });

        if (filteredData.features.length > 0) {
            displayMarkers(filteredData);
        } else {
            showToast("No location found matching your search.");
        }
    }

    // Display a route from user's current location to selected destination
    function displayRoute() {
        if (selectedDestination) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;
                    var userLocation = L.latLng(userLat, userLng);

                    routingControl = L.Routing.control({
                        waypoints: [userLocation, selectedDestination],
                        routeWhileDragging: true
                    }).addTo(map);
                }, function(error) {
                    showToast("Error getting location: " + error.message);
                });
            }
        } else {
            showToast("Select a destination first.");
        }
    }

    // Show user location on the map
    function showUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;
                var userLocation = L.latLng(userLat, userLng);

                map.setView(userLocation, 45);

                // Add a marker at the user's location with a popup
                L.marker(userLocation).addTo(map).bindPopup("You are here").openPopup();
            }, function(error) {
                showToast("Error getting location: " + error.message);
            });
        } else {
            showToast("Geolocation is not supported by this browser.");
        }
    }


    // Clear the displayed route
    function clearRoute() {
        if (routingControl) {
            routingControl.remove();
        }
    }

    // Initialize Bootstrap tooltips
    $(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
<?= $this->endSection('my_script') ?>