<x-layouts.app title="Regional">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Regional</h4>

    <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('pengaturan.index') }}"><i class="bx bx-left-arrow-alt me-1"></i> Kembali</a>
        </li>
    </ul>

<!-- Striped Rows -->
<div class="card">
    <h5 class="card-header">Data Regional</h5>
    <div class="card-body">
        @can('regional_create')
            <a href="{{ route('regional.create') }}" class="mb-4 btn btn-secondary"><i class="bx bx-plus"></i> Tambah Regional</a>
        @endcan

            <table class="table table-hover" id="tabel-regional">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        @if(auth()->user()->can('regional_update') || auth()->user()->can('regional_update'))
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        @if(auth()->user()->can('regional_update') || auth()->user()->can('regional_update'))
                            <th>Aksi</th>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Modal Edit Regional --}}
<div class="modal fade" id="modalEditRegional" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            {{-- Form Tambah Menu --}}
            <form method="POST" id="formEdit">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Sub Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @csrf

                <div class="modal-body">
                    <div class="mb-0">
                        <x-partials.label title="Nama Regional" />
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-fullname" class="input-group-text" @error('nama') style="border: 1px solid red" @enderror><i class="bx bx-user"></i></span>
                            <x-partials.input-text name="nama" placeholder="Nama Regional" id="namaEdit" />
                        </div>
                        <x-partials.error-message name="nama" class="d-block"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>

                    {{-- Button Submit --}}
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-slot name="script">
    {{-- Berikan akses role sesuai user->role --}}
    @if(auth()->user()->can('regional_update') || auth()->user()->can('regional_update'))
        <script>
            $(document).ready(function () {
                $('#tabel-regional').DataTable({
                    ajax: "{{ route('ajax.getRegional') }}",
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    columns:[
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                        {data: 'nama', name: 'nama'},
                        {data: 'action', name: 'action', searchable: false, orderable: false},
                    ]
                });
            });
        </script>
    @else
        <script>
            $(document).ready(function () {
                $('#tabel-regional').DataTable({
                    ajax: "{{ route('ajax.getRegional') }}",
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    columns:[
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                        {data: 'nama', name: 'nama'},
                    ]
                });
            });
        </script>
    @endif

    <script>
        $(document).ready(function () {
            // Jika tombol delete diklik
            $(document).on("click", "button.confirm-delete", function () {
                var form = $(this).closest("form");
                event.preventDefault();
                Swal.fire({ // SweetAlert
                    title: "Apa kamu yakin?",
                    text: "Data regional akan terhapus!",
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
                url: "{{ route('ajax.getRegionalEdit') }}",
                data: {
                    id: e.dataset.id // Mengambil id pada event
                },
                dataType: "json",
                success: function (response) { // Jika ajax sukses dan memberikan respon
                    var regional = response.data;
                    var url = "{{ route('regional.update', ':id') }}"; // Action pada form edit
                    url = url.replace(':id', regional.id );
                    $('#formEdit').attr('action', url);
                    $('#namaEdit').val(regional.nama);
                }
            });
            }

            // Reset is-invalid form validation
            function resetFormValidation(){
            $(".is-invalid").removeClass("is-invalid")
            $(".invalid-feedback").addClass("d-none")
            }
    </script>
</x-slot>

</x-layouts.app>
