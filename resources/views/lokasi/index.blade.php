<x-layouts.app title="Pengaturan">
    <h5 class="fw-bold py-3 mb-4">Regional & Lokasi >> Lokasi</h5>

    <!-- Striped Rows -->
    <div class="card">
        <h5 class="card-header">Data Lokasi</h5>
        <div class="card-body">
            <a href="{{ route('lokasi.create') }}" class="mb-4 btn btn-primary">Tambah Lokasi</a>
            <table class=" table-responsive table table-striped" id="lokasi-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Regional</th>
                        <th>Nama Bandara</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th>No</th>
                        <th>Regional</th>
                        <th>Nama Bandara</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function () {
                // Datatables
                $('#lokasi-table').DataTable({
                    ajax: "{{ route('ajax.getLokasi') }}",
                    processing: true,
                serverSide: true,
                responsive: true,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                    {data: 'regional_name', name: 'regional_name'},
                    {data: 'nama_bandara', name: 'nama_bandara'},
                    {data: 'lokasi_proyek', name: 'lokasi_proyek'},
                    {data: 'action', name: 'action'},
                ],
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
        </script>
    </x-slot>
</x-layouts.app>
