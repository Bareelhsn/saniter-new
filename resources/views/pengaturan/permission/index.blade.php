<x-layouts.app title="Permission">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Permission</h4>

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

                <h5 class="card-header">Manajemen Permission</h5>

                <div class="card-body">

                    <div class="mb-4">
                        <div class="d-flex align-items-start align-items-sm-center gap-2">
                            <button type="button" class="btn btn-secondary me-0" data-bs-toggle="modal" data-bs-target="#modalPermission" onclick="resetFormValidation()"><i class="bx bx-plus"></i>Tambah Permission</button>
                        </div>
                    </div>

                    <table id="role-table" class="table table-hover table-sm" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Menu</th>
                                <th>Name</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Menu</th>
                                <th>Name</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div>
    </div>

    {{-- Modal Tambah Permission --}}
    <div class="modal fade" id="modalPermission" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                {{-- Form Tambah Menu --}}
                <form action="{{ route('pengaturan.permission.store') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Tambah Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @csrf

                    <div class="modal-body">

                        {{-- Input Menu --}}
                        <div class="mb-3">
                            <x-partials.label title="Menu"/>
                            <select name="id_menu" class="form-select @error('id_menu')is-invalid @enderror" id="id_menu">
                                <option value="" selected disabled>Pilih Menu...</option>
                                @foreach ($menu as $item)
                                    <option @if(old('id_menu') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->judul }}</option>
                                @endforeach
                            </select>
                            <x-partials.error-message class="d-block" name="id_menu" />
                        </div>

                        {{-- Input Judul --}}
                        <x-input-text title="Permission" name="name" placeholder="Masukkan permission" id="name"/>
                        <x-partials.input-desc text="Contoh user_create, user_read, user_update, user_delete." class="mb-3"/>

                        {{-- Input Otomatis CRUD --}}
                        <div class="div">
                            <div class="form-check form-check-inline mb-3">
                                <input class="form-check-input" type="checkbox" name="otomatis" id="otomatis">
                                <label class="form-check-label" for="defaultCheck1"> Otomatis permission CRUD. </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="custom" id="custom" disabled >
                                <label class="form-check-label" for="defaultCheck1"> Custom nama CRUD. </label>
                            </div>
                        </div>

                        <div id="customCRUD"></div>

                        {{-- Input Permisson --}}
                        <x-partials.label title="Role" />
                        <select id="choices-multiple-remove-button" name="role[]" placeholder="Pilih role." multiple>
                            @foreach ($roles as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <x-partials.input-desc text="Biarkan kosong jika tidak ingin menambah role." style="margin-top: -20px !important" />
                        <x-partials.error-message class="d-block" name="role" style="margin-top: -24px" />

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
                    ajax: "{{ route('ajax.getPermission') }}",
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                        {data: 'judul', name: 'judul'},
                        {data: 'name', name: 'name'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ],
                    rowGroup: {
                        dataSrc: 'judul'
                    },
                    columnDefs: [
                    {
                        target: 1,
                        visible: false,
                        searchable: false
                    }
                ]
                })

                var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
                    removeItemButton: true,
                });

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

            var namaPermission = "";

            // Jika menu dipilih
            $('#id_menu').on('change', function(){
                namaPermission = $(this).find(":selected").text().toLowerCase()+"_";
                if ($('#otomatis').is(':not(:checked)')) {
                    $('#name').val($(this).find(":selected").text().toLowerCase()+"_");
                }
            })

            // Jika checkbox berubah
            $('#otomatis').on('change', function (e) {
                if (this.checked) { // Apakah ter check
                    $('#name').attr('readonly', 'readonly'); // Set id name ke readonly
                    $('#name').val('CRUD');
                    $('#custom').prop('disabled', false);
                }else{
                    $('#name').removeAttr('readonly'); // Hapus readonly
                    $('#name').val(namaPermission);
                    $('#custom').prop('disabled', true);
                }
            })

            // Jika checkbox berubah
            $('#custom').on('change', function (e) {
                if (this.checked) { // Apakah ter check
                    $('#customCRUD').html("<label class='form-label mt-3'>Custom Nama CRUD</label><input name='customCRUD' type='text' class='form-control' placeholder='Custom jika terdapat sub-menu di menu tersebut. Input tanpa underscore ( _ ).' /><div class='form-text mb-3'>Contoh user menginput proyek, akan menjadi proyek_create, proyek_read, proyek_update, proyek_delete. </div>");
                }else{
                    $('#customCRUD').html("");
                }
            })

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
                $('#modalPermission').modal('show');
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
