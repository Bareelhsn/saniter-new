<x-layouts.app title="Pengaturan">
<h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Izin /</span> Setting Jumlah Izin Teknisi</h5>

<div class="row">
    <div class="col-md-12">

    <div class="alert alert-warning alertRegional" role="alert">Pilih regional terlebih dahulu!</div>

    <ul class="nav nav-pills flex-md-row mb-3">
        @foreach ($regional as $item)
            <li class="nav-item">
                <a class="nav-link btn-regional" href="#" data-id="{{ $item->id }}" onclick="getIzin(this)"><i class="bx bx-plus-circle me-1"></i> Regional {{ $item->nama }}</a>
            </li>
        @endforeach
    </ul>
        <div class="card mb-4">
            <h5 class="card-header">
                Jumlah Izin
            </h5>

            <div class="card-body">
            <a href="{{ route('izin.setting-create') }}" class="mb-4 btn btn-primary">Setting Izin</a>
                <table class="table table-striped" id="izin-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <!-- <th>Regional</th> -->
                            <th>Jumlah Izin</th>
                            <th>aksi</th>
                        </tr>
                    </thead>
                    {{-- <tbody class="table-border-bottom-0">
                        <?php $no = 1; ?>
                        @foreach ($jumlah as $key => $j)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $j->name_user }}</td>
                            <td>{{ $j->jumlah_izin }}</td>
                            <!-- <td>{{ $j->regional_nama }}</td> -->
                            <td>

                                <button
                                type="button"
                                class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#update{{ $key }}">
                                Update
                                </button>

                                <form method="POST" action="{{ route('izin.setting-delete', $j->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button
                                type="submit"
                                class="btn btn-danger btn-sm">
                                Hapus
                                </button>
                                </form>
                            </td>


                                <div class="modal fade" id="update{{ $key }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <form method="POST" action="{{ route('regional.update', $j->id) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Update Jumlah Izin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="nameBasic" class="form-label">Nama Teknisi</label>
                                                        <input type="text" name="user_id" class="form-control" value="{{ $j->name_user }}" readonly/>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-company">Tahun</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-company2" class="input-group-text"><i class="bx bxs-calendar"></i></span>
                                                        <input type="text" name="tahun" id="basic-icon-default-company" class="form-control" placeholder="20..">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-company">Jumlah Izin</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-company2" class="input-group-text"><i class="bx bx-plus-circle"></i></span>
                                                        <input type="text" name="jumlah_izin" id="basic-icon-default-company" class="form-control" placeholder="12">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>



                        </tr>
                        @endforeach
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->
</div>

<x-slot name="script">
    <script>
        var tableIzin;
        $(document).ready(function () {
            tableIzin = $('#izin-table').DataTable();
        });

        // Fungsi mengambil jumlah izin user sesuai regional
        function getIzin(e){
            $(".alertRegional").addClass('d-none');
            // Menghapus seluruh btn-regional yg memiliki class active
            $('.btn-regional').removeClass('active');

            // Menambah class active pada btn regional yg dipilih
            $(e).addClass('active');

            // Mengatur ajax csrf
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('ajax.getJumlahIzin') }}",
                data: {
                    id: e.dataset.id // Mengambil id pada event
                },
                dataType: "json",
                success: function (response) { // Jika ajax sukses dan memberikan respon
                    // Reload table
                    if (tableIzin != undefined) {
                        tableIzin.clear().destroy();
                    };

                    // Datatables
                    tableIzin = $('#izin-table').DataTable({
                        responsive: true,
                        data: response.data,
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },
                            {data: 'name'},
                            {data: 'jumlah_izin'},
                            {data: 'action', searchable: false , orderable: false}
                        ],
                    })
                }
            });
        }
    </script>
</x-slot>

</x-layouts.app>
