<x-layouts.app title="Shift">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Shift</h4>

<!-- Striped Rows -->
<div class="card">
    <h5 class="card-header">Data Shift</h5>
    <div class="card-body">
        @can('shift_create')
            <a href="{{ route('shift.create') }}" class="mb-4 btn btn-secondary"><i class="bx bx-plus"></i> Tambah Shift</a>
        @endcan

            <table class="table table-hover" id="tabel-shift">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Server Time</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Server Time</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Modal Edit Regional --}}
<div class="modal fade" id="modalEditShift" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Ubah Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Form Tambah Menu --}}
            <form method="post" id="formEdit">
                @csrf
                @method('PUT')
                <div class="card-body">

                    {{-- Input Nama Shift --}}
                    <x-partials.label title="Shift" />
                    <select name="namaEdit" class="form-control @error('namaEdit') is-invalid @enderror" id="namaEdit">
                        <option value="" selected disabled>Pilih Shift...</option>
                        <option value="Pagi" @if(old('nama') == "Pagi") selected @endif>Pagi</option>
                        <option value="Sore" @if(old('nama') == "Sore") selected @endif>Sore</option>
                        <option value="Malam" @if(old('nama') == "Malam") selected @endif>Malam</option>
                    </select>
                    <x-partials.error-message name="namaEdit" class="d-block"/>

                    <div class="row mt-3">
                        <div class="col">

                            {{-- Input Jam Masuk --}}
                            <x-partials.label title="Jam Masuk" />
                            {{-- <input class="form-control @error('jam_masuk') is-invalid @enderror" type="datetime" name="jam_masuk" value="{{ old('jam_masuk') }}"> --}}
                            <input type="text" class="form-control @error('jam_masukEdit') is-invalid @enderror" placeholder="HH:MM" id="jam_masuk" name="jam_masukEdit" style="background-color: white" value="{{ old('jam_masukEdit') }}" />
                            <x-partials.error-message name="jam_masuk" class="d-block"/>

                        </div>
                        <div class="col">

                            {{-- Input Jam Pulang --}}
                            <x-partials.label title="Jam Pulang" />
                            <input type="text" class="form-control @error('jam_pulangEdit') is-invalid @enderror" placeholder="HH:MM" id="jam_pulang" name="jam_pulangEdit" style="background-color: white" value="{{ old('jam_keluarEdit') }}"/>
                            <x-partials.error-message name="jam_pulangEdit" class="d-block"/>

                        </div>
                    </div>


                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-slot name="script">
    <script>
        $(document).ready(function () {
            $('#tabel-shift').DataTable({
                ajax: "{{ route('ajax.getShift') }}",
                processing: true,
                serverSide: true,
                responsive: true,
                columns:[
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    {data: 'nama', name: 'nama'},
                    {data: 'server_time', name: 'server_time'},
                    {data: 'jam_masuk', name: 'jam_masuk'},
                    {data: 'jam_pulang', name: 'jam_pulang'},
                    {data: 'action', name: 'action', searchable: false, orderable: false},
                ]
            });

            $("#jam_masuk").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                defaultDate: "00:00"
            });

            $("#jam_pulang").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                defaultDate: "00:00"
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Jika tombol delete diklik
            $(document).on("click", "button.confirm-delete", function () {
                var form = $(this).closest("form");
                event.preventDefault();
                Swal.fire({ // SweetAlert
                    title: "Apa kamu yakin?",
                    text: "Data akan terhapus!",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yakin",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) { // Jika iyaa form akan tersubmit
                        form.submit();
                    }
                });
            });
        });

        // Ketika tombol edit diklik
        function editData(e){

            resetFormValidation()

            // Mengatur ajax csrf
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('ajax.getShiftEdit') }}",
                data: {
                    id: e.dataset.id // Mengambil id pada event
                },
                dataType: "json",
                success: function (response) { // Jika ajax sukses dan memberikan respon
                    var shift = response.data;
                    var url = "{{ route('shift.update', ':id') }}"; // Action pada form edit
                    url = url.replace(':id', shift.id );
                    $("#formEdit")[0].reset();
                    $('#formEdit').attr('action', url);
                    $('#jam_masuk').val(shift.jam_masuk);
                    $('#jam_pulang').val(shift.jam_pulang);
                    $('#namaEdit option[value='+shift.nama+']').attr('selected','selected');

                    $("#jam_masuk").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                        defaultDate: shift.jam_masuk
                    });

                    $("#jam_pulang").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                        defaultDate: shift.jam_pulang
                    });
                }
            });
            }

            // Reset is-invalid form validation
            function resetFormValidation(){
            $(".is-invalid").removeClass("is-invalid")
            $(".invalid-feedback").addClass("d-none")
            }
    </script>

    {{-- Jika terdapat session dengan nama modalAdd, untuk validasi popup otomatis --}}
    @if (Session::has('modalEdit'))
    <script>
        $(document).ready(function () {
            $('#modalEditShift').modal('show');
        });
        </script>
    @endif

</x-slot>

</x-layouts.app>
