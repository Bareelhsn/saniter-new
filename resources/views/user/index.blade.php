<x-layouts.app title="User">
    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Administrasi /</span> User</h4>

    <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard') }}"><i class="bx bx-left-arrow-alt me-1"></i> Kembali</a>
        </li>
    </ul>

<div class="card">
    <h5 class="card-header">Data Akun Saniter</h5>
    <div class="card-body">
        @can('user_create')
            <a href="{{ route('user.create') }}" class="mb-4 btn btn-secondary"><i class="bx bx-plus"></i> Tambah User</a>
        @endcan

        <table class="table table-hover" id="user-table" width="100%">
            <thead>
                <tr>
                    <th width="1">No</th>
                    <th>Nama</th>
                    <th width="1">Regional</th>
                    <th width="1">Email</th>
                    <th width="auto">Aktif</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Regional</th>
                    <th>Email</th>
                    <th>Aktif</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div id="modalDetailUser"></div>

<x-slot name="script">
    <script>
        $(document).ready(function () {
            // Datatables
            $('#user-table').DataTable({
                ajax: "{{ route('ajax.getUser') }}",
                processing: true,
                serverSide: true,
                responsive: true,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    {data: 'name', name: 'name'},
                    {data: 'regional_name', name: 'regional_name'},
                    {data: 'email', name: 'email'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'roles_name', name: 'roles_name'},
                    {data: 'action', name: 'action'},
                ],
            })

            $("#path").change(function () {
                const file = this.files[0];
                console.log(file);
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $("#imagePreview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
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

            // Jika tombol delete diklik
            $(document).on("click", "button.confirm-edit-is-active", function () {
                var form = $(this).closest("form");
                event.preventDefault();
                Swal.fire({ // SweetAlert
                    title: "Apa kamu yakin?",
                    text: "Mengubah status aktif user?",
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
    </script>
    <script>
        // Ketika tombol edit diklik
        function detailData(e){
            // Mengatur ajax csrf
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('ajax.getUserDetail') }}",
                data: {
                    id: e.dataset.id // Mengambil id pada event
                },
                dataType: "json",
                success: function (response) { // Jika ajax sukses dan memberikan respon
                    $('#modalDetailUser').html('');
                    $('#modalDetailUser').html(response.modal);
                    $('#detailUser').modal('show');
                }
            });
        }
    </script>
</x-slot>

</x-layouts.app>
