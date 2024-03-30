<x-layouts.app title="Pengaturan">
<h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Izin /</span> Data Izin Teknisi</h5>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="bx bx-plus-circle me-1"></i> Buat Izin</a>
            </li>
        </ul>
        <div class="card mb-4">

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="tabel-user">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Lama Izin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        {{-- <tbody class="table-border-bottom-0">
                            <?php $no = 1; ?>
                            @foreach ($regional as $key => $u)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $u->nama }}</td>
                                <td>

                                    <button
                                    type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#update{{ $key }}">
                                    Update
                                    </button>

                                    <form method="POST" action="{{ route('regional.delete', $u->id) }}" class="d-inline">
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
                                            <form method="POST" action="{{ route('regional.update', $u->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel1">Detail Nama Regional</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col mb-3">
                                                            <label for="nameBasic" class="form-label">Nama Regional</label>
                                                            <input type="text" name="nama" class="form-control" value="{{ $u->nama }}" />
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
    </div>
    <!-- / Content -->
</div>

</x-layouts.app>
