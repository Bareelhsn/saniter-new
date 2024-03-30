<x-layouts.app title=" Tambah Role">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Tambah Role</h4>

    <div class="row">
        <div class="col-md-12">

            <div class="card mb-4">

                {{-- Update Profile --}}
                <h5 class="card-header">Manajemen Role</h5>

                {{-- Form Tambah Menu --}}
                <form action="{{ route('pengaturan.role.store') }}" method="POST">
                    @csrf

                    <div class="card-body">

                        {{-- Input Judul --}}
                        <x-input-text title="Role" name="name" placeholder="Masukkan role" margin="mb-3"/>

                        {{-- Input Permisson --}}
                        {{-- <x-partials.label title="Permission"/>
                        <select id="choices-multiple-remove-button" name="permissions[]" placeholder="Pilih permission." multiple>
                            @foreach ($permissions as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select> --}}

                        <table class="table table-bordered mt-4">
                            <thead>
                                <th>Menu</th>
                                <th>Permissions</th>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $key => $items)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input {{ $key }}-All" type="checkbox" id="checkBox{{ $key }}" data-judul="{{ $key }}" onchange="checkAll(this)">
                                            <label class="form-check-label" for="inlineCheckbox1">{{ $key }}</label>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach ($items as $item)
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input {{ $key }}" type="checkbox" value="{{ $item->name }}" name="checkBox[]" data-judul="{{ $key }}" onchange="checkJudul(this)">
                                            <label class="form-check-label" for="inlineCheckbox1">{{ $item->name }}</label>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" align="center">Permission masih kosong.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        {{-- Button Submit --}}
                        <a href="{{ route('pengaturan.role.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <x-slot name="script">
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
