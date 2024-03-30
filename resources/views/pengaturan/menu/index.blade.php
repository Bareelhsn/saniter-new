<x-layouts.app title="Menu">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Menu</h4>

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

                {{-- Update Profile --}}
                <h5 class="card-header">Manajemen Menu</h5>

                <div class="card-body">

                    <div class="mb-4">
                        <div class="d-flex align-items-start align-items-sm-center gap-2">
                            <button type="button" class="btn btn-secondary me-0" data-bs-toggle="modal" data-bs-target="#modalMenu" onclick="resetFormValidation()"><i class="bx bx-plus"></i>Tambah Menu</button>
                        </div>
                    </div>

                    <table id="menu-table" class="table table-hover table-sm" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Order</th>
                                <th>Url</th>
                                <th>Icon</th>
                                <th>Kategori</th>
                                <th>Show</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Order</th>
                                <th>Url</th>
                                <th>Icon</th>
                                <th>Kategori</th>
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
    <div class="modal fade" id="modalMenu" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                {{-- Form Tambah Menu --}}
                <form action="{{ route('pengaturan.menu.store') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Tambah Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @csrf

                    <div class="modal-body">
                        {{-- Input Judul --}}
                        <x-input-text title="Judul" name="judul" placeholder="Masukkan judul menu" margin="mb-3" onkeyup="convertToSlug(this, 'url')" value="{{ old('judul') }}"/>

                        <div class="row">
                            <div class="col">

                                {{-- Input Kategori --}}
                                <div class="mb-3">
                                    <x-partials.label title="Kategori"/>
                                    <select id="id_kategori" name="id_kategori" class="form-select @error('id_kategori')is-invalid @enderror" onchange="selectKategori(this,'url')">
                                        <option value="" selected disabled>Pilih Kategori...</option>
                                        @foreach ($kategori as $item)
                                            <option @if(old('id_kategori') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    <x-partials.error-message class="d-block" name="id_kategori" />
                                </div>

                            </div>
                            <div class="col">

                                {{-- Input URL --}}
                                <x-input-text title="Url" name="url" id="url" placeholder="Masukkan url menu" value="{{ old('url') }}" />
                                <x-partials.input-desc text="Harap gunakan url sesuai nama kategori."/>

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">

                                {{-- Input Order --}}
                                <x-input-number title="Urutan Order" name="order" placeholder="Masukkan order" value="{{ old('order') }}" />

                            </div>
                            <div class="col">

                                {{-- Input Icon --}}
                                <x-input-text title="Icon" name="icon" placeholder="Masukkan icon dari box-icon" value="{{ old('icon') }}" />
                                <div class="form-text mt-0">Icon bisa dilihat disini <a href="https://boxicons.com/" target="_blank">BoxIcon</a></div>

                            </div>
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

    {{-- Modal Edit Menu --}}
    <div class="modal fade" id="modalEditMenu" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                {{-- Form Tambah Menu --}}
                <form id="formEdit" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        {{-- Input Judul --}}
                        <x-input-text title="Judul" name="judul" id="judulEdit" placeholder="Masukkan judul menu" margin="mb-3" onkeyup="convertToSlug2(this, 'urlEdit')" value="{{ old('judul') }}"/>

                        <div class="row">
                            <div class="col">

                                {{-- Input Kategori --}}
                                <div class="mb-3">
                                    <x-partials.label title="Kategori"/>
                                    <select id="id_kategoriEdit" name="id_kategori" class="form-select @error('id_kategori')is-invalid @enderror" onchange="selectKategori(this,'urlEdit')">
                                        <option value="" disabled>Pilih Kategori...</option>
                                        @foreach ($kategori as $item)
                                            <option @if(old('id_kategori') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    <x-partials.error-message class="d-block" name="id_kategori" />
                                </div>

                            </div>
                            <div class="col">

                                {{-- Input URL --}}
                                <x-input-text title="Url" name="url" id="urlEdit" placeholder="Masukkan url menu" value="{{ old('url') }}" />
                                <x-partials.input-desc text="Harap gunakan url sesuai nama kategori." />

                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">

                                {{-- Input Order --}}
                                <x-input-number title="Urutan Order" name="order" id="orderEdit" placeholder="Masukkan order" value="{{ old('order') }}"/>

                            </div>
                            <div class="col">

                                {{-- Input Icon --}}
                                <x-input-text title="Icon" name="icon" id="iconEdit" placeholder="Masukkan icon dari box-icon" value="{{ old('icon') }}" />
                                <div class="form-text mt-0">Icon bisa dilihat disini <a href="https://boxicons.com/" target="_blank">BoxIcon</a></div>

                            </div>
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
        <script>
            $(document).ready(function () {
                // Datatables
                $('#menu-table').DataTable({
                    ajax: "{{ route('ajax.getMenu') }}",
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                        {data: 'judul', name: 'judul'},
                        {data: 'order', name: 'order'},
                        {data: 'url', name: 'url', orderable: false},
                        {data: 'icon', name: 'icon', searchable: false, orderable: false},
                        {data: 'nama_kategori', name: 'nama_kategori', visible: false},
                        {data: 'show', name: 'show', searchable: false, orderable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ],
                    rowGroup: {
                        dataSrc: 'nama_kategori'
                    },
                    order : [[5,'asc']],
                    columnDefs: [
                    {
                        target: 5,
                        visible: false,
                        searchable: false
                    }]
                })

                // Jika tombol delete diklik
                $(document).on("click", "button.confirm-delete", function () {
                    var form = $(this).closest("form");
                    event.preventDefault();
                    Swal.fire({ // SweetAlert
                        title: "Apa kamu yakin?",
                        text: "Data submenu akan ikut terhapus!",
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

            var kategori = "";
            var judul_menu = "";

            // Jika kategori di pilih maka otomatis mengisi input field url
            function selectKategori(e, target){
                kategori = $(e).find(":selected").text().toLowerCase()+'/';
                $('#'+target).val(kategori+judul_menu);
            }

            // Mengubah inputan judul menjadi bentuk slug (onkeyup)
            function convertToSlug(e, target) {
                judul_menu = $(e).val();
                judul_menu = judul_menu.toLowerCase().replace(/ /g, "-").replace(/[^\w-]+/g, "");
                if ($("#id_kategori").find(":selected").val() == "") {
                    kategori = "";
                }else{
                    kategori = $("#id_kategori").find(":selected").text().toLowerCase()+'/';
                }
                $("#"+target).val(kategori+judul_menu)
            }

            function convertToSlug2(e, target) {
                judul_menu = $(e).val();
                judul_menu = judul_menu.toLowerCase().replace(/ /g, "-").replace(/[^\w-]+/g, "");
                if ($("#id_kategoriEdit").find(":selected").val() == "") {
                    kategori = "";
                }else{
                    kategori = $("#id_kategoriEdit").find(":selected").text().toLowerCase()+'/';
                }
                $("#"+target).val(kategori+judul_menu)
            }

            // Ketika tombol edit diklik
            function editData(e){

                resetFormValidation();

                // Mengatur ajax csrf
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{ route('ajax.getMenuEdit') }}",
                    data: {
                        id: e.dataset.id // Mengambil id pada event
                    },
                    dataType: "json",
                    success: function (response) { // Jika ajax sukses dan memberikan respon
                        var menu = response.data;
                        var url = "{{ route('pengaturan.menu.update', ':id') }}"; // Action pada form edit
                        url = url.replace(':id', menu.id );
                        $("#formEdit")[0].reset();
                        $('#formEdit').attr('action', url);
                        $('#judulEdit').val(menu.judul);
                        $('#urlEdit').val(menu.url);
                        $('#orderEdit').val(menu.order);
                        $('#iconEdit').val(menu.icon);
                        $('#id_kategoriEdit option[value='+menu.id_kategori+']').attr('selected','selected');
                        judul_menu = $('#judulEdit').val().toLowerCase().replace(/ /g, "-").replace(/[^\w-]+/g, "");
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
                $('#modalMenu').modal('show');
            });
            </script>
        @endif

        {{-- Jika terdapat session dengan nama modalEdit, untuk validasi popup otomatis --}}
        @if (Session::has('modalEdit'))
            <script>
                $(document).ready(function () {
                    $('#modalEditMenu').modal('show')
                });
            </script>
        @endif
    </x-slot>

</x-layouts.app>
