<div class="modal fade" id="detailUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-auto col-sm-12 col-md-auto mb-3">
                        <label class="form-label" for="basic-icon-default-phone">Foto</label>
                        {{-- Foto Profile --}}
                        <img src="{{ asset('storage/'. $user->foto) }}" alt="user-avatar" class="d-block img-fluid mx-auto d-block" style="max-height: 250px"/>
                    </div>
                    <div class="col-sm-12 col-md">
                        <table class="table table-striped ">
                            <tr>
                                <td>Role</td>
                                <td colspan="2">: <span class="badge bg-info">{{ $user->roles_name }}</span></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td>: {{ $user->nik }}</td>
                            </tr>
                            <tr>
                                <td>Alamat KTP</td>
                                <td>: {{ $user->alamat_ktp }}</td>
                            </tr>
                            <tr>
                                <td>Alamat Domisili</td>
                                <td>: {{ $user->alamat_dom }}</td>
                            </tr>
                            <tr>
                                <td>Regional Kerja</td>
                                <td>: {{ $user->regional_name }}</td>
                            </tr>
                            <tr>
                                <td>Lokasi Proyek</td>
                                <td>: {{ $user->bandara }} | {{ $user->lokasi }}</td>
                            </tr>
                            <tr>
                                <td>Nomor Telepon</td>
                                <td>: {{ $user->telp }}</td>
                            </tr>
                            <tr>
                                <td>Tanda Tangan</td>
                                <td>:
                                    @if ($user->ttd)
                                        <img src="{{ asset('storage/'. $user->ttd) }}" alt="user-avatar" class="img-fluid ms-2" style="max-height: 100px"/>
                                    @else
                                        <img src="{{ asset('storage/user-ttd/default.jpg') }}" alt="user-avatar" class="img-fluid ms-2" style="max-height: 100px"/>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer mt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
