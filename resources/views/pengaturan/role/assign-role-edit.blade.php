<x-layouts.app title="Tetapkan Role User">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan / Tetapkan Role / </span> Tetapkan</h4>

    <div class="row">
        <div class="col-md-12">

            <div class="card mb-4">

                {{-- Update Profile --}}
                <h5 class="card-header">Manajemen Role</h5>

                {{-- Form Tambah Menu --}}
                <form action="{{ route('pengaturan.assign-role.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <x-input-text title="Nama User" name="user_name" value="{{ $user->name }}" readonly margin="mb-3"/>

                        <x-partials.label title="Role"/>
                        <select name="role_name" id="role_name" class="form-control" onchange="roleTable(this)">
                            <option value="" disabled selected>Pilih Role...</option>
                            @foreach ($role as $item)
                                <option @if(!empty($user->getRoleNames()[0]) && $user->getRoleNames()[0] == $item->name) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                        <div class="alert alert-info mt-3 mb-0 alert-role" role="alert"><strong>Pilih role terlebih dahulu.</strong></div>

                        <div id="roleTable">
                        </div>

                    </div>

                    <div class="card-footer">
                        {{-- Button Submit --}}
                        <a href="{{ route('pengaturan.assign-role.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function () {
                if ($('#role_name').val() != "") {
                    roleTable($('#role_name'));
                }
            });

            function roleTable(e) {
                // Mengatur ajax csrf
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{ route('ajax.getTabelRoleUser') }}",
                    data: {
                        idRole: $(e).val(),
                        idUser: "{{ $user->id }}"
                    },
                    dataType: "json",
                    success: function (response) {
                        $('#roleTable').html('');
                        $('#roleTable').html(response);
                        $('.alert-role').hide()
                    }
                });
            }
        </script>
        <script>
            function checkAll(e){
                if ($(e).is(":checked")) {
                    $('.'+e.dataset.judul).prop('checked', true);
                }else{
                    $('.'+e.dataset.judul).prop('checked', false);
                }
            }

            function checkJudul(e){
                if ($('.'+e.dataset.judul).is(':checked')) {
                    $('.'+e.dataset.judul+'-All').prop('checked', true);
                }else{
                    $('.'+e.dataset.judul+'-All').prop('checked', false);
                }
            }
        </script>
    </x-slot>

</x-layouts.app>
