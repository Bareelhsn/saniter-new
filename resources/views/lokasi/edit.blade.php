<x-layouts.app title="Pengaturan">

    <x-slot name="style">
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}">
        <style>
            #map {
                width: 100%;
                height: 400px;
            }
        </style>
    </x-slot>

    <div class="card mb-12">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Lokasi Baru</h5>
        </div>
        <form method="post" action="{{ route('lokasi.update', $lokasi->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">

                {{-- Input Regional --}}
                <div class="mb-3">
                    <x-partials.label title="Regional Kerja"/>
                    <select class="form-select @error('regional_id') is-invalid @enderror" name="regional_id" id="regional_id" onchange="getRegionalMap()">
                        <option disabled selected="">Pilih Regional Kerja</option>
                        @foreach ($regional as $r)
                            <option @if(old('regional_id') == $r->id || $lokasi->regional_id == $r->id) selected @endif value="{{ $r->id }}"> {{ $r->nama }}</option>
                        @endforeach
                    </select>
                    <x-partials.error-message class="d-block" name="regional_id" />
                </div>

                {{-- Input Nama Bandara --}}
                <div class="mb-3">
                    <x-partials.label title="Nama Bandara"/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('nama_bandara') style="border: solid red 1px;" @enderror><i class="bx bxs-plane-alt"></i></span>
                        <input type="text" name="nama_bandara" class="form-control @error('nama_bandara') is-invalid @enderror" placeholder="Bandara International I Gusti Ngurah Rai" value="{{ old('nama_bandara') ?? $lokasi->nama_bandara  }}"/>
                    </div>
                    <x-partials.error-message class="d-block" name="nama_bandara" class="d-block"/>
                </div>

                {{-- Input Lokasi Proyek --}}
                <div class="mb-3">
                    <x-partials.label title="Lokasi Proyek (Terminal)"/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('lokasi_proyek') style="border: solid red 1px;" @enderror><i class="bx bxs-map"></i></span>
                        <input type="text" name="lokasi_proyek" class="form-control @error('lokasi_proyek') is-invalid @enderror" placeholder="Terminal Domestik / International" value="{{ old('lokasi_proyek') ?? $lokasi->lokasi_proyek  }}"/>
                    </div>
                    <x-partials.error-message class="d-block" name="lokasi_proyek" />
                </div>

                <div class="row">
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
                                        <input type="number" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" placeholder="-8.6605651" step="any" value="{{ old('latitude') ?? $lokasi->lokasi_latitude  }}"/>
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
                                        <input type="number" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" placeholder="115.2154872" step="any" value="{{ old('longitude') ?? $lokasi->lokasi_longitude  }}"/>
                                    </div>
                                    <x-partials.error-message class="d-block" name="longitude" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Input Radius --}}
                <div class="">
                    <x-partials.label title="Radius"/>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" @error('radius') style="border: solid red 1px;" @enderror><i class="bx bx-trip"></i></span>
                        <input type="number" name="radius" id="radius" class="form-control @error('radius') is-invalid @enderror" placeholder="150" onkeyup="getRadiusMarker()" value="{{ old('radius') ?? $lokasi->radius  }}"/>
                    </div>
                    <x-partials.error-message class="d-block" name="radius" />
                </div>
            </div>

            <div class="card-footer mt-0">

                {{-- Button Submit --}}
                <a href="{{ route('lokasi.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Submit</button>

            </div>
        </form>
    </div>

    <x-slot name="script">
        <script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}"></script>
        <script>
            // Variable
            var map = L.map('map');
            var radiusCircle;
            var radiusCirclePop;

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
                    var lat = {{$lokasi->lokasi_latitude}};
                    var long = {{ $lokasi->lokasi_longitude }};
                }else{
                    getRegionalMap();
                }
                // Pemanggilan fungsi render map dibawah
                getMapView(lat,long,15);
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

                radiusCirclePop = L.popup()
                    .setLatLng([{{ $lokasi->lokasi_latitude }},{{ $lokasi->lokasi_longitude }}])
                    .setContent('Lokasi yang dipilih')
                    .addTo(map);
            }
        </script>

        <script>
            // Fungsi saat select regional dipilih
            function getRegionalMap(){
                if (radiusCirclePop != undefined) {
                    map.removeLayer(radiusCirclePop);
                }

                // Mengatur ajax csrf
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Ambil data dari ajax
                $.ajax({
                    type: "POST",
                    url: "{{ route('ajax.getRegionalMap') }}",
                    data: {
                        id: $('#regional_id').val() // Mengambil id pada event
                    },
                    dataType: "json",
                    success: function (response) { // Jika ajax sukses dan memberikan respon
                        console.log(response);
                        var regional = response.data;
                        $('#latitude').val(regional.latitude);
                        $('#longitude').val(regional.longitude);

                        // Render map
                        getMapView(regional.latitude,regional.longitude,11);

                        // Aktifkan popup saat pemilihan regional
                        var popupKantorQinarPusat = L.popup()
                            .setLatLng([regional.latitude, regional.longitude])
                            .setContent('Kantor Regional '+regional.nama+' PT. Qinar Raya Mandiri')
                            .addTo(map);
                    }
                });
            }
        </script>

        <script>
            // Fungsi marker saat input radius pada form
            function getRadiusMarker()
            {
                // Jika radiusCircle (Marker lingkaran merah untuk raidus) ditemukan
                if (radiusCircle != undefined) {
                    map.removeLayer(radiusCircle);
                };

                var lat = $('#latitude').val();
                var long = $('#longitude').val();
                var rad = $('#radius').val();

                // Jika input tidak kosong
                if (lat !== null && long !== null) {

                    // Buat marker pada map
                    radiusCircle = L.circle([lat, long], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: rad
                    }).addTo(map);

                }else{
                    Swal.fire({
                        title: 'Peringatan',
                        text: 'Harap pilih lokasi area pada map.',
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Kembali",
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) { // Jika tombol refresh ditekan
                            return;
                        }
                    });
                }
            }
        </script>

        <script>
            // Marker default lokasi kantor regional Qinar
            function markerDefault(map){
                // Marker Kantor Pusat
                var kantorQinarPusat = L.circle([-8.661063, 115.214712], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 100
                }).addTo(map);
                kantorQinarPusat.bindPopup("Kantor Regional Pusat PT. Qinar Raya Mandiri");

                // Marker Kantor Jakarta
                var kantorQinarJakarta = L.circle([-6.175357, 106.827192], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 100
                }).addTo(map);
                kantorQinarJakarta.bindPopup("Kantor Regional Tengah PT. Qinar Raya Mandiri");

                radiusCircle = L.circle([{{ $lokasi->lokasi_latitude }},{{ $lokasi->lokasi_longitude }}], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: {{ $lokasi->radius }}
                }).addTo(map);
                radiusCircle.bindPopup("Lokasi dipilih");
            }
        </script>
    </x-slot>
</x-layouts.app>
