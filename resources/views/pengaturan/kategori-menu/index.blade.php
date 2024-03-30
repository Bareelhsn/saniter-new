<x-layouts.app title=" Kategori Menu">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Kategori Menu</h4>

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

                <h5 class="card-header">Manajemen Menu</h5>

                <div class="card-body">

                    <div class="mb-4">
                        <div class="d-flex align-items-start align-items-sm-center gap-2">
                            <button type="button" class="btn btn-secondary me-0" data-bs-toggle="modal" data-bs-target="#modalKategoriMenu" onclick="resetFormValidation()"><i class="bx bx-plus"></i>Tambah Kategori Menu</button>
                        </div>
                    </div>

                    <table id="kategori-menu-table" class="table table-hover table-sm" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Kategori</th>
                                <th>Order</th>
                                <th>Show</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Nama Kategori</th>
                                <th>Order</th>
                                <th>Show</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div>
    </div>

    {{-- Modal Tambah Menu --}}
    <div class="modal fade" id="modalKategoriMenu" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                {{-- Form Tambah Menu --}}
                <form action="{{ route('pengaturan.kategorimenu.store') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Tambah Kategori Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @csrf

                    <div class="modal-body">

                        {{-- Input Judul --}}
                        <x-input-text title="Kategori Menu" name="nama_kategori" placeholder="Masukkan kategori menu" margin="mb-3" value="{{ old('nama_kategori') }}"/>

                        {{-- Input Order --}}
                        <x-input-number title="Urutan Order" name="order" placeholder="Masukkan order" value="{{ old('order') }}" />

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

    {{-- Modal Edit Menu --}}
    <div class="modal fade" id="modalKategoriMenuEdit" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                {{-- Form Tambah Menu --}}
                <form id="formEdit" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Kategori Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        {{-- Input Judul --}}
                        <x-input-text title="Kategori" name="nama_kategori" id="nama_kategoriEdit" placeholder="Masukkan kategori menu" margin="mb-3" value="{{ old('nama_kategori') }}"/>

                        {{-- Input Order --}}
                        <x-input-number title="Urutan Order" name="order" id="orderEdit" placeholder="Masukkan order" value="{{ old('order') }}" />

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
                $('#kategori-menu-table').DataTable({
                    ajax: "{{ route('ajax.getKategoriMenu') }}",
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                        {data: 'nama_kategori', name: 'nama_kategori'},
                        {data: 'order', name: 'order'},
                        {data: 'show', name: 'show'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
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

                // Jika tombol delete diklik
                $(document).on("click", "button.confirm-edit-show", function () {
                    var form = $(this).closest("form");
                    event.preventDefault();
                    Swal.fire({ // SweetAlert
                        title: "Apa kamu yakin?",
                        text: "Mengubah visible kategori!",
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
                    url: "{{ route('ajax.getKategoriMenuEdit') }}",
                    data: {
                        id: e.dataset.id // Mengambil id pada event
                    },
                    dataType: "json",
                    success: function (response) { // Jika ajax sukses dan memberikan respon
                        var menu = response.data;
                        var url = "{{ route('pengaturan.kategorimenu.update', ':id') }}"; // Action pada form edit
                        url = url.replace(':id', menu.id );
                        $("#formEdit")[0].reset();
                        $('#formEdit').attr('action', url);
                        $('#nama_kategoriEdit').val(menu.nama_kategori);
                        $('#orderEdit').val(menu.order);
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
        @if (Session::has('modalAdd'))
        <script>
            $(document).ready(function () {
                $('#modalKategoriMenu').modal('show');
            });
            </script>
        @endif

        {{-- Jika terdapat session dengan nama modalEdit, untuk validasi popup otomatis --}}
        @if (Session::has('modalEdit'))
            <script>
                $(document).ready(function () {
                    $('#modalEditKategoriMenu').modal('show')
                });
            </script>
        @endif
    </x-slot>

</x-layouts.app>
