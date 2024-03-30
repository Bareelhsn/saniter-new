<x-layouts.app title="Role">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Role</h4>

    <div class="row">
        <div class="col-md-12">

            {{-- Menu --}}
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    {{-- Jika request url adalah url yg di tentukan, set class active --}}
                    <a class="nav-link active" href="{{ route('pengaturan.index') }}"><i class="bx bx-left-arrow-alt me-1"></i> Kembali</a>
                </li>
            </ul>

            <div class="card mb-4">

                {{-- Update Role --}}
                <h5 class="card-header">Manajemen Role</h5>

                <div class="card-body">

                    <div class="mb-4">
                        <div class="d-flex align-items-start align-items-sm-center gap-2">
                            <a href="{{ route('pengaturan.role.create') }}" class="btn btn-secondary me-0"><i class="bx bx-plus"></i>Tambah Role</a>
                        </div>
                    </div>

                    <table id="role-table" class="table table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Permissions</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Permissions</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div>
    </div>

    {{-- Modal Tambah Role --}}
    <div class="modal fade" id="modalRole" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                {{-- Form Tambah Menu --}}
                <form action="{{ route('pengaturan.role.store') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Tambah Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @csrf

                    <div class="modal-body">

                        {{-- Input Judul --}}
                        <x-input-text title="Role" name="name" placeholder="Masukkan role" margin="mb-3"/>

                        <table class="table">
                            <thead>
                                <th>Menu</th>
                                <th>Permissions</th>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $items)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>
                                        @foreach ($items as $item)
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                            <label class="form-check-label" for="inlineCheckbox1">{{ $item->name }}</label>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

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
        <script>
            $(document).ready(function () {
                // Datatables
                $('#role-table').DataTable({
                    ajax: "{{ route('ajax.getRole') }}",
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    columns: [
                        {data: 'DT_RowIndex', searchable: false },
                        {data: 'name'},
                        {data: 'permissions', render: function (data, type, row) {
                            var result = '';
                            for (let index = 0; index < data.length; index++) {
                                result = result + ' <span class="badge bg-label-dark">'+ data[index].name +'</span>';
                            }
                            return result;
                        }, orderable: false, searchable: false},
                        {data: 'action', orderable: false, searchable: false},
                    ],columnDefs: [{ width: '70%', targets: 2 }]
                })

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

            // Reset is-invalid form validation
            function resetFormValidation(){
                $(".is-invalid").removeClass("is-invalid")
                $(".invalid-feedback").addClass("d-none")
            }
        </script>

        {{-- Jika terdapat session dengan nama modalAdd, untuk validasi popup otomatis --}}
        @if (Session::has('modalAdd'))
        <script>
            $(document).ready(function () {
                $('#modalRole').modal('show');
            });
            </script>
        @endif

        {{-- Jika terdapat session dengan nama modalEdit, untuk validasi popup otomatis --}}
        @if (Session::has('modalEdit'))
            <script>
                $(document).ready(function () {
                    $('#modalEditRole').modal('show')
                });
            </script>
        @endif
    </x-slot>

</x-layouts.app>
