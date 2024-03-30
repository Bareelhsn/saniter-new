<x-layouts.app title="Edit Regional">

    <x-slot name="style">
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}">
        <style>
            #map {
                width: 100%;
                height: 400px;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan / Regional /</span> Tambah Regional</h4>

    <div class="card mb-12">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Tambah Regional</h5>
        </div>
        <form method="post" action="{{ route('regional.update', $regional->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="mb-3">
                    <x-partials.label title="Nama Regional" />
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname" class="input-group-text" @error('nama') style="border: 1px solid red" @enderror><i class="bx bx-user"></i></span>
                        <x-partials.input-text name="nama" placeholder="Nama Regional" value="{{ old('nama') ?? $regional->nama }}" />
                    </div>
                    <x-partials.error-message name="nama" class="d-block"/>
                </div>

                <div class="row mb-0">
                    <div class="col-12 mb-3">

                        {{-- Map Leaflet --}}
                        <div id="map"></div>

                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col">

                                {{-- Input Latitude --}}
                                <div class="mb-3">
                                    <x-partials.label title="Latitude"/>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text" @error('latitude') style="border: solid red 1px;" @enderror><i class="bx bx-area"></i></span>
                                        <input type="number" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" placeholder="-8.6605651" step="any" value="{{ old('latitude') ?? $regional->latitude }}"/>
                                    </div>
                                    <x-partials.error-message class="d-block" name="latitude" />
                                </div>

                            </div>
                            <div class="col">

                                {{-- Input Longitude --}}
                                <div class="mb-3">
                                    <x-partials.label title="Longitude"/>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text" @error('longitude') style="border: solid red 1px;" @enderror><i class="bx bx-area"></i></span>
                                        <input type="number" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" placeholder="115.2154872" step="any" value="{{ old('longitude') ?? $regional->longitude }}"/>
                                    </div>
                                    <x-partials.error-message class="d-block" name="longitude" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer mt-0">
                <a href="{{ route('regional.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <x-slot name="script">
        <script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}"></script>
        <script>
            $(document).ready(function () {
                getGeo();
            });

            // Geolocation, menentukan lokasi berdasarkan device
            function getGeo(){
                const config = {
                    enableHighAccuracy: true,
                    timeout: 10000,
                };

                if (navigator.geolocation) {
                    // Cek lokasi sesuai map, parameter jika sukses dan error
                    navigator.geolocation.getCurrentPosition(locationSuccess, showError, config);
                } else {
                    // Jika tidak mendukung geolocation
                    sweetAlertMessage('Peringatan!', 'Browser anda tidak mendukung lokasi.');
                }
            }

            // Jika pencarian lokasi sukses
            function locationSuccess(position) {
                // Set default map indonesia
                if ($("#radius_id").val() == null) {
                    var lat = {{$regional->latitude}};
                    var long = {{$regional->longitude}};
                }else{
                    getRegionalMap();
                }
                // Pemanggilan fungsi render map dibawah
                getMapView(lat,long,14);
            }

            // Jika pencarian lokasi error
            function showError(error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        sweetAlertMessage('Peringatan!', 'Aktifkan pencarian lokasi pada browser dan device.', 'warning');
                    break;
                    case error.POSITION_UNAVAILABLE:
                        sweetAlertMessage('Peringatan!', 'Informasi lokasi tidak tersedia.', 'warning');
                    break;
                    case error.TIMEOUT:
                        sweetAlertMessage('Peringatan!', 'Timeout saat mencari lokasi.', 'warning');
                    break;
                    case error.UNKNOWN_ERROR:
                        sweetAlertMessage('Peringatan!', 'Terdapat kesalahan saat mencari lokasi.', 'warning');
                    break;
                }
            }

            // SweetAlert
            function sweetAlertMessage($title, $message, $icon)
            {
                Swal.fire({
                    title: $title,
                    text: $message,
                    icon: $icon,
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Refresh",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) { // Jika tombol refresh ditekan
                        location.reload();
                    }
                });
            }
        </script>

        <script>
            // Fungsi render map leaflet
            var map = L.map('map');
            var radiusCircle;
            function getMapView(lat, long, zoom)
            {
                // Reload map setiap render
                map.reload = function(){
                    map.remove();
                    map = L.map('map').setView([lat, long], zoom);
                }
                map.reload();

                // Library Openstreet map
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                // Fungsi saat map diklik akan muncul popups longitude dan latitude
                var popup = L.popup();
                function onMapClick(e) {
                    $('#latitude').val(e.latlng.lat);
                    $('#longitude').val(e.latlng.lng);
                    $('#radius').val('');

                    // Jika radiusCircle (Marker lingkaran merah untuk raidus) ditemukan
                    if (radiusCircle != undefined) {
                        map.removeLayer(radiusCircle);
                    };

                    // Popup
                    popup.setLatLng(e.latlng).setContent("Anda memilih pada " + e.latlng.toString()).openOn(map);
                }

                // Jika map diklik
                map.on('click', onMapClick);

                // Pemanggilan default marker kantor regional Qinar
                markerDefault(map);

                var regionalTargetPop = L.popup()
                    .setLatLng([{{ $regional->latitude }},{{ $regional->longitude }}])
                    .setContent("Kantor Regional {{ $regional->nama }} PT. Qinar Raya Mandiri")
                    .addTo(map);
            }
        </script>

        <script>
            var vars = {};
            // Marker default lokasi kantor regional Qinar
            function markerDefault(map){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Ambil data dari ajax
                $.ajax({
                    type: "POST",
                    url: "{{ route('ajax.getAllRegionalMap') }}",
                    data: {
                        id: $('#regional_id').val() // Mengambil id pada event
                    },
                    dataType: "json",
                    success: function (response) { // Jika ajax sukses dan memberikan respon
                        response.data.forEach(element => {
                            vars['Kantor'+element.nama] = L.circle([element.latitude, element.longitude], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 100
                            }).addTo(map);
                            vars['Kantor'+element.nama].bindPopup("Kantor Regional "+element.nama+" PT. Qinar Raya Mandiri");
                        });
                    }
                });
            }
        </script>
    </x-slot>

</x-layouts.app>
