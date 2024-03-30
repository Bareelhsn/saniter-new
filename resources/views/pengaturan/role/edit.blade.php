<x-layouts.app title="Ubah Role">

    <x-slot name="style">
        <style>
            .table th {
                text-align: left !important;
            }
        </style>
    </x-slot>

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Ubah Role</h4>

    <div class="row">
        <div class="col-md-12">

            <div class="card mb-4">

                {{-- Update Profile --}}
                <h5 class="card-header">Manajemen Role</h5>

                {{-- Form Tambah Menu --}}
                <form action="{{ route('pengaturan.role.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="oldPermission" value="{{ $role->permissions }}">

                    <div class="card-body">

                        {{-- Input Judul --}}
                        @if($role->name == 'Admin')
                            <x-input-text title="Role" name="name" placeholder="Masukkan role" margin="mb-3" value="{{ $role->name ?? old('name') }}" readonly/>
                        @else
                            <x-input-text title="Role" name="name" placeholder="Masukkan role" margin="mb-3" value="{{ $role->name ?? old('name') }}" />
                        @endif

                        <div class="table-responsive">
                        <table class="table table-bordered mt-4">
                            <thead>
                                <th>Menu</th>
                                <th>Permissions</th>
                            </thead>
                            <tbody>
                                @php $n=0; @endphp
                                @forelse ($permissionsAll as $key => $items)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline mt-3">
                                            <input class="form-check-input {{ $key }}-All" type="checkbox" id="checkBox{{ $key }}" data-judul="{{ $key }}" onchange="checkAll(this)" @if(getCheckedMenu($role->permissions, $key) > 0) checked @endif>
                                            <label class="form-check-label" for="inlineCheckbox1">{{ $key }}</label>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach ($items as $item)

                                            @php $print = true; @endphp

                                                @for ($i = 0; $i < count($role->permissions); $i++)

                                                    @if ($role->permissions[$i]->name === $item->name)
                                                        <div class="form-check form-check-inline mt-3">
                                                            <input class="form-check-input {{ $key }}" type="checkbox" value="{{ $item->name }}" name="checkBox[]" data-judul="{{ $key }}" onchange="checkJudul(this)" checked>
                                                            <label class="form-check-label" for="inlineCheckbox1">{{ $item->name }}</label>
                                                        </div>
                                                        @php $print = false; @endphp
                                                    @endif

                                                @endfor

                                        @if ($print == true)
                                            <div class="form-check form-check-inline mt-3">
                                                <input class="form-check-input {{ $key }}" type="checkbox" value="{{ $item->name }}" name="checkBox[]" data-judul="{{ $key }}" onchange="checkJudul(this)" >
                                                <label class="form-check-label" for="inlineCheckbox1">{{ $item->name }}</label>
                                            </div>
                                        @endif

                                        @endforeach
                                    </td>
                                </tr>
                                @php $n++; @endphp

                                @empty
                                    <tr>
                                        <td colspan="2" align="center">Permission masih kosong.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
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
