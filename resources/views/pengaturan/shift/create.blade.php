<x-layouts.app title="Shift">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
            input[type=time]::-webkit-datetime-edit-ampm-field {
                display: none;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan / Shift /</span> Tambah Shift</h4>

<!-- Striped Rows -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Shift</h5>
    </div>
    <form method="post" action="{{ route('shift.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">

            {{-- Input Nama Shift --}}
            <x-partials.label title="Shift" />
            <select name="nama" class="form-control @error('nama') is-invalid @enderror">
                <option value="" selected disabled>Pilih Shift...</option>
                <option value="Pagi" @if(old('nama') == "Pagi") selected @endif>Pagi</option>
                <option value="Sore" @if(old('nama') == "Sore") selected @endif>Sore</option>
                <option value="Malam" @if(old('nama') == "Malam") selected @endif>Malam</option>
            </select>
            <x-partials.error-message name="nama" class="d-block"/>

            {{-- Input Server Time --}}
            {{-- <x-partials.label title="Server Time" />
            <select name="server_time" class="form-control">
                @foreach ($timezoneList as $key => $item)
                    <option value="{{ $key }}">{{ $key }} || {{ $item }}</option>
                @endforeach
            </select> --}}

            <div class="row mt-3">
                <div class="col">

                    {{-- Input Jam Masuk --}}
                    <x-partials.label title="Jam Masuk" />
                    {{-- <input class="form-control @error('jam_masuk') is-invalid @enderror" type="datetime" name="jam_masuk" value="{{ old('jam_masuk') }}"> --}}
                    <input type="text" class="form-control @error('jam_masuk') is-invalid @enderror" placeholder="HH:MM" id="jam_masuk" name="jam_masuk" style="background-color: white" value="{{ old('jam_masuk') }}" />
                    <x-partials.error-message name="jam_masuk" class="d-block"/>

                </div>
                <div class="col">

                    {{-- Input Jam Pulang --}}
                    <x-partials.label title="Jam Pulang" />
                    <input type="text" class="form-control @error('jam_pulang') is-invalid @enderror" placeholder="HH:MM" id="jam_pulang" name="jam_pulang" style="background-color: white" value="{{ old('jam_keluar') }}"/>
                    <x-partials.error-message name="jam_pulang" class="d-block"/>

                </div>
            </div>


        </div>
        <div class="card-footer">
            <a href="{{ route('shift.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<x-slot name="script">
    <script>
        $(document).ready(function () {
            $("#jam_masuk").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });

            $("#jam_pulang").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        });
    </script>
</x-slot>

</x-layouts.app>
